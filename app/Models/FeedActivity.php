<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedActivity extends Model
{
    protected $table = "feed_activities";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "user_id",
        "actor_id",
        "target_type",
        "target_id",
        "name",
        "data",
    ];

    //
    // Relationships
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, "actor_id", "id");
    }
}