<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PollStatus extends Model
{
    use HasTranslations;

    protected $table = "poll_statuses";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
        "description",
    ];
    public $translatable = [
        "name",
        "description",
    ];

    //
    // Relationships
    //

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
}