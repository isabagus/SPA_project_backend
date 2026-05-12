<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $incrementing = true;
    protected $fillable = [
        'academic_year',
        'class_id',
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
        return $this->belongsTo(LevelClass::class, 'class_id', 'class_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_name', 'religion_name');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id', 'mentor_id');
    }

    public function parent()
    {
        return $this->hasOne(Parents::class, 'student_id', 'student_id');
    }

    public function reports()
    {
        return $this->hasMany(Reports::class, 'student_id', 'student_id');
    }
}
