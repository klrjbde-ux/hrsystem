<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'performance_review_id', 'reviewer_id',
        'rating', 'comments', 'recommendations',
        'status', 'review_date',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'review_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function performanceReview(): BelongsTo
    {
        return $this->belongsTo(PerformanceReview::class, 'performance_review_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }
}
