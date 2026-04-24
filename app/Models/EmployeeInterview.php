<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_name',
        'cv',
        'current_salary',
        'expected_salary',
        'date_of_joining',
        'interview_date',
        'interview_status',
        'interview_remarks',
        'applied_for_job',
    ];
}

