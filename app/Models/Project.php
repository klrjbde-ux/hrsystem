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
            $qaPassedTasks = $this->tasks()->where('status', 'qa_passed')->count();

            $this->status = ($qaPassedTasks === $totalTasks)
                ? 'completed'
                : 'in_progress';
        }

        $this->save();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_teams', 'project_id', 'user_id');
    }
public function getDeadlineTextAttribute(): string
{
    if (!$this->end_date) {
        return 'No deadline set';
    }

    $today = \Carbon\Carbon::today(); // ignore time
    $end = $this->end_date->copy()->startOfDay();

    // ❌ Deadline passed
    if ($end->lt($today)) {
        return 'Deadline Passed';
    }

    // ✅ Calculate days difference (inclusive logic)
    $days = $today->diffInDays($end) + 1;

    return $days . ' day' . ($days > 1 ? 's' : '') . ' remaining';
}
}
