<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelClass extends Model
{
    protected $table = 'classes'; // Nama tabel di database Anda
    
    protected $primaryKey = 'class_id';
    public $incrementing = true;
    public $timestamps = false;
    protected $keyType = 'int';

    protected $fillable = [
        'class_id',
        'level_name',
        'section_name',
        'level_class', // Keep for backward compat/display
        'mentor_id'
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'class_id', 'class_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id', 'mentor_id');
    }
}
