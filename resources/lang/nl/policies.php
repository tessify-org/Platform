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

    "update_deny" => "Je hebt geen toestemming om dit project aan te passen.",
    "delete_deny" => "Je hebt geen toestemming om dit project te verwijderen.",
    "status_deny" => "Je hebt geen toestemming om de status van dit project aan te passen.",
    "manage_team_roles_deny" => "Je hebt geen toestemming om de teamrollen van dit project te beheren.",
    "manage_team_applications_deny" => "Je hebt geen toestemming om de aanmeldingen voor dit project te beheren.",
    "manage_team_members_deny" => "Je hebt geen toestemming om de teamleden van dit project te beheren.",
    "apply_for_team_deny_owner" => "Je kan je niet aanmelden want je bent de eigenaar van dit project.",
    "apply_for_team_deny_team_member" => "Je kan je niet aanmelden want je bent al lid van dit team.",
    "apply_for_team_deny_application_exists" => "Je hebt al een aanmelding open staan voor dit project.",
    "leave_team_deny" => "Je kan dit team niet verlaten want je bent er geen onderdeel van.",

    // Group policy specific

    "manage_group_roles_deny" => "Je hebt geen toestemming om de rollen van deze groep te beheren.",
    "manage_group_members_deny" => "Je hebt geen toestemming om de leden van deze groep te beheren.",
    "manage_group_applications_deny" => "Je hebt geen toestemming om de aanmeldingen van deze groep te beheren.",
    "apply_for_group_deny_founder" => "Je kan je niet aanmelden want je bent de oprichter van de groep.",
    "apply_for_group_deny_team_member" => "Je kan je niet aanmelden want je bent al lid van de groep.",
    "apply_for_group_deny_application_exists" => "Je kan je niet aanmelden want je hebt al een aanmelding lopen.",

];