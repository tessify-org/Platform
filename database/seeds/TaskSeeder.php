<?php

use App\Models\User;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskCategory;
use App\Models\TaskSeniority;
use App\Models\Ministry;
use App\Models\Organization;

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("task_statuses")->delete();
        DB::table("task_categories")->delete();
        DB::table("task_seniorities")->delete();
        
        //
        // Statuses
        //

        $open = TaskStatus::create([
            "name" => "open",
            "label" => "Open",
        ]);
        $progress = TaskStatus::create([
            "name" => "in_progress",
            "label" => "In progress",
        ]);
        $completed = TaskStatus::create([
            "name" => "completed",
            "label" => "Completed",
        ]);

        $statuses = [$open, $progress, $completed];

        //
        // Categories
        //

        $programming = TaskCategory::create([
            "name" => "Programmeren",
        ]);
        $spellcheck = TaskCategory::create([
            "name" => "Spellcheck",
        ]);
        $review = TaskCategory::create([
            "name" => "Reviewen van stuk",
        ]);
        $translate = TaskCategory::create([
            "name" => "Vertalen",
        ]);

        $categories = [$programming, $spellcheck, $review, $translate];

        //
        // Seniorities
        //

        $junior = TaskSeniority::create([
            'name' => 'junior',
            'label' => 'Junior',
        ]);
        $medior = TaskSeniority::create([
            "name" => "medior",
            "label" => "Medior",
        ]);
        $senior = TaskSeniority::create([
            "name" => "senior",
            "label" => "Senior",
        ]);
        $expert = TaskSeniority::create([
            "name" => "expert",
            "label" => "Expert",
        ]);

        $seniorities = [$junior, $medior, $senior, $expert];

        //
        // Create tasks
        //

        $users = User::all();
        $skills = Skill::all();
        $ministries = Ministry::all();

        foreach (Project::all() as $project)
        {
            for ($i = 0; $i < rand(2, 5); $i++)
            {
                $status = $statuses[rand(0, (count($statuses) - 1))];
                $category = $categories[rand(0, (count($categories) - 1))];
                $seniority = $seniorities[rand(0, (count($seniorities) - 1))];

                
                $ministry = $ministries->random();
                $organization = $ministry->organizations->get(0);
                
                $ministry_id = $ministry->id;
                $organization_id = is_null($organization) ? null : $organization->id;
                if (rand(1,2) == 2)
                {
                    $ministry_id = null;
                    $organization_id = null;
                }

                $task = factory(Task::class)->create([
                    "author_id" => $users->random()->id,
                    "project_id" => $project->id,
                    "task_status_id" => $status->id,
                    "task_category_id" => $category->id,
                    "task_seniority_id" => $seniority->id,
                    "ministry_id" => $ministry_id,
                    "organization_id" => $organization_id,
                    "realized_hours" => $status->name == "completed" ? rand(1, 10) : 0,
                ]);

                if ($status->name == "in_progress" or $status->name == "completed")
                {
                    $user = $users->random();
                    $task->users()->attach([$user->id]);
                }

                $skill_ids = [];
                foreach ($skills->shuffle()->take(3) as $skill) $skill_ids[] = $skill->id;
                $task->skills()->attach($skill_ids);
            }
        }
    }
}
