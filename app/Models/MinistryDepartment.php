<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class MinistryDepartment extends Model
{
    use Sluggable;
    use HasTranslations;

    protected $table = "ministry_departments";
    protected $guarded = [
        "id", "created_at", "updated_at",
    ];
    protected $fillable = [
        "slug",
        "name",
        "description",
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
        return ["slug" => ["source" => "name_nl"]];
    }

    //
    // Relationships
    //

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    //
    // Accessors
    //

    public function getNameNlAttribute()
    {
        return $this->name["nl"];
    }
}