<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Reports;
use App\Models\ReportDetail;
use App\Models\ParentStudent;
use App\Models\Mentor;
use App\Models\LevelClass;
use App\Models\Religion;
use App\Models\Teacher;
use App\Models\Subject;

class ReportController extends Controller
{
    // Tier 1: Daftar Tahun Ajaran & Kelas
    public function index() {
        $classes = Reports::select('academic_year', 'level_class', 'class_id')
            ->groupBy('academic_year', 'level_class', 'class_id')
            ->orderBy('academic_year', 'desc')
            ->get();
        return view('layouts.reports.index', compact('classes'));
    }

    // Tier 2: Daftar Siswa di Kelas & Tahun Ajaran tersebut
    public function listStudents($class_id, $academic_year) {
        // Decode academic_year because it might contain slashes or spaces
        $academic_year = urldecode($academic_year);
        
        $students = Reports::with('student')
            ->where('class_id', $class_id)
            ->where('academic_year', $academic_year)
            ->select('student_id', 'academic_year', 'class_id', 'level_class', 
                \DB::raw('AVG(average_value) as overall_avg'),
                \DB::raw('MAX(report_id) as report_id')
            )
            ->groupBy('student_id', 'academic_year', 'class_id', 'level_class')
            ->get();

        return view('layouts.reports.students', compact('students', 'academic_year'));
    }

    // Tier 3: Daftar Mata Pelajaran Siswa (Rincian Rapor)
    public function show($id) {
        $mainReport = Reports::with(['student'])->findOrFail($id);

        $allSubjects = Reports::with(['subject'])
            ->where('student_id', $mainReport->student_id)
            ->where('academic_year', $mainReport->academic_year)
            ->where('level_class', $mainReport->level_class)
            ->get();

        return view('layouts.reports.detail', compact('mainReport', 'allSubjects'));
    }

    // Tier 4: Detail Rubrik & Deskripsi per Mata Pelajaran
    public function subjectDetail($id) {
        // $term = Term::where('academic_year', $id)->first();
        $report = Reports::with(['student', 'subject', 'reportDetails.criteria'])->findOrFail($id);
        return view('layouts.reports.subject_detail', compact('report'));
    }
}
