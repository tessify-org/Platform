<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Cviebrock\EloquentSluggable\Sluggable;
use Overtrue\LaravelSubscribe\Traits\Subscribable;

class Project extends Model
{
    use Sluggable;
    use Searchable;
    use Subscribable;
    use HasTranslations;

    protected $table = "projects";
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $fillable = [
        "author_id",
        "project_category_id",
        "project_status_id",
        "project_phase_id",
        "ministry_id",
        "organization_id",
        "organization_department_id",
        "work_method_id",
        "slug",
        "title",
        "slogan",
        "description",
        "header_image_url",
        "starts_at",
        "ends_at",
        "has_tasks",
        "has_deadline",
        "budget",
        "project_code",
    ];
    protected $dates = [
        "starts_at", 
        "ends_at",
    ];
    protected $casts = [
        "has_tasks" => "boolean",
        "has_deadline" => "boolean",
    ];
    public $translatable = [
        "slogan",
        "description",
    ];
    
    //
    // Slug configuration
    //

    public function sluggable()
    {
        return ["slug" => ["source" => "title"]];
    }

    //
    // Relationships
    //

    public function author()
    {
        return $this->belongsTo(User::class, "author_id", "id");
    }

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function organizationDepartment()
    {
        return $this->belongsTo(OrganizationDepartment::class);
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, "project_category_id", "id");
    }

    public function status()
    {
        return $this->belongsTo(ProjectStatus::class, "project_status_id", "id");
    }

    public function phase()
    {
        return $this->belongsTo(ProjectPhase::class);
    }

    public function workMethod()
    {
        return $this->belongsTo(WorkMethod::class);
    }

    public function resources()
    {
        return $this->hasMany(ProjectResource::class);
    }

    public function teamRoles()
    {
        return $this->hasMany(TeamRole::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }
    
    public function teamMemberApplications()
    {
        return $this->hasMany(TeamMemberApplication::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, "commentable");
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, "taggable");
    }

    public function reviewRequests()
    {
        return $this->morphMany(ReviewRequest::class, "reviewrequestable");
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, "reviewable");
    }
}