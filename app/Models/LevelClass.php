<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelClass extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'level_class';
    public $incrementing = false; // Karena 'Year 1' adalah string, bukan auto-increment
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = [
        'level_class',
        'mentor_id',
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id', 'mentor_id');
    }
}
