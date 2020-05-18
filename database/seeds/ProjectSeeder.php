<?php

use App\Models\User;

use App\Models\Skill;
use App\Models\Project;
use App\Models\TeamRole;
use App\Models\Ministry;
use App\Models\Organization;
use App\Models\OrganizationDepartment;
use App\Models\WorkMethod;
use App\Models\TeamMember;
use App\Models\ProjectPhase;
use App\Models\ProjectStatus;
use App\Models\ProjectCategory;
use App\Models\ProjectResource;
use App\Models\TeamMemberApplication;

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("projects")->delete();
        DB::table("project_statuses")->delete();
        DB::table("project_categories")->delete();
        DB::table("work_methods")->delete();
        DB::table("team_roles")->delete();
        DB::table("team_members")->delete();
        DB::table("team_member_applications")->delete();
        DB::table("team_member_team_role")->delete();

        //
        // Work methods
        // 
        
        $scrum = WorkMethod::create([
            "name" => "scrum",
            "label" => "SCRUM",
            "description" => "Scrum is een framework om op een flexibele manier (software)producten te maken. Er wordt gewerkt in multidisciplinaire teams die in korte sprints, met een vaste lengte van 1 tot 4 weken, werkende (software) producten opleveren. Scrum is een term die afkomstig is uit de rugbysport. Bij een scrum probeert een team samen een doel te bereiken en de wedstrijd te winnen. Samenwerking is heel belangrijk en men moet snel kunnen inspelen op veranderende omstandigheden.",
            "external_url" => "https://nl.wikipedia.org/wiki/Scrum_(softwareontwikkelmethode)",
        ]);

        $kanban = WorkMethod::create([
            "name" => "kanban",
            "label" => "KanBan",
            "description" => "Kanban is een systeem om te signaleren (met bijvoorbeeld kaartjes) wanneer iets nodig is. Kanban kan gebruikt worden om van alles in het leven te organiseren.[1] Kanban is een systeem om de logistieke productieketen te besturen. Kanban werd ontwikkeld door Taiichi Ohno, bij Toyota, om een systeem te vinden waarmee het mogelijk was om een hoog niveau van productie te behalen.",
            "external_url" => "https://nl.wikipedia.org/wiki/Kanban",
        ]);

        $prince2 = WorkMethod::create([
            "name" => "prince2",
            "label" => "Prince2",
            "description" => "PRINCE2 (een acroniem van Projects in Controlled Environments, version 2) is een methode voor projectmanagement. Deze methode is gericht op het management, de besturing en de organisatie van een project. PRINCE2 is ontwikkeld door de Britse semioverheidsorganisatie Office of Government Commerce (OGC).",
            "external_url" => "https://nl.wikipedia.org/wiki/PRINCE2",
        ]);

        //
        // Project statuses
        //

        $open = ProjectStatus::create([
            "name" => "open", 
            "label" => "Open"
        ]);
        $closed = ProjectStatus::create([
            "name" => "closed", 
            "label" => "Closed"
        ]);

        //
        // Project phases
        //

        $concept = ProjectPhase::create(["name" => "concept"]);
        $brainstorm = ProjectPhase::create(["name" => "brainstorm"]);
        $ontwerp = ProjectPhase::create(["name" => "ontwerp"]);
        $testen = ProjectPhase::create(["name" => "testen"]);
        $aansluiting = ProjectPhase::create(["name" => "aansluiting dienst op werk"]);
        $feedback = ProjectPhase::create(["name" => "feedbackronde"]);

        //
        // Project categories
        //

        $software = ProjectCategory::create([
            "name" => "software", 
            "label" => "Software"
        ]);
        $hardware = ProjectCategory::create([
            "name" => "hardware", 
            "label" => "Hardware"
        ]);
        $process = ProjectCategory::create([
            "name" => "process-improvement", 
            "label" => "Procesverbetering"
        ]);

        //
        // Grab random user
        //

        $user = User::where("email", "nick.verheijen@minbzk.nl")->first();

        //
        // Generate projects
        //

        $skills = Skill::all();
        $phases = ProjectPhase::all();
        $ministries = Ministry::all();
        $categories = ProjectCategory::all();
        $statuses = ProjectStatus::all();
        $workMethods = WorkMethod::all();

        for ($i = 0; $i < 30; $i++)
        {
            // Create the project
            $project = factory(Project::class)->create([
                "author_id" => $user->id,
                "work_method_id" => $workMethods->random()->id,
                "project_status_id" => $statuses->random()->id,
                "project_category_id" => $categories->random()->id,
                "ministry_id" => rand(0, 1) == 1 ? $ministries->random()->id : null,
                "project_phase_id" => $phases->random()->id,
                "header_image_url" => "storage/images/projects/header/default.jpeg"
            ]);
                
            // Role 1
            $role_one = TeamRole::create([
                "project_id" => $project->id,
                "name" => "Developer",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras massa tellus, consectetur eu pellentesque id, mollis id ante. Sed accumsan auctor tortor, sit amet blandit ex dapibus ac. Nullam feugiat malesuada felis at malesuada.",
            ]);
            $role_one->skills()->attach([$skills->get(0)->id, $skills->get(1)->id, $skills->get(2)->id]);
            
            // Role 1 team member
            $role_one_member = TeamMember::create([
                "project_id" => $project->id,
                "user_id" => $user->id,
            ]);
            $role_one_member->teamRoles()->attach($role_one->id);

            // Role 2
            $role_two = TeamRole::create([
                "project_id" => $project->id,
                "name" => "Designer",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras massa tellus, consectetur eu pellentesque id, mollis id ante. Sed accumsan auctor tortor, sit amet blandit ex dapibus ac. Nullam feugiat malesuada felis at malesuada.",
            ]);
            $skills = $skills->shuffle();
            $role_two->skills()->attach([$skills->get(0)->id, $skills->get(1)->id, $skills->get(2)->id]);

            // Role 2 applications
            $role_two_application = TeamMemberApplication::create([
                "project_id" => $project->id,
                "user_id" => $user->id,
                "team_role_id" => $role_two->id,
                "motivation" => "I'm the 1337est hax0r",
                "processed" => false,
                "accepted" => false,
            ]);
            
            // Role 3
            $role_three = TeamRole::create([
                "project_id" => $project->id,
                "name" => "Scrum Master",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras massa tellus, consectetur eu pellentesque id, mollis id ante. Sed accumsan auctor tortor, sit amet blandit ex dapibus ac. Nullam feugiat malesuada felis at malesuada.",
            ]);
            $skills = $skills->shuffle();
            $role_three->skills()->attach([$skills->get(0)->id, $skills->get(1)->id, $skills->get(2)->id]);
        }
    }
}
