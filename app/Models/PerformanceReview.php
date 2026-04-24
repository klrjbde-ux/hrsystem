<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'reviewer_id', 'period_start', 'period_end',
        'overall_rating', 'strengths', 'improvements', 'comments', 'status',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'overall_rating' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }

    public function appraisals(): HasMany
    {
        return $this->hasMany(Appraisal::class, 'performance_review_id');
    }
}
