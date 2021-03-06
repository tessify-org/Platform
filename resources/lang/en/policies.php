<?php

/*
|--------------------------------------------------------------------------
| Policy Language Lines
|--------------------------------------------------------------------------
| 
| The following language lines are used throughout the policies the CORE
| package provides.
|
*/

return [

    //
    // Project policy
    //

    "update_deny" => "You don't have permission to update this project.",
    "delete_deny" => "You don't have permission to delete this project.",
    "status_deny" => "You don't have permission to update this project's status.",
    "manage_team_roles_deny" => "You don't have permission to manage this project's team roles.",
    "manage_team_applications_deny" => "You don't have permission to manage this project's team applications.",
    "manage_team_members_deny" => "You don't have permission to manage this project's team members.",
    "apply_for_team_deny_owner" => "You can't apply for a role as you are the owner of this project.",
    "apply_for_team_deny_team_member" => "You can't apply for a role as you are already on this project's team.",
    "apply_for_team_deny_application_exists" => "You already have an outstanding application that's awaiting processing.",
    "leave_team_deny" => "You can only leave this team if you're a part of it.",

    // Group policy specific

    "manage_group_roles_deny" => "You don't have permission to manage this group's roles.",
    "manage_group_members_deny" => "You don't have permission to manage this group's members.",
    "manage_group_applications_deny" => "You don't have permission to manage this group's member applications.",
    "apply_for_group_deny_founder" => "You can't apply for this group as you are it's founder.",
    "apply_for_group_deny_team_member" => "You can't apply for this group as you are already a member.",
    "apply_for_group_deny_application_exists" => "You can't apply for this group as you've already submitted an application.",

];
