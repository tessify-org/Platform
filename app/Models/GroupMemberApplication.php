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
        "user_id",
        "group_id",
        "motivation",
        "processed",
        "accepted",
    ];
    protected $casts = [
        "processed" => "boolean",
        "accepted" => "boolean",
    ];

    //
    // Relationships
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}