<?php

namespace App\Http\Controllers\Projects;

use Projects;
use TeamRoles;
use TeamMembers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\Teams\Roles\CreateTeamRoleRequest;
use App\Http\Requests\Projects\Teams\Roles\UpdateTeamRoleRequest;
use App\Http\Requests\Projects\Teams\Roles\DeleteTeamRoleRequest;

class ProjectTeamRoleController extends Controller
{
    public function getOverview($slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        return view("pages.projects.team.roles.overview", [
            "project" => $project,
            "roles" => collect(TeamRoles::getAllForProject($project)),
            "strings" => collect([
                "title" => __("projects.team_roles_title"),
                "no_records" => __("projects.team_roles_no_records"),
                "add_button" => __("projects.team_roles_add_button"),
                "view_title" => __("projects.team_roles_view_title"),
                "view_edit" => __("projects.team_roles_view_edit"),
                "view_delete" => __("projects.team_roles_view_delete"),
                "create_title" => __("projects.team_roles_create_title"),
                "create_cancel" => __("projects.team_roles_create_cancel"),
                "create_submit" => __("projects.team_roles_create_submit"),
                "update_title" => __("projects.team_roles_update_title"),
                "update_cancel" => __("projects.team_roles_update_cancel"),
                "update_submit" => __("projects.team_roles_update_submit"),
                "delete_title" => __("projects.team_roles_delete_title"),
                "delete_text" => __("projects.team_roles_delete_text"),
                "delete_cancel" => __("projects.team_roles_delete_cancel"),
                "delete_submit" => __("projects.team_roles_delete_submit"),
                "form_name" => __("projects.team_roles_form_name"),
                "form_description" => __("projects.team_roles_form_description"),
                "form_positions" => __("projects.team_roles_form_positions"),
                "nl" => __("general.nl"),
                "en" => __("general.en"),
            ]),
            "apiEndpoints" => collect([
                "create" => route("api.team-roles.create"),
                "update" => route("api.team-roles.update"),
                "delete" => route("api.team-roles.delete"),
            ]),
        ]);
    }

    public function getCreate($slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        return view("pages.projects.teams.roles.create", [
            "project" => $project,
            "oldInput" => collect([
                "name" => old("name"),
                "description" => old("description"),
                "positions" => old("positions"),
            ]),
            "strings" => collect([
                "name" => __("projects.create_role_name"),
                "description" => __("projects.create_role_description"),
                "positions" => __("projects.create_role_positions"),
                "back" => __("projects.create_role_back"),
                "submit" => __("projects.create_role_submit"),
                "en" => __("general.en"),
                "nl" => __("general.nl"),
            ]),
        ]);
    }

    public function postCreate(CreateTeamRoleRequest $request, $slug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $this->authorize("manage-team-roles", $project);

        TeamRoles::createFromRequest($project, $request);

        flash(__("projects.create_role_succeeded"))->success();
        return redirect()->route("projects.team.view", $project->slug);
    }

    public function getUpdate($slug, $roleSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $teamRole = TeamRoles::findPreloadedBySlug($roleSlug);
        if (!$teamRole)
        {
            flash(__("projects.team_role_not_found"))->error();
            return redirect()->route("projects.team.view", $project->slug);
        }

        return view("pages.projects.teams.roles.edit", [
            "project" => $project,
            "teamRole" => $teamRole,
            "oldInput" => collect([
                "name" => old("name"),
                "description" => old("description"),
                "positions" => old("positions"),
            ]),
            "strings" => collect([
                "name" => __("projects.create_role_name"),
                "description" => __("projects.create_role_description"),
                "positions" => __("projects.create_role_positions"),
                "back" => __("projects.create_role_back"),
                "submit" => __("projects.create_role_submit"),
                "en" => __("general.en"),
                "nl" => __("general.nl"),
            ]),
        ]);
    }

    public function postUpdate(UpdateTeamRoleRequest $request, $slug, $roleSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $this->authorize("manage-team-roles", $project);

        $teamRole = TeamRoles::findPreloadedBySlug($roleSlug);
        if (!$teamRole)
        {
            flash(__("projects.team_role_not_found"))->error();
            return redirect()->route("projects.team.view", $project->slug);
        }
        
        TeamRoles::updateFromRequest($teamRole, $request);

        flash(__("general.saved_changes"))->success();
        return redirect()->route("projects.team.view", $project->slug);
    }

    public function getDelete($slug, $roleSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $teamRole = TeamRoles::findPreloadedBySlug($roleSlug);
        if (!$teamRole)
        {
            flash(__("projects.team_role_not_found"))->error();
            return redirect()->route("projects.view", $project->slug);
        }
        
        return view("pages.projects.teams.roles.delete", [
            "project" => $project,
            "teamRole" => $teamRole,
        ]);
    }

    public function postDelete(DeleteTeamRoleRequest $request, $slug, $roleSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $this->authorize("manage-team-roles", $project);

        $teamRole = TeamRoles::findPreloadedBySlug($roleSlug);
        if (!$teamRole)
        {
            flash(__("projects.team_role_not_found"))->error();
            return redirect()->route("projects.team.view", $project->slug);
        }
        
        TeamRoles::deleteRole($teamRole);

        flash(__("projects.delete_role_succeeded"))->success();
        return redirect()->route("projects.team.view", $project->slug);
    }

    public function getAssignToMe($slug, $roleSlug)
    {
        $project = Projects::findPreloadedBySlug($slug);
        if (!$project)
        {
            flash(__("projects.project_not_found"))->error();
            return redirect()->route("projects");
        }

        $this->authorize("manage-team-roles", $project);

        $teamRole = TeamRoles::findPreloadedBySlug($roleSlug);
        if (!$teamRole)
        {
            flash(__("projects.team_role_not_found"))->error();
            return redirect()->route("projects.view", $project->slug);
        }
        
        TeamMembers::addUserToTeam($teamRole);

        flash(__("projects.view_team_assigned_to_self", ["name" => $teamRole->name]))->success();
        return redirect()->route("projects.team.view", $project->slug);
    }
}