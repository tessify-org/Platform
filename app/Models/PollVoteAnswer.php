<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PollVoteAnswer extends Model
{
    use HasTranslations;

    protected $table = "poll_vote_answers";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "poll_vote_id",
        "poll_question_id",
        "poll_question_answer_id",
    ];

    //
    // Relationships
    //

    public function vote()
    {
        return $this->belongsTo(PollVote::class);
    }

    public function question()
    {
        return $this->belongsTo(PollQuestion::class);
    }

    public function answer()
    {
        return $this->belongsTo(PollQuestionAnswer::class);
    }
}