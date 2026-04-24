<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function isAllowed($permission)
    {
        $allowedLists = $this->getAllPermissions()->pluck('name');

        if ($this->hasRole('admin')) {
            return 1;
        } else {
            foreach ($allowedLists as $allowedPermission) {
                if ($allowedPermission == $permission) {
                    return 1;
                }
            }
        }
    }
    // In User.php model
    public function employee()
    {
        return $this->hasOne(Employee::class, 'personal_email', 'email');
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the tasks for the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the routines for the user.
     */
    public function routines()
    {
        return $this->hasMany(Routine::class);
    }

    /**
     * Get the notes for the user.
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the calendar events for the user.
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function projectMembers()
    {
        return $this->belongsToMany(Project::class, 'project_teams', 'user_id', 'project_id');
    }

    // public function roles()
    // {
    //     return $this->belongsToMany(role::class, 'model_has_roles', 'model_id', 'role_id');
    // }


}
