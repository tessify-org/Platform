<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\CompletedTask;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class CompletedTaskService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\CompletedTask";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function create(Task $task, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        return CompletedTask::create([
            "user_id" => $user->id,
            "task_id" => $task->id,
        ]);
    }
}