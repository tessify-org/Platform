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
        "open_question",
        "allow_multiple_answers",
    ];
    protected $casts = [
        "open_question" => "boolean",
        "allow_multiple_answers" => "boolean",
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