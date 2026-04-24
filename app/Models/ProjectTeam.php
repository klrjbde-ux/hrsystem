<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTeam extends Model
{
    protected $table = 'project_teams';
    protected $fillable = ['project_id', 'user_id'];
}