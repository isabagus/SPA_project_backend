<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelClass extends Model
{
    protected $table = 'classes'; // Nama tabel di database Anda
    
    protected $primaryKey = 'level_class';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'level_class',
        'mentor_id'
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'class_id', 'level_class');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id', 'mentor_id');
    }
}
