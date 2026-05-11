<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportDetail extends Model
{
    protected $table = 'details_report';

    protected $fillable = [
        'report_id',
        'rubric_id',
        'criteria_id',
        'score',
        'description_subject',
    ];

    /**
     * Relasi ke Raport (Parent)
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Reports::class, 'report_id', 'report_id');
    }

    /**
     * Relasi ke Kategori Rubrik
     */
    public function rubric(): BelongsTo
    {
        return $this->belongsTo(RubricCategory::class, 'rubric_id', 'rubric_id');
    }

    /**
     * Relasi ke Sub-Kriteria Spesifik (New)
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(RubricCriteria::class, 'criteria_id', 'criteria_id');
    }
}
