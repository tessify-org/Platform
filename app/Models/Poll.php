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
        "user_id",
        "poll_status_id",
        "slug",
        "title",
        "description",
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

    //
    // Accessors
    //

    public function getResultsAttribute($value)
    {
        return json_decode(unserialize($value));
    }

    //
    // Mutators
    //

    public function setResultsAttribute($value)
    {
        $this->attributes["results"] = serialize(json_encode($value));
    }
}