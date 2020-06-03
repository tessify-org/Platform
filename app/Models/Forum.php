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
    
    public function parentForum()
    {
        return $this->belongsTo(Forum::class, "parent_forum_id", "id");
    }

    public function childForums()
    {
        return $this->hasMany(Forum::class, "id", "parent_forum_id");
    }

    public function forumable()
    {
        return $this->morpthTo();
    }
}