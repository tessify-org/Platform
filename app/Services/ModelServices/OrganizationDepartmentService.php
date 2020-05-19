<?php

namespace App\Services\ModelServices;

use App\Models\Task;
use App\Models\Project;
use App\Models\Organization;
use App\Models\OrganizationDepartment;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Organizations\Departments\CreateDepartmentRequest;
use App\Http\Requests\Organizations\Departments\UpdateDepartmentRequest;
use App\Http\Requests\Api\Organizations\Departments\CreateDepartmentRequest as ApiCreateRequest;
use App\Http\Requests\Api\Organizations\Departments\UpdateDepartmentRequest as ApiUpdateRequest;

class OrganizationDepartmentService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\OrganizationDepartment";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $department)
        {
            if ($department->slug == $slug)
            {
                return $department;
            }
        }
        
        return false;
    }

    public function findAllForOrganization(Organization $organization)
    {
        $out = [];

        foreach ($this->getAll() as $department)
        {
            if ($department->organization_id == $organization->id)
            {
                $out[] = $department;
            }
        }

        return $out;
    }

    public function findOrCreateByName(Organization $organization, $name)
    {
        foreach ($this->getAll() as $department)
        {
            if ($department->organization_id == $organization->id and $department->name == $name)
            {
                return $department;
            }
        }

        return OrganizationDepartment::create([
            "organization_id" => $organization->id,
            "name" => [
                "nl" => $name,
                "en" => $name,
            ],
        ]);
    }

    public function findForProject(Project $project)
    {
        foreach ($this->getAll() as $department)
        {
            if ($department->id == $project->organization_department_id)
            {
                return $department;
            }
        }
        
        return false;
    }

    public function findForTask(Task $task)
    {
        foreach ($this->getAll() as $department)
        {
            if ($department->id == $task->organization_department_id)
            {
                return $department;
            }
        }

        return false;
    }
}