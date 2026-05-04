<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelClass extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'level_class';
    protected $fillable = [
        'level_class',
    ];
}
