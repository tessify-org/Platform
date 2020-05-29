<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    protected $table = "poll_votes";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "user_id",
        "poll_id",
        "answers",
    ];

    //
    // Relationships
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    //
    // Accessors
    //

    public function getAnswersAttribute($value)
    {
        return json_decode(unserialize($value));
    }

    //
    // Mutators
    //

    public function setAnswersAttribute($value)
    {
        $this->attributes["answers"] = serialize(json_encode($value));
    }
}