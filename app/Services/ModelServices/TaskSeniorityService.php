<?php

namespace App\Services\ModelServices;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskSeniority;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class TaskSeniorityService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\TaskSeniority";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function findForTask(Task $task)
    {
        foreach ($this->getAll() as $seniority)
        {
            if ($seniority->id == $task->task_seniority_id)
            {
                return $seniority;
            }
        }

        return false;
    }
}