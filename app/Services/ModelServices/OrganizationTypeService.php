<?php

namespace App\Services\ModelServices;

use App\Models\OrganizationType;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Organizations\Types\CreateOrganizationTypeRequest;
use App\Http\Requests\Organizations\Types\UpdateOrganizationTypeRequest;
use App\Http\Requests\Api\Organizations\Types\CreateOrganizationTypeRequest as ApiCreateRequest;
use App\Http\Requests\Api\Organizations\Types\UpdateOrganizationTypeRequest as ApiUpdateRequest;

class OrganizationTypeService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\OrganizationType";
    }
    
    public function preload($instance)
    {
        return $instance;
    }
}