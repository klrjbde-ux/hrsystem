<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentHistory extends Model
{
    protected $fillable = ['comment_id', 'old_comment'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
