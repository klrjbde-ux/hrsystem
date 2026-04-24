<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStandupMeeting extends Model
{
    use HasFactory;

    protected $table = 'daily_standup_meetings';

    protected $fillable = [
        'date',
        'employee_id',
        'status',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
