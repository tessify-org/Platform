<?php

namespace App\Services\ModelServices;

use Auth;
use App\Models\Group;
use App\Models\GroupRole;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Community\Groups\Roles\CreateGroupRoleRequest;
use App\Http\Requests\Community\Groups\Roles\UpdateGroupRoleRequest;
use App\Http\Requests\Community\Groups\Roles\DeleteGroupRoleRequest;

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

    public function getAllAssignableForGroup(Group $group)
    {
        $out = [];

        foreach ($this->getAll() as $role)
        {
            if ($role->group_id == $group->id && $role->assignable)
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

    public function createDefaultRoles(Group $group)
    {
        // Founder role
        $founder = GroupRole::create([
            "group_id" => $group->id,
            "name" => [
                "nl" => "Oprichter",
                "en" => "Founder",
            ],
            "description" => [
                "nl" => "Deze persoon heeft de groep gestart.",
                "en" => "This person started the group.",
            ],
            "assignable" => false,
            "deleteable" => false,
        ]);

        $member = GroupRole::create([
            "group_id" => $group->id,
            "name" => [
                "nl" => "Lid",
                "en" => "Member",
            ],
            "assignable" => true,
            "deleteable" => false,
            "default" => true,
        ]);

        return [
            "founder" => $founder,
            "member" => $member,
        ];
    }

    public function getDefaultRole(Group $group)
    {
        foreach ($this->getAll() as $role)
        {
            if ($role->group_id == $group->id && $role->default)
            {
                return $role;
            }
        }

        return false;
    }

    public function createFromRequest(Group $group, CreateGroupRoleRequest $request)
    {
        return GroupRole::create([
            "group_id" => $group->id,
            "name" => [
                "nl" => request("name_nl"),
                "en" => request("name_en"),
            ],
            "description" => [
                "nl" => request("description_nl"),
                "en" => request("description_en"),
            ],
        ]);
    }

    public function updateFromRequest(GroupRole $role, UpdateGroupRoleRequest $request)
    {
        $role->name = [
            "nl" => request("name_nl"),
            "en" => request("name_en"),
        ];
        $role->description = [
            "nl" => request("description_nl"),
            "en" => request("description_en"),
        ];
        $role->save();

        return $role;
    }

    public function deleteFromRequest(DeleteGroupRoleRequest $request)
    {
        $role = $this->find(request("group_role_id"));
        $role->delete();
    }
}