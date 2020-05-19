<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TaskSeniority extends Model
{
    use HasTranslations;

    protected $table = "task_seniorities";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
        "label",
    ];
    public $translatable = [
        "name",
        "label",
    ];

    //
    // Relationships
    //

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}