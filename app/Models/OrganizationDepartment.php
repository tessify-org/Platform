<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class OrganizationDepartment extends Model
{
    use Sluggable;
    use HasTranslations;

    protected $table = "organization_departments";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "organization_id",
        "slug",
        "name",
        "description",
        "website_url",
        "header_image_url",
    ];
    public $translatable = [
        "name",
        "description",
    ];
    
    //
    // Slug configuration
    //

    public function sluggable()
    {
        return ["slug" => ["source" => "name"]];
    }

    //
    // Relationships
    //

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}