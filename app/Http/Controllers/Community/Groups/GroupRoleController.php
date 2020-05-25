<?php

namespace App\Http\Controllers\Community\Groups;

use Groups;
use GroupRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Groups\Roles\CreateGroupRoleRequest;
use App\Http\Requests\Community\Groups\Roles\UpdateGroupRoleRequest;
use App\Http\Requests\Community\Groups\Roles\DeleteGroupRoleRequest;

class GroupRoleController extends Controller
{
    public function getOverview($slug)
    {   
        // Grab the group
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        // Render the group member overview page
        return view("pages.community.groups.roles.overview", [
            "group" => $group,
            "roles" => collect(GroupRoles::getAllForGroup($group)),
            "strings" => collect([
                "no_records" => __("groups.roles_no_records"),
                "add_button" => __("groups.roles_add_button"),
                "name" => __("groups.roles_form_name"),
                "description" => __("groups.roles_form_description"),
                "view_dialog_title" => __("groups.roles_view_dialog_title"),
                "view_dialog_name" => __("groups.roles_view_dialog_name"),
                "view_dialog_description" => __("groups.roles_view_dialog_description"),
                "view_dialog_date" => __("groups.roles_view_dialog_date"),
                "view_dialog_update" => __("groups.roles_view_dialog_update"),
                "view_dialog_delete" => __("groups.roles_view_dialog_delete"),
                "create_dialog_title" => __("groups.roles_create_dialog_title"),
                "create_dialog_cancel" => __("groups.roles_create_dialog_cancel"),
                "create_dialog_submit" => __("groups.roles_create_dialog_submit"),
                "update_dialog_title" => __("groups.roles_update_dialog_title"),
                "update_dialog_cancel" => __("groups.roles_update_dialog_cancel"),
                "update_dialog_submit" => __("groups.roles_update_dialog_submit"),
                "delete_dialog_title" => __("groups.roles_delete_dialog_title"),
                "delete_dialog_text" => __("groups.roles_delete_dialog_text"),
                "delete_dialog_cancel" => __("groups.roles_delete_dialog_cancel"),
                "delete_dialog_submit" => __("groups.roles_delete_dialog_submit"),
                "nl" => __("general.nl"),
                "en" => __("general.en"),
            ]),
            "apiEndpoints" => collect([
                "create" => route("group.roles.create.post", $group->slug),
                "update" => route("group.roles.update.post", $group->slug),
                "delete" => route("group.roles.delete.post", $group->slug),
            ]),
        ]);
    }

    public function postCreate(CreateGroupRoleRequest $request, $slug)
    {
        // Grab the group
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        // Create the group role
        $role = GroupRoles::createFromRequest($group, $request);

        // Return JSON response
        return response()->json([
            "status" => "success",
            "role" => $role,
        ]);
    }

    public function postUpdate(UpdateGroupRoleRequest $request)
    {
        // Grab the role
        $role = GroupRoles::find(request("group_role_id"));

        // Update the role
        $role = GroupRoles::updateFromRequest($role, $request);

        // Return JSON response
        return response()->json([
            "status" => "success",
            "role" => $role,
        ]);
    }

    public function postDelete(DeleteGroupRoleRequest $request)
    {
        // Delete the role
        GroupRoles::deleteFromRequest($request);

        // Return JSON response
        return response()->json(["status" => "success"]);
    }
}