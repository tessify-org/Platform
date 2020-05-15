<?php

namespace App\Services\ModelServices;

use Organizations;
use App\Models\Task;
use App\Models\Project;
use App\Models\Ministry;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Ministries\CreateMinistryRequest;
use App\Http\Requests\Ministries\UpdateMinistryRequest;
use App\Http\Requests\Api\Ministries\CreateMinistryRequest as ApiCreateRequest;
use App\Http\Requests\Api\Ministries\UpdateMinistryRequest as ApiUpdateRequest;

class MinistryService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Ministry";
    }
    
    public function preload($instance)
    {
        $instance->organizations = Organizations::findAllForMinistry($ministry);

        return $instance;
    }

    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $organization)
        {
            if ($organization->slug == $slug)
            {
                return $organization;
            }
        }

        return false;
    }

    public function findForProject(Project $project)
    {
        foreach ($this->getAll() as $ministry)
        {
            if ($ministry->id === $project->ministry_id)
            {
                return $ministry;
            }
        }

        return false;
    }

    public function findForTask(Task $task)
    {
        foreach ($this->getAll() as $ministry)
        {
            if ($ministry->id == $task->ministry_id)
            {
                return $ministry;
            }
        }

        return false;
    }
}