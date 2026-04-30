<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Term extends Model
{
    use HasFactory;

    protected $primaryKey = 'term_id';

    protected $fillable = [
        'academic_year',
        'mentor_id',
        'name_student',
        'nis',
        'gender',
        'address',
        'photo',
        'email',
        'created_at',
        'updated_at',
    ];
}
