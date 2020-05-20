<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class Group extends Model
{
    use Sluggable;
    use Searchable;
    use HasTranslations;

    protected $table = "groups";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "founder_id",
        "slug",
        "title",
        "description",
        "header_image_url",
        "avatar_image_url",
    ];
    public $translatable = [
        "description"
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
}