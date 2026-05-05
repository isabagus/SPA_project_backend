<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\CategorySubject;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['category', 'term_data'])->get();
        return view('layouts.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $categories = CategorySubject::all();
        $terms = Term::all();
        return view('layouts.subjects.create', compact('categories', 'terms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_subject' => 'required|exists:categories_subject,category_subject',
            'term'             => 'required|exists:terms,term',
            'name_subject.*'   => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->name_subject as $name) {
                Subject::create([
                    'category_subject' => $request->category_subject,
                    'term'             => $request->term,
                    'name_subject'     => $name,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.subjects.index')->with('success', 'Subjects successfully saved!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error saving subjects: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $subject = Subject::findOrFail($id);
        return view('layouts.subjects.detail', compact('subject'));
    }
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $categories = CategorySubject::all();
        $terms = Term::all();
        return view('layouts.subjects.edit', compact('subject', 'categories', 'terms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_subject' => 'required|exists:categories_subject,category_subject',
            'term'             => 'required|exists:terms,term',
            'name_subject'     => 'required|string|max:255',
        ]);

        try {
            $subject = Subject::findOrFail($id);
            $subject->update([
                'category_subject' => $request->category_subject,
                'term'             => $request->term,
                'name_subject'     => $request->name_subject,
            ]);

            return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating subject: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully!');
    }
}
