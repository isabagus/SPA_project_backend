<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSubject extends Model
{
    protected $table = 'details_subject';

    protected $fillable = [
        'subject_id',
        'teacher_id',
    ];
}
