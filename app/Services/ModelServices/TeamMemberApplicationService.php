<?php

namespace App\Services\ModelServices;

use Auth;
use Users;
use TeamRoles;
use TeamMembers;
use App\Models\User;
use App\Models\Project;
use App\Models\TeamMemberApplication;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Projects\Teams\Applications\CreateTeamMemberApplicationRequest;
use App\Http\Requests\Projects\Teams\Applications\UpdateTeamMemberApplicationRequest;
use App\Http\Requests\Api\Projects\TeamMemberApplications\CreateTeamMemberApplicationRequest as ApiCreateRequest;
use App\Http\Requests\Api\Projects\TeamMemberApplications\UpdateTeamMemberApplicationRequest as ApiUpdateRequest;
use App\Http\Requests\Api\Projects\TeamMemberApplications\AcceptTeamMemberApplicationRequest as ApiAcceptRequest;
use App\Http\Requests\Api\Projects\TeamMemberApplications\DenyTeamMemberApplicationRequest as ApiDenyRequest;

class TeamMemberApplicationService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\TeamMemberApplication";
    }

    public function preload($instance)
    {
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->team_role = TeamRoles::find($instance->team_role_id);
        $instance->formatted_created_at = $instance->created_at->format("d-m-Y H:m:s");
        
        $instance->accept_href = route("projects.team.applications.accept", ["slug" => $instance->project->slug, "uuid" => $instance->uuid]);
        $instance->deny_href = route("projects.team.applications.reject", ["slug" => $instance->project->slug, "uuid" => $instance->uuid]);


        return $instance;
    }

    public function getAllForProject(Project $project)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $application)
        {
            if ($application->project_id == $project->id && $application->processed == false)
            {
                $out[] = $application;
            }
        }

        return $out;
    }

    public function findByUuid($uuid)
    {
        foreach ($this->getAll() as $application)
        {
            if ($application->uuid == $uuid)
            {
                return $application;
            }
        }

        return false;
    }

    public function findPreloadedByUuid($uuid)
    {
        foreach ($this->getAllPreloaded() as $application)
        {
            if ($application->uuid == $uuid)
            {
                return $application;
            }
        }

        return false;
    }

    public function createFromRequest(Project $project, CreateTeamMemberApplicationRequest $request)
    {
        $user = Users::current();

        return TeamMemberApplication::create([
            "project_id" => $project->id,
            "user_id" => $user->id,
            "team_role_id" => $request->team_role_id,
            "motivation" => $request->motivation,
        ]);
    }

    public function updateFromRequest(TeamMemberApplication $application, UpdateTeamMemberApplicationRequest $request)
    {
        $application->team_role_id = $request->team_role_id;
        $application->motivation = $request->motivation;
        $application->save();

        return $application;
    }

    public function accept(TeamMemberApplication $application)
    {
        // Update the application
        $application->processed = true;
        $application->accepted = true;
        $application->save();

        // Add user to project as a team member
        TeamMembers::addUserToProject($application->user, $application->teamRole, $application->project);

        // Return updated application
        return $application;
    }

    public function reject(TeamMemberApplication $application)
    {
        $application->processed = true;
        $application->accepted = false;
        $application->save();
        
        return $application;
    }
    
    public function reopen(TeamMemberApplication $application)
    {
        $application->processed = false;
        $application->accepted = false;
        $application->save();
        
        return $application;
    }

    public function apiDeny(ApiDenyRequest $request)
    {
        $application = $this->find($request->team_member_application_id);
        $application->processed = true;
        $application->accepted = false;
        $application->save();

        return $application;
    }

    public function apiAccept(ApiAcceptRequest $request)
    {
        $application = $this->find($request->team_member_application_id);
        $application->processed = true;
        $application->accepted = true;
        $application->save();

        return $application;
    }

    public function apiCreateFromRequest(ApiCreateRequest $request)
    {
        $application = TeamMemberApplication::create([
            "project_id" => $request->project_id,
            "user_id" => Auth::user()->id,
            "team_role_id" => $request->team_role_id,
            "motivation" => $request->motivation,
        ]);

        return $this->preload($application);
    }

    public function apiUpdateFromRequest(ApiUpdateRequest $request)
    {
        $application = $this->find($request->team_member_application_id);
        $application->team_role_id = $request->team_role_id;
        $application->motivation = $request->motivation;
        $application->save();
        
        return $this->preload($application);
    }

    public function hasOutstandingApplication(Project $project, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        foreach ($this->getAll() as $application)
        {
            if ($application->project_id == $project->id && $application->user_id == $user->id && !$application->processed)
            {
                return true;
            }
        }

        return false;
    }

    public function getOutstandingApplication(Project $project, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        foreach ($this->getAll() as $application)
        {
            if ($application->project_id == $project->id && $application->user_id == $user->id && !$application->processed)
            {
                return $application;
            }
        }

        return false;
    }

    public function getOutstandingApplications(Project $project)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $application)
        {
            if ($application->project_id == $project->id && !$application->processed)
            {
                $out[] = $application;
            }
        }

        return $out;
    }

    public function numOutstandingApplications(Project $project)
    {
        return count($this->getOutstandingApplications($project));
    }
}