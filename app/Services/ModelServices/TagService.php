<?php

namespace App\Services\ModelServices;

use DB;
use Auth;
use App\Models\User;
use App\Models\Tag;
use App\Models\Task;
use App\Models\Project;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class TagService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;

    private $taggables;

    public function __construct()
    {
        $this->model = "App\Models\Tag";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function getTaggables()
    {
        if (is_null($this->taggables))
        {
            $this->taggables = DB::table("taggables")->get();
        }

        return $this->taggables;
    }
    
    public function getAllForTask(Task $task)
    {
        $out = [];

        foreach ($this->getTaggables() as $taggable)
        {
            if ($taggable->taggable_type == Task::class && $taggable->taggable_id == $task->id)
            {
                $out[] = $this->find($taggable->tag_id);
            }
        }

        return $out;
    }

    public function getAllForProject(Project $project)
    {
        $out = [];

        foreach ($this->getTaggables() as $taggable)
        {
            if ($taggable->taggable_type == Project::class && $taggable->taggable_id == $project->id)
            {
                $out[] = $this->find($taggable->tag_id);
            }
        }

        return $out;
    }

    public function findOrCreateByName($name)
    {
        foreach ($this->getAll() as $tag)
        {
            if ($tag->name == $name)
            {
                return $tag;
            }
        }

        return Tag::create(["name" => $name]);
    }
    
    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $tag)
        {
            if ($tag->slug == $slug)
            {
                return $tag;
            }
        }

        return false;
    }
}