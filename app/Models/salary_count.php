<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salary_count extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'gross_salary',
        'month',
        'total_leaves',
        'deduction_type',
        'payable_salary',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function deduction()
    {
        return $this->belongsTo(Deduction::class, 'deduction_type');
    }
}
