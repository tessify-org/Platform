<?php

namespace App\Policies;

use Groups;
use GroupMembers;
use GroupMemberApplications;
use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * If the user is an administrator, give him/her full access
     *
     * @param  \App\Models\User $user
     * @return true|void
     */
    public function before(User $user)
    {
        if ($user->is_admin) return true;
    }

    /**
     * Determine whether the user can view any groups.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the group.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function view(User $user, Group $group)
    {
        return true;
    }

    /**
     * Determine whether the user can create groups.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        return $project->author_id == $user->id
            ? Response::allow()
            : Response::deny(__("policies.update_deny"));
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $project->author_id == $user->id
            ? Response::allow()
            : Response::deny(__("policies.delete_deny"));
    }

    /**
     * Determine whether the user can manage this group's team roles.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function manageGroupRoles(User $user, Group $group)
    {
        return $project->author_id == $user->id
            ? Response::allow()
            : Response::deny(__("policies.manage_group_roles_deny"));
    }

    /**
     * Determine whether the user can manage this group's member applications.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function manageGroupMemberApplications(User $user, Group $group)
    {
        return $project->author_id == $user->id
            ? Response::allow()
            : Response::deny(__("policies.manage_group_applications_deny"));
    }

    /**
     * Determine whether the user can manage this group's members.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function manageGroupMembers(User $user, Group $group)
    {
        return $project->author_id == $user->id
            ? Response::allow()
            : Response::deny(__("policies.manage_group_members_deny"));
    }

    /**
     * Determine whether the user can apply for team roles on this group.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function applyForGroup(User $user, Group $group)
    {
        // Make sure the user is not the author
        if ($group->founder_id == $user->id)
        {
            return Response::deny(__("policies.apply_for_group_deny_founder"));
        }

        // Make sure the user is not already a team member
        if (GroupMembers::isMember($group, $user))
        {
            return Response::deny(__("policies.apply_for_group_deny_team_member"));
        }
        
        // Make sure the user does not already have an outstanding application
        if (GroupMemberApplications::hasOutstandingApplication($group, $user))
        {
            return Response::deny(__("policies.apply_for_group_deny_application_exists"));
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can leave the team of a given project.
     * 
     * @param  \App\Models\User     $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function leaveTeam(User $user, Group $group)
    {
        return GroupMembers::isMember($group, $user)
            ? Response::allow()
            : Response::deny(__("policies.leave_team_deny"));
    }
}
