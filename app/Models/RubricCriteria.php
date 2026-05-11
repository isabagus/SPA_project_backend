<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RubricCriteria extends Model
{
    protected $table = 'rubric_criteria';
    protected $primaryKey = 'criteria_id';

    protected $fillable = [
        'rubric_id',
        'criteria_name',
        'default_description',
    ];

    /**
     * Relasi ke Parent (RubricCategory)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(RubricCategory::class, 'rubric_id', 'rubric_id');
    }

    /**
     * Relasi ke Nilai (ReportDetail)
     */
    public function reportDetails(): HasMany
    {
        return $this->hasMany(ReportDetail::class, 'criteria_id', 'criteria_id');
    }
}
