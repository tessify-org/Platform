<?php

namespace App\Services\ModelServices;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskCategory;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class TaskCategoryService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\TaskCategory";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function findForTask(Task $task)
    {
        foreach ($this->getAll() as $category)
        {
            if ($category->id == $task->task_category_id)
            {
                return $category;
            }
        }

        return false;
    }

    public function findOrCreateByName($name)
    {
        $locale = app()->getLocale();

        foreach ($this->getAll() as $category)
        {
            if ($category->name == $name)
            {
                return $category;
            }
        }

        return TaskCategory::create([
            "name" => [
                "en" => $name,
                "nl" => $name,
            ],
        ]);
    }
}