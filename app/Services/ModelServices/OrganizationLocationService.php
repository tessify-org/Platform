<?php

namespace App\Services\ModelServices;

use App\Models\Organization;
use App\Models\OrganizationLocation;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Organizations\Locations\CreateOrganizationLocationRequest;
use App\Http\Requests\Organizations\Locations\UpdateOrganizationLocationRequest;
use App\Http\Requests\Api\Organizations\Locations\UpdateOrganizationLocationRequest as ApiCreateRequest;
use App\Http\Requests\Api\Organizations\Locations\CreateOrganizationLocationRequest as ApiUpdateRequest;

class OrganizationLocationService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\OrganizationLocation";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function findAllForOrganization(Organization $organization)
    {
        $out = [];

        foreach ($this->getAll() as $location)
        {
            if ($location->organization_id == $organization->id)
            {
                $out[] = $location;
            }
        }

        return $out;
    }
}