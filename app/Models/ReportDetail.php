<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportDetail extends Model
{
    protected $table = 'details_report';

    protected $fillable = [
        'report_id',
        'rubric_id',
        'score',
        'description_subject',
    ];

       public function report()
    {
        return $this->belongsTo(Reports::class, 'report_id', 'report_id');
    }

    public function rubric()
    {
        return $this->belongsTo(RubricCategory::class, 'rubric_id', 'rubric_id');
    }
}
