<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorySubject extends Model
{
    protected $guarded = ['id'];    
    
    protected $table = 'categories_subject';
}
