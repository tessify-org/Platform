<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\Group;
use App\Models\GroupRole;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;

class GroupRoleService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\GroupRole";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function getAllForGroup(Group $group)
    {
        $out = [];

        foreach ($this->getAll() as $role)
        {
            if ($role->group_id == $group->id)
            {
                $out[] = $role;
            }
        }

        return $out;
    }

    public function count(Group $group)
    {
        $out = 0;

        foreach ($this->getAll() as $role)
        {
            if ($role->group_id == $group->id)
            {
                $out += 1;
            }
        }

        return $out;
    }
}