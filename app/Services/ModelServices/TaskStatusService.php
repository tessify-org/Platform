<?php

namespace App\Services\ModelServices;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class TaskStatusService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\TaskStatus";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function findForTask(Task $task)
    {
        foreach ($this->getAll() as $status)
        {
            if ($status->id == $task->task_status_id)
            {
                return $status;
            }
        }

        return false;
    }

    public function findByName($name)
    {
        foreach ($this->getAll() as $status)
        {
            if ($status->name == $name)
            {
                return $status;
            }
        }

        return false;
    }
}