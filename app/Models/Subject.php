<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'categories_subject';
    protected $primaryKey = 'category_subject';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'category_subject', 'category_subject');
    }
}
