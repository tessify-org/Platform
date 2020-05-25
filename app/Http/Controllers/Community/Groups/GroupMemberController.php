<?php

namespace App\Http\Controllers\Community\Groups;

use Groups;
use GroupRoles;
use GroupMembers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Groups\Members\LeaveGroupRequest;
use App\Http\Requests\Community\Groups\Members\KickGroupMemberRequest;
use App\Http\Requests\Community\Groups\Members\UpdateGroupMemberRequest;

class GroupMemberController extends Controller
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
        return view("pages.community.groups.members.overview", [
            "group" => $group,
            "roles" => collect(GroupRoles::getAllAssignableForGroup($group)),
            "members" => collect(GroupMembers::getAllPreloadedForGroup($group)),
            "strings" => collect([
                "kick" => __("groups.members_kick"),
                "update" => __("groups.members_update"),
                "no_records" => __("groups.members_no_records"),
                "kick_dialog_title" => __("groups.members_kick_dialog_title"),
                "kick_dialog_text" => __("groups.members_kick_dialog_text"),
                "kick_dialog_cancel" => __("groups.members_kick_dialog_cancel"),
                "kick_dialog_submit" => __("groups.members_kick_dialog_submit"),
                "update_dialog_title" => __("groups.members_update_dialog_title"),
                "update_dialog_role" => __("groups.members_update_dialog_role"),
                "update_dialog_cancel" => __("groups.members_update_dialog_cancel"),
                "update_dialog_submit" => __("groups.members_update_dialog_submit"),
            ]),
            "apiEndpoints" => collect([
                "kick" => route("group.members.kick.post", $group->slug),
                "update" => route("group.members.update.post", $group->slug)
            ]),
        ]);
    }

    public function getLeave($slug)
    {
        // Grab the group
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }
        
        // Render the group member leave page
        return view("pages.community.groups.members.leave", [
            "group" => $group,
        ]);
    }

    public function postLeave(LeaveGroupRequest $request, $slug)
    {
        // Grab the group
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        // Leave the group
        GroupMembers::leave($group);

        // Flash message & redirect to view group page
        flash(__("groups.left"))->success();
        return redirect()->route("group", $group->slug);
    }

    public function postKick(KickGroupMemberRequest $request, $slug)
    {
        // Grab the group
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        // Kick the group member
        GroupMembers::processKickRequest($request);
        
        return response()->json(["status" => "success"]);

        // // Flash message & redirect back to member overview
        // flash(__("groups.kicked"))->success();
        // return redirect()->route("group.members", $group->slug);
    }

    public function postUpdate(UpdateGroupMemberRequest $request, $slug)
    {
        // Grab the group
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        // Update the group member
        GroupMembers::processUpdateRequest($request);

        // Return JSON response
        return response()->json(["status" => "success"]);
    }
}