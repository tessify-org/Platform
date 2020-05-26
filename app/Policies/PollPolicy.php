<?php

namespace App\Policies;

use Polls;
use App\Models\User;
use App\Models\Poll;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PollPolicy
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
     * @param  \App\Models\Poll $poll
     * @return mixed
     */
    public function view(User $user, Poll $poll)
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
     * @param  \App\Models\Poll $poll
     * @return mixed
     */
    public function update(User $user, Poll $poll)
    {
        return $project->author_id == $user->id
            ? Response::allow()
            : Response::deny(__("policies.update_deny"));
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Poll $poll
     * @return mixed
     */
    public function delete(User $user, Poll $poll)
    {
        return $project->author_id == $user->id
            ? Response::allow()
            : Response::deny(__("policies.delete_deny"));
    }
}
