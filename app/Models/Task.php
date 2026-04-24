<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'status_changed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'to_do':
                return 'primary';
            case 'in_progress':
                return 'warning';
            case 'qa':
                return 'info';
            case 'completed':
                return 'success';
            default:
                return 'secondary';
        }
    }

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    public function histories()
{
    return $this->hasMany(TaskHistory::class);
}
}
