<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class Poll extends Model
{
    use Sluggable;
    use HasTranslations;

    protected $table = "polls";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "pollable_type",
        "pollable_id",
        "user_id",
        "poll_status_id",
        "slug",
        "title",
        "description",
        "header_image_url",
        "published",
        "public",
        "results",
        "num_votes",
    ];
    protected $casts = [
        "published" => "boolean",
        "public" => "boolean",
    ];
    public $translatable = [
        "description",
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(PollStatus::class, "poll_status_id", "id");
    }

    public function questions()
    {
        return $this->hasMany(PollQuestion::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function pollable()
    {
        return $this->morphTo();
    }

    //
    // Accessors
    //

    public function getResultsAttribute($value)
    {
        return (array) json_decode($value);
    }

    //
    // Mutators
    //

    public function setResultsAttribute($value)
    {
        $this->attributes["results"] = json_encode($value);
    }
}