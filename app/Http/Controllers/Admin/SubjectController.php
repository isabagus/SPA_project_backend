<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\RubricCategory;
use App\Models\Term;
use App\Models\Teacher;
use App\Models\LevelClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{

    public function index()
    {
        $subjects = Subject::with('class')
            ->orderBy('level_class', 'asc')
            ->orderBy('term', 'asc')
            ->paginate(10);
            
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
        $teachers = Teacher::all();
        $subjects = Subject::select('category_subject')->distinct()->get();
        $years = LevelClass::all();
        
        return view('layouts.subjects.create', compact('terms', 'teachers', 'subjects', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_subject' => 'required|string',
            'term'             => 'required|string',
            'class_id'         => 'required|string',
            'rubrics'          => 'required|array|min:1',
            'rubrics.*.name'   => 'required|string',
            'rubrics.*.teacher_id' => 'required|exists:teachers,teacher_id',
            'rubrics.*.criteria'   => 'required|array|min:1',
            'rubrics.*.criteria.*.name' => 'required|string',
        ]);

        // CEK DUPLIKASI: Subject dengan Category, Term, dan Level yang sama
        $exists = Subject::where('category_subject', $request->category_subject)
            ->where('term', $request->term)
            ->where('class_id', $request->class_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => "Subject '{$request->category_subject}' untuk {$request->class_id} di {$request->term} sudah ada. Silakan gunakan menu Edit untuk menambah rubrik."]);
        }

        $levelClass = LevelClass::find($request->class_id);

        DB::transaction(function () use ($request, $levelClass) {
            $subject = Subject::create([
                'category_subject' => $request->category_subject,
                'term'             => $request->term,
                'class_id'         => $request->class_id,
                'level_class'      => $levelClass->level_name, // Store name for display
            ]);

            foreach ($request->rubrics as $rubricData) {
                $category = RubricCategory::create([
                    'subject_id'  => $subject->subject_id,
                    'rubric_name' => $rubricData['name'],
                    'teacher_id'  => $rubricData['teacher_id'],
                    'term'        => $request->term,
                ]);

                foreach ($rubricData['criteria'] as $criteriaData) {
                    \App\Models\RubricCriteria::create([
                        'rubric_id'     => $category->rubric_id,
                        'criteria_name' => $criteriaData['name'],
                        'default_description' => null, // Optional for admin
                    ]);
                }
            }
        });

        return redirect()->route('admin.subjects.index')->with('success', 'Subject, Kategori, dan Kriteria berhasil disimpan!');
    }

    public function edit($id)
    {
        $subject = Subject::with(['rubrics.teacher', 'rubrics.criteria'])->findOrFail($id);
        $terms = Term::all();
        $teachers = Teacher::all();
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
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject berhasil dihapus!');
    }
}
