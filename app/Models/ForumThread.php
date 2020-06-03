<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ForumThread extends Model
{
    use Sluggable;

    protected $table = "forum_threads";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "forum_id",
        "user_id",
        "slug",
        "title",
        "description",
        "message",
        "sticky",
        "closed",
        "order",
    ];
    protected $casts = [
        "sticky" => "boolean",
        "closed" => "boolean",
    ];

    //
    // Slug configuration
    //

    public function sluggable()
    {
        return ["slug" => ["source" => "title"]];
    }

    //
    // Relationships
    //
    
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(ForumThreadPost::class);
    }
}