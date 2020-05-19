<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SkillTask extends Pivot
{
    use HasTranslations;

    protected $table = "skill_task";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "skill_id",
        "task_id",
        "required_mastery",
        "description",
    ];
    public $translatable = [
        "description",
    ];

    //
    // Relationships
    //

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}