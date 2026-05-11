<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\PaginationServiceProvider;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Mentor;
use App\Models\LevelClass;
use App\Models\Religion;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $students = Student::latest()
            ->when($search, function ($query, $search) {
                return $query->where('name_student', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhere('level_class', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

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
        $level_classes = LevelClass::all();
        $religions = Religion::all();
        return view('layouts.students.create', compact('academic_years', 'mentors', 'level_classes', 'religions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year.*' => 'required|string|exists:academic_years,academic_year',
            'level_class.*'   => 'required|string|exists:classes,level_class',
            'religion_name.*' => 'required|string|exists:religions,religion_name',
            'mentor_id.*'     => 'required|exists:mentors,mentor_id',
            'name_student.*'  => 'required|string|max:100',
            'gender.*'        => 'required|string|max:15',
            'address.*'       => 'required|string|max:255',
            'phone_number.*'  => 'required|string|max:15',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->name_student as $index => $name) {
                Student::create([
                    'academic_year' => $request->academic_year[$index],
                    'level_class'   => $request->level_class[$index],
                    'religion_name' => $request->religion_name[$index],
                    'mentor_id'     => $request->mentor_id[$index],
                    'name_student'  => $name,
                    'gender'        => $request->gender[$index],
                    'address'       => $request->address[$index],
                    'phone_number'  => $request->phone_number[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $data = Student::findOrFail($id);
        $academic_years = AcademicYear::all();
        $mentors = Mentor::all();
        $level_classes = LevelClass::all();
        $religions = Religion::all();
        return view('layouts.students.edit', compact('data', 'academic_years', 'mentors', 'level_classes', 'religions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'academic_year' => 'required|string|exists:academic_years,academic_year',
            'level_class'   => 'required|string|exists:classes,level_class',
            'religion_name' => 'required|string|exists:religions,religion_name',
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
                'level_class'   => $request->level_class,
                'religion_name' => $request->religion_name,
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
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student Data successfully delete');
    }
}
