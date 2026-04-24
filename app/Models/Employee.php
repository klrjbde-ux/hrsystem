<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = 'employees';

    protected $fillable = [
        'firstname',
        'lastname',
        'personal_email',
        'user_name',
        'gender',
        'dob',
        'emp_type',
        'emp_status',
        'designation',
        'department',
        'branch',
        'joining_date',
        'manager',
        'team',
        'gross_salary',
        'contact_no'
    ];
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'emp_id');
    }
    public function empType()
    {
        return $this->belongsTo(EmpType::class, 'emp_type', 'id');
    }

    public function empStatus()
    {
        return $this->belongsTo(EmpStatus::class, 'emp_status', 'id');
    }


    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department');
    }

    public function employeeContactRelation()
    {
        return $this->belongsTo(EmployeeContactRelation::class, 'relation');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'personal_email', 'email');
    // }
}
