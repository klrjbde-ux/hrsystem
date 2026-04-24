<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'priority', // ✅ ADD THIS
        'budget',
        'site_link',
        'project_file',
    ];



    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    // public function getStatusAttribute()
    // {
    //     $totalTasks = $this->tasks()->count();

    //     if ($totalTasks === 0) {
    //         return 'to_do';
    //     }

    //     $completedTasks = $this->tasks()->where('status', 'completed')->count();
    //     $inProgressTasks = $this->tasks()->where('status', 'in_progress')->count();

    //     if ($completedTasks === $totalTasks) {
    //         return 'completed';
    //     }

    //     if ($inProgressTasks > 0) {
    //         return 'in_progress';
    //     }

    //     return 'to_do';
    // }

    public function teamProjects()
    {
        return $this->belongsToMany(ProjectTeam::class, 'project_teams', 'project_id', 'user_id');
    }

    public function updateStatusFromTasks()
    {
        $totalTasks = $this->tasks()->count();

        if ($totalTasks === 0) {
            $this->status = 'not_started';
        } else {
            $completedTasks = $this->tasks()->where('status', 'completed')->count();

            $this->status = ($completedTasks === $totalTasks)
                ? 'completed'
                : 'in_progress';
        }

        $this->save();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_teams', 'project_id', 'user_id');
    }
}
