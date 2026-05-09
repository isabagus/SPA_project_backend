<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RubricCategory extends Model
{
    protected $table = 'rubric_categories';
    protected $primaryKey = 'rubric_id';
    
    protected $fillable = [
        'subject_id', // Tambahkan ini
        'teacher_id',
        'term',
        'rubric_name'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }
}
