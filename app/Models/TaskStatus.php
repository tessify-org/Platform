<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TaskStatus extends Model
{
    use HasTranslations;

    protected $table = "task_statuses";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
        "label",
    ];
    public $translatable = [
        "label",
    ];

    //
    // Relationships
    //

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}