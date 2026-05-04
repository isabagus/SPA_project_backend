<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    protected $table = 'religions';

    protected $primaryKey = 'religion_name';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['religion_name'];

    public function students()
    {
        return $this->hasMany(Student::class, 'religion_name', 'religion_name');
    }
}
