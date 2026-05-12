<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';
    
    // Tambahkan class_id ke dalam fillable
    protected $fillable = [
        'category_subject',
        'term',
        'class_id',
        'level_class',
        'teacher_id',
        'report_group_key'
    ];

    /**
     * Relasi ke Guru yang mengampu
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }

    /**
     * Relasi ke Laporan
     */
    public function reports()
    {
        return $this->hasMany(Reports::class, 'subject_id', 'subject_id');
    }

    public function class()
    {
        return $this->belongsTo(LevelClass::class, 'class_id', 'class_id');
    }

    public function rubrics()
    {
        return $this->hasMany(RubricCategory::class, 'subject_id', 'subject_id');
    }
}
