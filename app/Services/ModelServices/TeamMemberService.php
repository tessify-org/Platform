<?php

namespace App\Services\ModelServices;

use Auth;
use Users;
use TeamRoles;
use Exception;
use App\Models\User;
use App\Models\Project;
use App\Models\TeamRole;
use App\Models\TeamMember;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Api\Projects\TeamMembers\KickTeamMemberRequest as ApiKickRequest;
use App\Http\Requests\Api\Projects\TeamMembers\UpdateTeamMemberRequest as ApiUpdateRequest;


class TeamMemberService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\TeamMember";
    }

    public function preload($instance)
    {
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->team_roles = TeamRoles::findAllForMember($instance);

        return $instance;
    }

    public function addUserToTeam(TeamRole $teamRole, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        // Create the team member
        $teamMember = TeamMember::create([
            "project_id" => $teamRole->project_id,
            "user_id" => $user->id,
        ]);

        // Associate the team member with the role
        $teamMember->teamRoles()->attach($teamRole->id);

        // Return the team member
        return $teamMember;
    }

    public function addUserToProject(User $user, TeamRole $teamRole, Project $project)
    {
        $teamMember = TeamMember::create([
            "project_id" => $project->id,
            "user_id" => $user->id,
        ]);
        $teamMember->teamRoles()->attach($teamRole->id);
    }

    public function removeUserFromTeam(Project $project, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        foreach ($project->teamMembers as $teamMember)
        {
            if ($teamMember->user_id == $user->id)
            {
                $teamMember->teamRoles()->detach();
                $teamMember->delete();
            }
        }
    }

    public function updateRolesFromApiRequest(ApiUpdateRequest $request)
    {
        // Grab the team member we're updating
        $teamMember = $this->find($request->team_member_id);

        // Detach current team roles from the team member
        $teamMember->teamRoles()->detach();

        // Decode the new role ids
        $role_ids = json_decode($request->role_ids);
        if (!is_array($role_ids) || count($role_ids) == 0)
        {
            throw new Exception("Invalid role ids received.");
        }

        // Attach new roles
        $teamMember->teamRoles()->attach($role_ids);

        // Return the new roles (so we can update the ui without refreshing)
        return $teamMember->teamRoles;
    }

    public function kickFromApiRequest(ApiKickRequest $request)
    {
        // Grab the team member we're kicking
        $teamMember = $this->find($request->team_member_id);

        // Detach the team members roles
        $teamMember->teamRoles()->detach();

        // Delete the team member
        $teamMember->delete();
    }

    public function getAllForProject(Project $project)
    {
        $out = [];

        foreach ($this->getAll() as $teamMember)
        {
            if ($teamMember->project_id == $project->id)
            {
                $out[] = $this->preload($teamMember);
            }
        }
        
        return $out;
    }
}