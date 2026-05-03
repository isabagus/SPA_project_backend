<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'academic_year',
        'mentor_id',
        'name_student',
        'gender',
        'address',
        'phone_number'
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id', 'mentor_id');
    }
}