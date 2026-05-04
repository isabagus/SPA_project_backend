<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'terms';
    protected $primaryKey = 'term';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['term'];
    public $timestamps = false;

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'term', 'term');
    }
}
