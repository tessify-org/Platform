<?php

namespace App\Services\ModelServices;

use App\Models\Project;
use App\Models\ProjectStatus;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class ProjectStatusService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\ProjectStatus";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function findForProject(Project $project)
    {
        foreach ($this->getAll() as $status)
        {
            if ($status->id == $project->project_status_id)
            {
                return $status;
            }
        }

        return false;
    }
}