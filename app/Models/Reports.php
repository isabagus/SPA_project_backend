<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $table = 'reports';
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'student_id',
        'subject_id',
        'level_class',
        'academic_year',
        'average_value',
        'attendance',
        'mentor_note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'year_academy', 'year_academy');
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class, 'report_id', 'report_id');
    }
}
