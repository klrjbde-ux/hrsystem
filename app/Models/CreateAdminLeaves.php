<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreateAdminLeaves extends Model
{
    use HasFactory;
    protected $table = 'create_admin_leaves';
    protected $fillable = [
        'employee_id','status','leave_type','start_date', 'end_date',  'no_of_leaves',];
    

public function totalleaves()
{
    return $this->belongsTo(TotalLeaves::class, 'leave_type');
}
public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}
}
