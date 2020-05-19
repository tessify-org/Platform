<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TaskCategory extends Model
{
    use HasTranslations;

    protected $table = "task_categories";
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

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}