<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'name',
        'cv',
        'current_salary',
        'expected_salary',
        'joining_date',
        'interview_date',
        'status',
        'remarks',
        'applied_job',
    ];
}
