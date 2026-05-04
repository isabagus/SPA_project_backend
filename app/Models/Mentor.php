<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Mentor extends Model
{
    protected $table = 'mentors';
    protected $primaryKey = 'mentor_id';

    protected $fillable = [
        'user_id',
        'name_mentor',
        'nip',
        'phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function classes()
    {
        return $this->hasMany(LevelClass::class, 'mentor_id', 'mentor_id');
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class, 'mentor_id', 'mentor_id');
    }
}
