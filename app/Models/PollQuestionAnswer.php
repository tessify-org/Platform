<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PollQuestionAnswer extends Model
{
    use HasTranslations;

    protected $table = "poll_question_answers";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "poll_question_id",
        "value",
    ];
    public $translatable = [
        "value",
    ];

    //
    // Relationships
    //

    public function question()
    {
        return $this->belongsTo(PollQuestion::class);
    }
}