<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;

class TeamRole extends Model
{
    use Sluggable;
    use HasTranslations;
    
    protected $table = "team_roles";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "project_id",
        "slug",
        "name",
        "description",
        "positions",
    ];
    public $translatable = [
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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function teamMembers()
    {
        return $this->belongsToMany(TeamMember::class);
    }

    public function teamMemberApplications()
    {
        return $this->hasMany(TeamMemberApplication::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
}