<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeTiming extends Model
{
    use HasFactory;

    protected $table = 'officetiming';

    protected $fillable = [
        'timing_start',
        'timing_off',
        'break',
        'totalworkinghours'
    ];

    // Remove employee relationship if it's not needed
    // This is COMPANY office timing, not per employee
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'employee_id');
    // }
}
