<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $fillable = [
        'academic_year',
        'level_class',
        'religion_name',
        'mentor_id',
        'name_student',
        'gender',
        'address',
        'phone_number'
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'year_academy', 'year_academy');
    }

    public function levelClass()
    {
        return $this->belongsTo(LevelClass::class, 'level_class', 'level_class');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_name', 'religion_name');
    }

    public function parents()
    {
        return $this->hasMany(Parents::class, 'student_id', 'student_id');
    }

    public function reports()
    {
        return $this->hasMany(Reports::class, 'student_id', 'student_id');
    }
}
