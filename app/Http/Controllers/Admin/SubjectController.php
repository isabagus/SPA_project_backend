<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\RubricCategory;
use App\Models\Term;
use App\Models\Teacher;
use App\Models\LevelClass;
use App\Models\CategorySubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectController extends Controller
{

    public function index()
    {
        // Ambil subject normal + hanya perwakilan PERTAMA dari setiap grup RS/PKN
        $subjects = Subject::with('class')
            ->where(function ($query) {
                $query->whereNull('report_group_key')
                      ->orWhereIn('subject_id', function ($sub) {
                          $sub->selectRaw('MIN(subject_id)')
                              ->from('subjects')
                              ->whereNotNull('report_group_key')
                              ->groupBy('report_group_key');
                      });
            })
            ->orderBy('level_class', 'asc')
            ->orderBy('term', 'asc')
            ->paginate(10);

        // Ganti nama tampilan untuk subject grup agar Admin melihat nama yang ramah
        $subjects->getCollection()->transform(function ($subject) {
            if ($subject->report_group_key && str_starts_with($subject->report_group_key, 'GRP_AF_RS_PKN')) {
                $subject->category_subject = 'Affective Domain RS & PKN';
            }
            return $subject;
        });

        return view('layouts.subjects.index', compact('subjects'));
    }

    public function show($id)
    {
        $subject = Subject::with(['rubrics.teacher', 'rubrics.criteria', 'class'])->findOrFail($id);
        return view('layouts.subjects.detail', compact('subject'));
    }

    public function create()
    {
        $terms = Term::all();
        $teachers = Teacher::with('subjects')->get();
        $categorySubjects = CategorySubject::whereNotIn('category_subject', [
            'PKN', 
            'Religion (Christianity)', 
            'Religion (Catholicism)', 
            'Religion (Islam)'
        ])->get();
        $years = LevelClass::all();
        
        return view('layouts.subjects.create', compact('terms', 'teachers', 'categorySubjects', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_subject' => 'required|string',
            'term'             => 'required|string',
            'class_id'         => 'required|string',
            'rubrics'          => 'required|array|min:1',
            'rubrics.*.name'   => 'required|string',
            'rubrics.*.teacher_id' => 'nullable|exists:teachers,teacher_id',
            'rubrics.*.criteria'   => 'required|array|min:1',
            'rubrics.*.criteria.*.name' => 'required|string',
        ]);

        $isRsPkn = ($request->category_subject === 'Affective Domain RS & PKN');

        // CEK DUPLIKASI
        if ($isRsPkn) {
            $exists = Subject::where('report_group_key', 'LIKE', 'GRP_AF_RS_PKN_' . $request->class_id . '%')
                ->where('term', $request->term)
                ->exists();
        } else {
            $exists = Subject::where('category_subject', $request->category_subject)
                ->where('term', $request->term)
                ->where('class_id', $request->class_id)
                ->exists();
        }

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => "Subject '{$request->category_subject}' untuk kelas ini di {$request->term} sudah ada."]);
        }

        $levelClass = LevelClass::with('mentor.user')->find($request->class_id);

        // Find Mentor's Teacher record (Fallback)
        $mentorTeacherId = null;
        if ($levelClass->mentor) {
            $teacher = Teacher::where('user_id', $levelClass->mentor->user_id)->first();
            $mentorTeacherId = $teacher ? $teacher->teacher_id : null;
        }

        DB::transaction(function () use ($request, $levelClass, $mentorTeacherId, $isRsPkn) {
            if ($isRsPkn) {
                // ============================================================
                // TEMPLATE GENERATOR MODE: Auto-generate 4 subjects
                // ============================================================
                $groupKey = 'GRP_AF_RS_PKN_' . $request->class_id . '_' . Str::snake($request->term);

                // Ambil teacher PKN dari form (rubrik PKN punya dropdown guru)
                $pknTeacherId = null;
                $pknRubricData = null;
                $rsRubricData = null;

                foreach ($request->rubrics as $rubricData) {
                    if ($rubricData['name'] === 'PKN') {
                        $pknTeacherId = $rubricData['teacher_id'] ?? $mentorTeacherId;
                        $pknRubricData = $rubricData;
                    } elseif ($rubricData['name'] === 'Religious Studies / Agama') {
                        $rsRubricData = $rubricData;
                    }
                }

                // 1. Generate PKN subject
                $pknSubject = Subject::create([
                    'category_subject' => 'PKN',
                    'term'             => $request->term,
                    'class_id'         => $request->class_id,
                    'level_class'      => $levelClass->level_name,
                    'teacher_id'       => $pknTeacherId,
                    'report_group_key' => $groupKey
                ]);

                if ($pknRubricData) {
                    RubricCategory::create([
                        'subject_id'  => $pknSubject->subject_id,
                        'rubric_name' => 'PKN',
                        'teacher_id'  => $pknTeacherId,
                        'term'        => $request->term,
                    ])->criteria()->createMany(
                        array_map(fn($c) => ['criteria_name' => $c['name']], $pknRubricData['criteria'])
                    );
                }

                // 2. Generate Religion subjects (teacher_id = null, di-assign nanti)
                $religions = ['Religion (Islam)', 'Religion (Christianity)', 'Religion (Catholicism)'];

                foreach ($religions as $religion) {
                    $religionSubject = Subject::create([
                        'category_subject' => $religion,
                        'term'             => $request->term,
                        'class_id'         => $request->class_id,
                        'level_class'      => $levelClass->level_name,
                        'teacher_id'       => null, // Belum di-assign, akan di-assign di halaman khusus
                        'report_group_key' => $groupKey
                    ]);

                    if ($rsRubricData) {
                        RubricCategory::create([
                            'subject_id'  => $religionSubject->subject_id,
                            'rubric_name' => 'Religious Studies / Agama',
                            'teacher_id'  => null,
                            'term'        => $request->term,
                        ])->criteria()->createMany(
                            array_map(fn($c) => ['criteria_name' => $c['name']], $rsRubricData['criteria'])
                        );
                    }
                }
            } else {
                // ============================================================
                // STANDARD MODE: Single Subject
                // ============================================================
                $primaryTeacherId = $request->rubrics[0]['teacher_id'] ?? $mentorTeacherId;

                $subject = Subject::create([
                    'category_subject' => $request->category_subject,
                    'term'             => $request->term,
                    'class_id'         => $request->class_id,
                    'level_class'      => $levelClass->level_name,
                    'teacher_id'       => $primaryTeacherId,
                    'report_group_key' => null
                ]);

                foreach ($request->rubrics as $rubricData) {
                    $rubricTeacherId = $rubricData['teacher_id'] ?? $primaryTeacherId;

                    RubricCategory::create([
                        'subject_id'  => $subject->subject_id,
                        'rubric_name' => $rubricData['name'],
                        'teacher_id'  => $rubricTeacherId,
                        'term'        => $request->term,
                    ])->criteria()->createMany(
                        array_map(fn($c) => ['criteria_name' => $c['name']], $rubricData['criteria'])
                    );
                }
            }
        });

        if ($isRsPkn) {
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Template RS & PKN berhasil di-generate! Silakan klik "Assign Guru" untuk menugaskan guru agama.');
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject, Kategori, dan Kriteria berhasil disimpan!');
    }

    // ================================================================
    // ASSIGN TEACHERS: Halaman khusus untuk assign guru ke grup RS/PKN
    // ================================================================

    public function assignTeachers($id)
    {
        $subject = Subject::findOrFail($id);

        if (!$subject->report_group_key) {
            return redirect()->route('admin.subjects.index')->withErrors(['error' => 'Subject ini bukan bagian dari grup RS & PKN.']);
        }

        $groupSubjects = Subject::with(['rubrics.teacher'])
            ->where('report_group_key', $subject->report_group_key)
            ->orderBy('category_subject')
            ->get();

        $teachers = Teacher::with('subjects')->get();

        return view('layouts.subjects.assign_teachers', compact('groupSubjects', 'teachers', 'subject'));
    }

    public function updateTeachers(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        if (!$subject->report_group_key) {
            return redirect()->route('admin.subjects.index')->withErrors(['error' => 'Subject ini bukan bagian dari grup RS & PKN.']);
        }

        $request->validate([
            'teachers'   => 'required|array',
            'teachers.*' => 'nullable|exists:teachers,teacher_id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->teachers as $subjectId => $teacherId) {
                if ($teacherId) {
                    // Update subject-level teacher
                    Subject::where('subject_id', $subjectId)->update(['teacher_id' => $teacherId]);
                    // Update rubric-level teacher
                    RubricCategory::where('subject_id', $subjectId)->update(['teacher_id' => $teacherId]);
                }
            }
        });

        return redirect()->route('admin.subjects.index')->with('success', 'Guru berhasil ditugaskan ke semua subjek RS & PKN!');
    }

    // ================================================================
    // STANDARD CRUD: Edit, Update, Destroy
    // ================================================================

    public function edit($id)
    {
        $subject = Subject::with(['rubrics.teacher', 'rubrics.criteria'])->findOrFail($id);

        // Block edit untuk grouped subjects — arahkan ke Assign Teachers
        if ($subject->report_group_key) {
            return redirect()->route('admin.subjects.assignTeachers', $subject->subject_id)
                ->with('info', 'Subjek grup RS & PKN diarahkan ke halaman Assign Guru.');
        }

        $terms = Term::all();
        $teachers = Teacher::with('subjects')->get();
        $subjectCategories = Subject::select('category_subject')->distinct()->get();
        $years = LevelClass::all();

        return view('layouts.subjects.edit', compact('subject', 'terms', 'teachers', 'subjectCategories', 'years'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_subject' => 'required|string',
            'term'             => 'required|string',
            'level_class'      => 'required|string',
            'rubrics'          => 'required|array|min:1',
            'rubrics.*.name'   => 'required|string',
            'rubrics.*.teacher_id' => 'required|exists:teachers,teacher_id',
            'rubrics.*.criteria'   => 'required|array|min:1',
            'rubrics.*.criteria.*.name' => 'required|string',
        ]);

        $levelClass = LevelClass::find($request->level_class);

        DB::transaction(function () use ($request, $id, $levelClass) {
            $subject = Subject::findOrFail($id);
            $subject->update([
                'category_subject' => $request->category_subject,
                'term'             => $request->term,
                'level_class'      => $levelClass->level_name,
                'class_id'         => $request->level_class,
                'teacher_id'       => $request->rubrics[0]['teacher_id'] ?? null,
            ]);

            $existingRubricIds = collect($request->rubrics)->pluck('rubric_id')->filter()->toArray();
            $subject->rubrics()->whereNotIn('rubric_id', $existingRubricIds)->delete();

            foreach ($request->rubrics as $rubricData) {
                if (isset($rubricData['rubric_id'])) {
                    $category = RubricCategory::findOrFail($rubricData['rubric_id']);
                    $category->update([
                        'rubric_name' => $rubricData['name'],
                        'teacher_id'  => $rubricData['teacher_id'],
                        'term'        => $request->term,
                    ]);
                } else {
                    $category = RubricCategory::create([
                        'subject_id'  => $subject->subject_id,
                        'rubric_name' => $rubricData['name'],
                        'teacher_id'  => $rubricData['teacher_id'],
                        'term'        => $request->term,
                    ]);
                }

                // Handle Nested Criteria Synchronization
                $existingCriteriaIds = collect($rubricData['criteria'])->pluck('criteria_id')->filter()->toArray();
                $category->criteria()->whereNotIn('criteria_id', $existingCriteriaIds)->delete();

                foreach ($rubricData['criteria'] as $criteriaData) {
                    if (isset($criteriaData['criteria_id'])) {
                        \App\Models\RubricCriteria::where('criteria_id', $criteriaData['criteria_id'])->update([
                            'criteria_name' => $criteriaData['name'],
                        ]);
                    } else {
                        \App\Models\RubricCriteria::create([
                            'rubric_id'     => $category->rubric_id,
                            'criteria_name' => $criteriaData['name'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.subjects.index')->with('success', 'Subject, Kategori, dan Kriteria berhasil diupdate!');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        if ($subject->report_group_key) {
            // Hapus semua subjek dalam grup
            Subject::where('report_group_key', $subject->report_group_key)->delete();
        } else {
            $subject->delete();
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject berhasil dihapus!');
    }
}
