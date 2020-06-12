<?php

namespace App\Http\Controllers\Projects;

use Users;
use Projects;
use TeamMembers;
use TeamMemberApplications;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\Teams\LeaveTeamRequest;
use App\Http\Requests\Projects\Teams\RemoveMemberFromTeamRequest;
use App\Http\Requests\Projects\Teams\UpdateTeamMemberRolesRequest;
use App\Http\Requests\Projects\Teams\Applications\InviteTeamMemberRequest;

class ProjectTeamController extends Controller
{
    public function getOverview($slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        return view("pages.projects.team.overview", [
            "project" => $project,
            "members" => collect(TeamMembers::getAllForProject($project)),
            "memberOverviewStrings" => collect([
                "title" => __("projects.team_title"),
                "no_members" => __("projects.team_no_members"),
                "user" => __("projects.team_user"),
                "roles" => __("projects.team_roles"),
                "invite_user" => __("projects.team_invite"),
                "view_dialog_title" => __("projects.team_view_dialog_title"),
                "view_dialog_user" => __("projects.team_view_dialog_user"),
                "view_dialog_roles" => __("projects.team_view_dialog_roles"),
                "view_dialog_required_skills" => __("projects.team_view_dialog_required_skills"),
                "view_dialog_joined_on" => __("projects.team_view_dialog_joined_on"),
                "view_dialog_update" => __("projects.team_view_dialog_update"),
                "view_dialog_kick" => __("projects.team_view_dialog_kick"),
                "update_dialog_title" => __("projects.team_update_dialog_title"),
                "update_dialog_user" => __("projects.team_update_dialog_user"),
                "update_dialog_roles" => __("projects.team_update_dialog_roles"),
                "update_dialog_cancel" => __("projects.team_update_dialog_cancel"),
                "update_dialog_submit" => __("projects.team_update_dialog_submit"),
                "kick_dialog_title" => __("projects.team_kick_dialog_title"),
                "kick_dialog_text" => __("projects.team_kick_dialog_text"),
                "kick_dialog_reason" => __("projects.team_kick_dialog_reason"),
                "kick_dialog_reason_hint" => __("projects.team_kick_dialog_reason_hint"),
                "kick_dialog_cancel" => __("projects.team_kick_dialog_cancel"),
                "kick_dialog_submit" => __("projects.team_kick_dialog_submit"),
            ]),
            "memberOverviewApiEndpoints" => collect([
                "update" => route("api.team-members.update"),
                "kick" => route("api.team-members.kick"),
            ]),
            "applications" => collect(TeamMemberApplications::getAllForProject($project)),
            "applicationOverviewStrings" => collect([
                "title" => __("projects.team_applications_title"),
                "no_records" => __("projects.team_applications_no_records"),
                "view_dialog_title" => __("projects.team_applications_view_dialog_title"),
                "view_dialog_user" => __("projects.team_applications_view_dialog_user"),
                "view_dialog_role" => __("projects.team_applications_view_dialog_role"),
                "view_dialog_motivation" => __("projects.team_applications_view_dialog_motivation"),
                "view_dialog_date" => __("projects.team_applications_view_dialog_date"),
                "view_dialog_back" => __("projects.team_applications_view_dialog_back"),
                "view_dialog_deny" => __("projects.team_applications_view_dialog_deny"),
                "view_dialog_accept" => __("projects.team_applications_view_dialog_accept"),
            ]),
            "applicationOverviewApiEndpoints" => collect([
                "accept" => route("api.team-member-applications.accept"),
                "deny" => route("api.team-member-applications.deny"),
            ]),
        ]);
    }

    public function getView($slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        return view("pages.projects.teams.view", [
            "project" => $project,
            "user" => Users::current(),
            "outstandingRoles" => Projects::getOutstandingRoles($project),
        ]);
    }

    public function getLeaveTeam($slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        return view("pages.projects.team.leave", [
            "project" => $project,
        ]);
    }

    public function postLeaveTeam(LeaveTeamRequest $request, $slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $this->authorize("leave-team", $project);

        TeamMembers::removeUserFromTeam($project);

        flash(__("projects.leave_team_success"))->success();
        return redirect()->route("projects.view", $project->slug);
    }

    public function getRemoveMember($slug, $userSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $user = Users::findBySlug($userSlug);
        if (!$user)
        {
            flash(__("profiles.user_not_found"))->error();
            return redirect()->route("projects.view", $project->slug);
        }
        
        return view("pages.projects.teams.members.remove-member", [
            "project" => $project,
            "user" => $user,
        ]);
    }

    public function postRemoveMember(RemoveMemberFromTeamRequest $request, $slug, $userSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $this->authorize("manage-team-members", $project);

        $user = Users::findBySlug($userSlug);
        if (!$user)
        {
            flash(__("profiles.user_not_found"))->error();
            return redirect()->route("projects.view", $project->slug);
        }

        TeamMembers::removeUserFromTeam($project, $user);

        flash(__("projects.removed_from_team", ["name" => $user->formattedName]))->success();
        return redirect()->route("projects.team.view", $project->slug);
    }

    public function getInvite($slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        return view("pages.projects.team.invite-people", [
            "project" => $project,
            "users" => Users::getAllNotInProjectTeam($project),
        ]);
    }

    public function postInvite(InviteTeamMemberRequest $request, $slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

    }

    public function getChangeMemberRoles($slug, $userSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $user = Users::findBySlug($userSlug);
        if (!$user)
        {
            flash(__("profiles.user_not_found"))->error();
            return redirect()->route("projects.team.view", $project->slug);
        }

        $teamMember = Projects::findTeamMember($project, $user);
        if (!$teamMember)
        {
            flash(__("projects.team_member_not_found"))->error();
            return redirect()->route("projects.team.view", $project->slug);
        }

        return view("pages.projects.teams.members.change-roles", [
            "user" => $user,
            "project" => $project,
            "member" => $teamMember,
            "roles" => Projects::getOutstandingRoles($project),
            "oldInput" => collect([
                "team_role_id" => old("team_role_id"),
            ])
        ]);
    }

    public function postChangeMemberRoles(UpdateTeamMemberRolesRequest $request, $slug, $userSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $this->authorize("manage-team-members", $project);

        $user = Users::findBySlug($userSlug);
        if (!$user)
        {
            flash(__("profiles.user_not_found"))->error();
            return redirect()->route("projects.team.view", $project->slug);
        }

        $teamMember = Projects::findTeamMember($project, $user);
        if (!$teamMember)
        {
            flash(__("projects.team_member_not_found"))->error();
            return redirect()->route("projects.team.view", $project->slug);
        }

        TeamMembers::updateRolesFromRequest($teamMember, $request);

        flash(__("projects.change_roles_success"))->success();
        return redirect()->route("projects.team.view", $project->slug);
    }
}