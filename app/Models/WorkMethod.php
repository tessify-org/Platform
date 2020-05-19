<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WorkMethod extends Model
{
    use HasTranslations;

    protected $table = "work_methods";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "name",
        "label",
        "description",
        "external_url",
    ];
    public $translatable = [
        "name",
        "label",
        "description",
    ];

    //
    // Relationships
    //

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}