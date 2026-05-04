<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';
    protected $fillable = ['category_subject', 'term', 'name_subject'];
    public $timestamps = false; // Karena di migrasi tidak ada timestamps

    public function category()
    {
        return $this->belongsTo(CategorySubject::class, 'category_subject', 'category_subject');
    }

    public function term_data()
    {
        return $this->belongsTo(Term::class, 'term', 'term');
    }
}
