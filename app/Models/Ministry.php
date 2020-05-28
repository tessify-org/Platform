<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;
use Overtrue\LaravelSubscribe\Traits\Subscribable;

class Ministry extends Model
{
    use Sluggable;
    use Searchable;
    use Subscribable;
    use HasTranslations;

    protected $table = "ministries";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "slug",
        "name",
        "abbreviation",
        "description",
        "website_url",
        "logo_url",
    ];
    public $translatable = [
        "name",
        "abbreviation",
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

    public function ministryDepartments()
    {
        return $this->hasMany(MinistryDepartment::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
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