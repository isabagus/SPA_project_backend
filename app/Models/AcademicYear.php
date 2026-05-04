<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $table = 'academic_years';
    protected $primaryKey = 'academic_year';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'academic_year',
    ];

    public function students()
    {
       return  $this->hasMany(Student::class, 'academic_year', 'academic_year');
    }

    public function reports()
    {
       return  $this->hasMany(Reports::class, 'academic_year', 'academic_year');
    }
}
