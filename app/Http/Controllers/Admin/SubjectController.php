<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\RubricCategory;
use App\Models\Term;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{

    public function index()
    {
        // Urutkan berdasarkan Tahun (level_class) dan kemudian berdasarkan Term
        $subjects = Subject::with('class')
            ->orderBy('level_class', 'asc')
            ->orderBy('term', 'asc')
            ->get();
            
        return view('layouts.subjects.index', compact('subjects'));
    }

    public function show($id)
    {
        // Load rubrik beserta gurunya
        $subject = Subject::with(['rubrics.teacher', 'class'])->findOrFail($id);
        return view('layouts.subjects.detail', compact('subject'));
    }

    public function create()
    {
        $terms = Term::all();
        $teachers = Teacher::all();
        $subjects = Subject::select('category_subject')->distinct()->get();
        
        return view('layouts.subjects.create', compact('terms', 'teachers', 'subjects'));
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
        ]);

        DB::transaction(function () use ($request) {
            $subject = Subject::create([
                'category_subject' => $request->category_subject,
                'term'             => $request->term,
                'level_class'      => $request->class_id,
            ]);

            foreach ($request->rubrics as $rubricData) {
                RubricCategory::create([
                    'subject_id'  => $subject->subject_id,
                    'rubric_name' => $rubricData['name'],
                    'teacher_id'  => $rubricData['teacher_id'],
                    'term'        => $request->term,
                ]);
            }
        });

        return redirect()->route('admin.subjects.index')->with('success', 'Subject dan Rubrik berhasil disimpan!');
    }

    public function edit($id)
    {
        $subject = Subject::with(['rubrics.teacher'])->findOrFail($id);
        $terms = Term::all();
        $teachers = Teacher::all();
        $subjectCategories = Subject::select('category_subject')->distinct()->get();
        
        return view('layouts.subjects.edit', compact('subject', 'terms', 'teachers', 'subjectCategories'));
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
        ]);

        DB::transaction(function () use ($request, $id) {
            $subject = Subject::findOrFail($id);
            $subject->update([
                'category_subject' => $request->category_subject,
                'term'             => $request->term,
                'level_class'      => $request->level_class,
            ]);

            $existingIds = collect($request->rubrics)->pluck('rubric_id')->filter()->toArray();

            $subject->rubrics()->whereNotIn('rubric_id', $existingIds)->delete();

 
            foreach ($request->rubrics as $data) {
                if (isset($data['rubric_id'])) {
                    RubricCategory::where('rubric_id', $data['rubric_id'])->update([
                        'rubric_name' => $data['name'],
                        'teacher_id'  => $data['teacher_id'],
                        'term'        => $request->term,
                    ]);
                } else {

                    RubricCategory::create([
                        'subject_id'  => $subject->subject_id,
                        'rubric_name' => $data['name'],
                        'teacher_id'  => $data['teacher_id'],
                        'term'        => $request->term,
                    ]);
                }
            }
        });

        return redirect()->route('admin.subjects.index')->with('success', 'Subject dan Rubrik berhasil diupdate!');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject berhasil dihapus!');
    }
}
