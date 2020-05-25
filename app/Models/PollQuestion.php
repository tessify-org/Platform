<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PollQuestion extends Model
{
    use HasTranslations;

    protected $table = "poll_questions";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "poll_id",
        "question",
    ];
    public $translatable = [
        "question",
    ];

    //
    // Relationships
    //

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function answers()
    {
        return $this->hasMany(PollQuestionAnswer::class);
    }
}