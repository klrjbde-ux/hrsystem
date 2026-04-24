<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    protected $fillable = [
        'task_id', 'old_status', 'new_status', 'changed_by', 'assigned_to'
    ];

    public function changedBy() {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function assignedTo() {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
