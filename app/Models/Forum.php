<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Forum extends Model
{
    use Sluggable;
    
    protected $table = "forums";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "parent_forum_id",
        "forumable_type",
        "forumable_id",
        "slug",
        "title",
        "description",
        "editable",
        "deletable",
    ];
    protected $casts = [
        "editable" => "boolean",
        "deletable" => "boolean",
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

    public function forumable()
    {
        return $this->morpthTo();
    }
    
    public function parentForum()
    {
        return $this->belongsTo(Forum::class, "parent_forum_id", "id");
    }

    public function subforums()
    {
        return $this->hasMany(Forum::class, "parent_forum_id", "id");
    }

    public function threads()
    {
        return $this->hasMany(ForumThread::class);
    }

    //
    // Helper methods
    //

    public function isGeneralForum()
    {
        return $this->parent_forum_id == 0 && $this->forumable_type == "" && $this->forumable_id == 0;
    }

    public function hasParent()
    {
        return $this->parent_forum_id > 0;
    }
}