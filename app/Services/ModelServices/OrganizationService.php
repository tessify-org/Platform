<?php

namespace App\Services\ModelServices;

use Ministries;
use MinistryDepartments;
use OrganizationTypes;
use OrganizationLocations;
use OrganizationDepartments;
use App\Models\Task;
use App\Models\Project;
use App\Models\Ministry;
use App\Models\MinistryDepartment;
use App\Models\Organization;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Organizations\CreateOrganizationRequest;
use App\Http\Requests\Organizations\UpdateOrganizationRequest;
use App\Http\Requests\Api\Organizations\CreateOrganizationRequest as ApiCreateRequest;
use App\Http\Requests\Api\Organizations\UpdateOrganizationRequest as ApiUpdateRequest;

class OrganizationService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Organization";
    }
    
    public function preload($instance)
    {
        $instance->ministry = Ministries::find($instance->ministry_id);
        $instance->ministry_department = MinistryDepartments::find($instance->ministry_department_id);
        $instance->type = OrganizationTypes::find($instance->organization_type_id);
        $instance->locations = OrganizationLocations::findAllForOrganization($instance);
        $instance->departments = OrganizationDepartments::findAllForOrganization($instance);

        return $instance;
    }

    public function findAllForMinistry(Ministry $ministry)
    {
        $out = [];

        foreach ($this->getAll() as $organization)
        {
            if ($organization->ministry_id == $ministry->id)
            {
                $out[] = $organization;
            }
        }

        return $out;
    }

    public function findAllForMinistryDepartment(MinistryDepartment $ministryDepartment)
    {
        $out = [];

        foreach ($this->getAll() as $organization)
        {
            if ($organization->ministry_department_id == $ministryDepartment->id)
            {
                $out[] = $organization;
            }
        }

        return $out;
    }

    public function findOrCreateByName($name)
    {
        foreach ($this->getAll() as $organization)
        {
            if ($organization->name == $name)
            {
                return $organization;
            }
        }
        
        return Organization::create([
            "name" => $name
        ]);
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
        foreach ($this->getAll() as $organization)
        {
            if ($organization->id == $project->organization_id)
            {
                return $organization;
            }
        }

        return false;
    }

    public function findForTask(Task $task)
    {
        foreach ($this->getAll() as $organization)
        {
            if ($organization->id == $task->organization_id)
            {
                return $organization;
            }
        }
    }
}