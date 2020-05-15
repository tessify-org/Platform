<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $table = "task_statuses";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
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