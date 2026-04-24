<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['task_id', 'user_id', 'attachment', 'comment', 'parent_comment_id', 'is_edited'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function previous_versions()
    {
        return $this->hasMany(\App\Models\CommentHistory::class, 'comment_id');
    }
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    protected $casts = [
    'attachment' => 'array',
];
}
