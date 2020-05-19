<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProjectStatus extends Model
{
    use HasTranslations;

    protected $table = "project_statuses";
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

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}