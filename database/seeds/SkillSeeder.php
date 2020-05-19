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
        
        Skill::create([
            "name" => [
                "nl" => "PHP",
                "en" => "PHP",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "MySQL",
                "en" => "MySQL",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "JavaScript",
                "en" => "JavaScript",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "HTML",
                "en" => "HTML",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "CSS",
                "en" => "CSS",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "C",
                "en" => "C",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "C++",
                "en" => "C++",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "C#",
                "en" => "C#",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => ".NET",
                "en" => ".NET",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "Python",
                "en" => "Python",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "R",
                "en" => "R",
            ],
        ]);

        //
        // Project management
        //

        Skill::create([
            "name" => [
                "nl" => "Scrum",
                "en" => "Scrum",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "Prince2",
                "en" => "Prince2",
            ],
        ]);
        Skill::create([
            "name" => [
                "nl" => "Project Management",
                "en" => "Project Management",
            ],
        ]);
    }
}
