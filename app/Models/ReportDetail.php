<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportDetail extends Model
{
    protected $table = 'details_report';

    protected $fillable = [
        'report_id',
        'subject_id',
        'score',
        'description_subject',
    ];

       public function report()
    {
        return $this->belongsTo(Reports::class, 'report_id', 'report_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id', 'mentor_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'id_subject', 'subject_id');
    }
}
