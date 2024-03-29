<?php

namespace App\Policies;

use Tasks;
use Projects;
use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;
    
    /**
     * If the user is an administrator, give him/her full access
     *
     * @param  \App\Models\User  $user
     * @return true|void
     */
    public function before(User $user)
    {
        // if ($user->is_admin) return true;
    }

    /**
     * Determine if the User can update this Task
     * 
     * @param   \App\Models\User
     * @param   \App\Models\Task
     * @return  boolean
     */
    public function update(User $user, Task $task)
    {
        $project = Projects::find($task->project_id);

        return ( $project and $project->author_id == $user->id ) or $task->author_id == $user->id;
    }

    /**
     * Determine if the User can delete this Task
     * 
     * @param   \App\Models\User
     * @param   \App\Models\Task
     * @return  boolean
     */
    public function delete(User $user, Task $task)
    {
        $project = Projects::find($task->project_id);
        
        return ( $project and $project->author_id == $user->id ) or $task->author_id == $user->id;
    }

    /**
     * Determine if the User can assign themself this Task
     * 
     * @param   \App\Models\User
     * @param   \App\Models\Task
     * @return  boolean
     */
    public function assignToSelf(User $user, Task $task)
    {
        return $task->status->name !== "completed" and Tasks::hasAvailableSlots($task) and !Tasks::assignedToUser($task, $user);
    }

    /**
     * Determine if the User can unassign themselves from this Task
     * 
     * @param   \App\Models\User
     * @param   \App\Models\Task
     * @return  boolean
     */
    public function abandon(User $user, Task $task)
    {
        return $task->status->name !== "completed" and Tasks::assignedToUser($task, $user);
    }
}