<?php

use App\Models\Skill;

use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("skills")->delete();
        
        //
        // Programming 
        //
        
        Skill::create(["name" => "PHP"]);
        Skill::create(["name" => "MySQL"]);
        Skill::create(["name" => "JavaScript"]);
        Skill::create(["name" => "HTML"]);
        Skill::create(["name" => "CSS"]);
        Skill::create(["name" => "C"]);
        Skill::create(["name" => "C++"]);
        Skill::create(["name" => "C#"]);
        Skill::create(["name" => ".NET"]);
        Skill::create(["name" => "Python"]);
        Skill::create(["name" => "R"]);

        //
        // Project management
        //

        Skill::create(["name" => "Scrum"]);
        Skill::create(["name" => "Prince2"]);
        Skill::create(["name" => "Project Management"]);
    }
}
