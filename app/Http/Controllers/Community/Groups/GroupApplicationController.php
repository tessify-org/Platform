<?php

namespace App\Http\Controllers\Community\Groups;

use Groups;
use GroupMemberApplications;
use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Groups\Applications\ApplyForGroupRequest;
use App\Http\Requests\Community\Groups\Applications\AcceptGroupMemberApplicationRequest;
use App\Http\Requests\Community\Groups\Applications\RejectGroupMemberApplicationRequest;

class GroupApplicationController extends Controller
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

        // Render the group applications overview
        return view("pages.community.groups.applications.overview", [
            "group" => $group,
            "applications" => collect(GroupMemberApplications::getOutstandingForGroup($group)),
            "strings" => collect([
                "no_records" => __("groups.applications_no_records"),
                "view_dialog_title" => __("groups.applications_view_dialog_title"),
                "view_dialog_user" => __("groups.applications_view_dialog_user"),
                "view_dialog_motivation" => __("groups.applications_view_dialog_motivation"),
                "view_dialog_date" => __("groups.applications_view_dialog_date"),
                "view_dialog_cancel" => __("groups.applications_view_dialog_cancel"),
                "view_dialog_accept" => __("groups.applications_view_dialog_accept"),
                "view_dialog_reject" => __("groups.applications_view_dialog_reject"),
                "accepted_dialog_title" => __("groups.applications_accepted_dialog_title"),
                "accepted_dialog_text" => __("groups.applications_accepted_dialog_text"),
                "rejected_dialog_title" => __("groups.applications_rejected_dialog_title"),
                "rejected_dialog_text" => __("groups.applications_rejected_dialog_text"),
            ]),
            "apiEndpoints" => collect([
                "accept" => route("group.applications.accept.post", $group->slug),
                "reject" => route("group.applications.reject.post", $group->slug),
            ]),
        ]);
    }

    public function getApply($slug)
    {
        // Grab the group
        $group = Groups::findPreloadedBySlug($slug);
        if (!$group)
        {
            flash(__("groups.group_not_found"))->error();
            return redirect()->route("groups");
        }

        // Render the apply for team page
        return view("pages.community.groups.applications.apply", [
            "group" => $group,
            "oldInput" => collect([
                "motivation" => old("motivation"),
            ]),
        ]);
    }

    public function postApply(ApplyForGroupRequest $request, $slug)
    {
        
    }

    public function postAccept(AcceptGroupMemberApplicationRequest $request)
    {
        // Grab the application we're accepting
        $application = GroupMemberApplications::findByUuid(request("uuid"));

        // Accept the application
        $application = GroupMemberApplications::accept($application);

        // Return a JSON response
        return response()->json(["status" => "success"]);
    }

    public function postReject(RejectGroupMemberApplicationRequest $request)
    {
        // Grab the application we're rejecting
        $application = GroupMemberApplications::findByUuid(request("uuid"));

        // Reject the application
        $application = GroupMemberApplications::reject($application);
        
        // Return a JSON response
        return response()->json(["status" => "success"]);
    }
}