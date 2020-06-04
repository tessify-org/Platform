<?php

namespace App\Models;

use Uuid;
use Illuminate\Database\Eloquent\Model;

class ForumThreadPost extends Model
{
    protected $table = "forum_thread_posts";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "forum_thread_id",
        "forum_thread_post_id",
        "user_id",
        "uuid",
        "message",
    ];

    //
    // Uuid
    //
    
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }
    
    //
    // Relationships
    //
    
    public function forumThread()
    {
        return $this->belongsTo(ForumThread::class);
    }

    public function parentPost()
    {
        return $this->belongsTo(ForumThreadPost::class, "forum_thread_post_id", "id");
    }

    public function childPosts()
    {
        return $this->hasMany(ForumThreadPost::class, "id", "forum_thread_post_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}