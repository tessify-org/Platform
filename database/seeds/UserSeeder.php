<?php

use App\Models\User;

use App\Models\Skill;
use App\Models\Assignment;
use App\Models\AssignmentType;
use App\Models\Ministry;
use App\Models\Organization;
use App\Models\OrganizationDepartment;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->delete();
        DB::table("user_follower")->delete();
        DB::table("subscriptions")->delete();
        
        //
        // My account
        //

        $nick = User::create([
            "first_name" => "Nick",
            "last_name" => "Verheijen",
            "email" => "nick.verheijen@minbzk.nl",
            "password" => bcrypt("engeland"),
            "avatar_url" => "storage/images/users/avatars/nick.jpeg",
            "header_bg_url" => "storage/images/users/headers/nick.png",
            "headline" => "This is the way. I have spoken.",
            "interests" => "I like turtles.",
            "is_admin" => true,
        ]);
        
        // Skills
        $php = Skill::where("name->en", "PHP")->first();
        $mysql = Skill::where("name->en", "MySQL")->first();
        $html = Skill::where("name->en", "HTML")->first();
        $css = Skill::where("name->en", "CSS")->first();
        $c = Skill::where("name->en", "C")->first();
        $cpp = Skill::where("name->en", "C++")->first();
        $cs = Skill::where("name->en", "C#")->first();
        $net = Skill::where("name->en", ".NET")->first();
        $python = Skill::where("name->en", "Python")->first();

        $nick->skills()->attach($php->id, [
            "mastery" => 8, 
            "description" => "Always room for improvement"
        ]);
        $nick->skills()->attach($mysql->id, [
            "mastery" => 7, 
            "description" => "Don't ask me to write a join statement please"
        ]);
        $nick->skills()->attach($html->id, [
            "mastery" => 10, 
            "description" => "Good at creating skeletons"
        ]);
        $nick->skills()->attach($css->id, [
            "mastery" => 8, 
            "description" => "Always room for improvement"
        ]);
        $nick->skills()->attach($c->id, [
            "mastery" => 3, 
            "description" => "Always room for improvement"
        ]);
        $nick->skills()->attach($cpp->id, [
            "mastery" => 5, 
            "description" => "I can write a basic CLI program"
        ]);
        $nick->skills()->attach($cs->id, [
            "mastery" => 6, 
            "description" => "Intermediate"
        ]);
        $nick->skills()->attach($net->id, [
            "mastery" => 5, 
            "description" => "8 months of experience"
        ]);
        $nick->skills()->attach($python->id, [
            "mastery" => 8, 
            "description" => "I can charm the cobra"
        ]);
        
        // Assignments
        $traineeship = AssignmentType::where("name", "traineeship")->first();
        $ssc = Organization::where("name->en", "Shared Service Center ICT")->first();
        $assignment = Assignment::create([
            "user_id" => $nick->id,
            "assignment_type_id" => $traineeship->id,
            "organization_id" => $ssc->id,
            "organization_location_id" => $ssc->locations->get(0)->id,
            "organization_department_id" => $ssc->departments->get(0)->id,
            "title" => "Innovatie Manager",
            "description" => "De man die lang niet alles kan.",
            "order" => 1,
            "current" => true,
            "start_date" => now()->format("Y-m-d"),
        ]);
        $prevAssignment = Assignment::create([
            "user_id" => $nick->id,
            "assignment_type_id" => $traineeship->id,
            "organization_id" => $ssc->id,
            "organization_location_id" => $ssc->locations->get(0)->id,
            "organization_department_id" => $ssc->departments->get(1)->id,
            "title" => "Mobile Developer",
            "description" => "Mogen spelen met het Xamarin Framework, rijksbrede mobiele applicaties en een rijkshuisstijl voor websites.",
            "order" => 0,
            "current" => false,
            "start_date" => now()->format("Y-m-d"),
            "end_date" => now()->format("Y-m-d"),
        ]);

        // 
        // More admin accounts
        // 

        // $nick2 = User::create([
        //     "first_name" => "Nick",
        //     "last_name" => "Verheijen de Tweede",
        //     "email" => "verheijen.webdevelopment@gmail.com",
        //     "password" => bcrypt("engeland"),
        //     "is_admin" => true,
        // ]);
        // Users::generateAvatar($nick2);
        
        $victor = User::create([
            "first_name" => "Victor",
            "last_name" => "Gevers",
            "email" => "victor.gevers@minbzk.nl",
            "password" => bcrypt("Corona2020!"),
            "is_admin" => true,
        ]);
        // Users::generateAvatar($victor);

        $winko = User::create([
            "first_name" => "Winko",
            "last_name" => "van den Berg",
            "email" => "winko.erades@minbzk.nl",
            "password" => bcrypt("engeland"),
            "is_admin" => true,
        ]);
        // Users::generateAvatar($winko);
        
        $ramon = User::create([
            "first_name" => "Ramon",
            "last_name" => "Fiedler",
            "email" => "ramon.fiedler@minbzk.nl",
            "password" => bcrypt("Erkers2020"),
            "is_admin" => true,
        ]);
        // Users::generateAvatar($ramon);

        //
        // Dummy users
        //

        // for ($i = 0; $i < 10; $i++)
        // {
        //     $user = factory(User::class)->create();

        //     // Follow the almighty nick
        //     $user->follow($nick);
        //     if ($i < 2) $nick->follow($user);
        // }

        // // Generate avatars for all users
        // foreach (User::all() as $user)
        // {
        //     app()->make("users")->generateAvatar($user);
        // }

        // // Make the users follow eachother
        // $users = User::all();
        // foreach ($users as $user)
        // {
        //     foreach ($users->shuffle()->take(3) as $u)
        //     {
        //         if ($user->id != $u->id) $user->follow($u);
        //     }
        // }
    }
}
