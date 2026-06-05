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
            'academic_year.*' => 'required|string|max:20',
            'level_class.*'   => 'required|string|exists:classes,level_class',
            'religion_name.*' => 'required|string|exists:religions,religion_name',
            'nis.*'           => 'required|string|max:20|unique:students,nis',
            'name_student.*'  => 'required|string|max:100',
            'gender.*'        => 'required|string|max:15',
            'address.*'       => 'required|string|max:255',
            'phone_number.*'  => 'required|string|max:15',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->name_student as $index => $name) {
                $class = LevelClass::where('level_class', $request->level_class[$index])->firstOrFail();

                // Validasi: Pastikan kelas sudah memiliki mentor (karena kolom mentor_id di DB adalah NOT NULL)
                if (is_null($class->mentor_id)) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Gagal: Kelas {$class->level_class} belum memiliki Mentor. Silakan assign Mentor ke kelas tersebut melalui menu Kelas terlebih dahulu.")->withInput();
                }

                Student::create([
                    'academic_year' => $request->academic_year[$index],
                    'class_id'      => $class->class_id,
                    'level_class'   => $class->level_class,
                    'religion_name' => $request->religion_name[$index],
                    'mentor_id'     => $class->mentor_id,
                    'nis'           => $request->nis[$index],
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
            'academic_year' => 'required|string|max:20',
            'level_class'   => 'required|string|exists:classes,level_class',
            'religion_name' => 'required|string|exists:religions,religion_name',
            'nis'           => 'required|string|max:20|unique:students,nis,' . $id . ',student_id',
            'name_student'  => 'required|string|max:100',
            'gender'        => 'required|string|max:15',
            'address'       => 'required|string|max:255',
            'phone_number'  => 'required|string|max:15',
        ]);

        try {
            $student = Student::findOrFail($id);
            $class = LevelClass::where('level_class', $request->level_class)->firstOrFail();

            // Validasi: Pastikan kelas sudah memiliki mentor
            if (is_null($class->mentor_id)) {
                return redirect()->back()->with('error', "Gagal: Kelas {$class->level_class} belum memiliki Mentor.")->withInput();
            }

            $student->update([
                'academic_year' => $request->academic_year,
                'class_id'      => $class->class_id,
                'level_class'   => $class->level_class,
                'religion_name' => $request->religion_name,
                'mentor_id'     => $class->mentor_id,
                'nis'           => $request->nis,
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
