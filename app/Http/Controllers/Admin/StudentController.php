<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Mentor;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('layouts.students.index', compact('students'));
    }

    public function detail()
    {
        return view('layouts.students.detail');
    }

    public function create()
    {
        $academic_years = AcademicYear::all();
        $mentors = Mentor::all();
        return view('layouts.students.create', compact('academic_years', 'mentors'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'academic_year' => 'required|string|exists:academic_years,academic_year',
            'mentor_id'     => 'required|exists:mentors,mentor_id',
            'name_student'  => 'required|string|max:100',
            'gender'        => 'required|string|max:15',
            'address'       => 'required|string|max:255',
            'phone_number'  => 'required|string|max:15',
        ]);

        try {
            Student::create([
                'academic_year' => $request->academic_year,
                'mentor_id'     => $request->mentor_id,
                'name_student'  => $request->name_student,
                'gender'        => $request->gender,
                'address'       => $request->address,
                'phone_number'  => $request->phone_number,
            ]);

            return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $data = Student::findOrFail($id);
        $academic_years = AcademicYear::all();
        $mentors = Mentor::all();
        return view('layouts.students.edit', compact('data', 'academic_years', 'mentors'));
    }
    
    public function update(Request $request, $id) 
    {
        $request->validate([
            'academic_year' => 'required|date|exists:academic_years,academic_year',
            'mentor_id'     => 'required|exists:mentors,mentor_id',
            'name_student'  => 'required|string|max:100',
            'gender'        => 'required|string|max:15',
            'address'       => 'required|string|max:255',
            'phone_number'  => 'required|string|max:15',
        ]);

        try {
            $student = Student::findOrFail($id);
            
            $student->update([
                'academic_year' => $request->academic_year,
                'mentor_id'     => $request->mentor_id,
                'name_student'  => $request->name_student,
                'gender'        => $request->gender,
                'address'       => $request->address,
                'phone_number'  => $request->phone_number,
            ]);

            return redirect()->route('admin.students.index')->with('success', 'Student data successfully updated!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }
    public function destroy($id) {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student Data successfully delete');
    }
}
