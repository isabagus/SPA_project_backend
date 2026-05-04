<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class Parents extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'parent_id';
    protected $fillable = [
        'user_id',
        'student_id',
        'name_parent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

}
