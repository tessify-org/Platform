<?php

namespace App\Services\ModelServices;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Projects\Categories\CreateProjectCategoryRequest;
use App\Http\Requests\Projects\Categories\UpdateProjectCategoryRequest;

class ProjectCategoryService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\ProjectCategory";
    }

    public function preload($instance)
    {
        return $instance;
    }
    
    public function findForProject(Project $project)
    {
        return $this->find($project->project_category_id);
    }

    public function createFromRequest(CreateProjectCategoryRequest $request)
    {
        return ProjectCategory::create([
            "name" => $request->name,
            "label" => $request->label,
        ]);
    }

    public function updateFromRequest(ProjectCategory $category, UpdateProjectCategoryRequest $request)
    {
        $category->name = $request->name;
        $category->label = $request->label;
        $category->save();

        return $category;
    }

    public function findOrCreateByLabel($label)
    {
        foreach ($this->getAll() as $category)
        {
            if ($category->label == $label)
            {
                return $category;
            }
        }

        return ProjectCategory::create([
            "name" => str_replace(" ", "_", strtolower($label)),
            "label" => [
                "en" => $label,
                "nl" => $label,
            ],
        ]);
    }
}