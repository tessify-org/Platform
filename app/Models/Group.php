<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;
use Overtrue\LaravelSubscribe\Traits\Subscribable;

class Group extends Model
{
    use Sluggable;
    use Searchable;
    use Subscribable;
    use HasTranslations;

    protected $table = "groups";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "founder_id",
        "slug",
        "name",
        "slogan",
        "description",
        "header_image_url",
        "avatar_image_url",
    ];
    public $translatable = [
        "slogan",
        "description",
    ];

    //
    // Slug configuration
    //

    public function sluggable()
    {
        return ["slug" => ["source" => "name"]];
    }

    //
    // Relationships
    //

    public function founder()
    {
        return $this->belongsTo(User::class, "founder_id", "id");
    }

    public function roles()
    {
        return $this->hasMany(GroupRole::class);
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function applications()
    {
        return $this->hasMany(GroupMemberApplication::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, "taggable");
    }

    public function polls()
    {
        return $this->morphToMany(Poll::class, "pollable");
    }

    public function forum()
    {
        return $this->morphOne(Forum::class, "forumable");
    }
}