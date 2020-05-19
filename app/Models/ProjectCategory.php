<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProjectCategory extends Model
{
    use HasTranslations;

    protected $table = "project_categories";
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