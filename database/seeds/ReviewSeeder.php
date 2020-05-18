<?php

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Review;
use App\Models\ReviewRequest;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("reviews")->delete();
        DB::table("review_requests")->delete();

        // // Grab me myself & i
        // $moi = User::find(1);
        // $toi = User::find(2);

        // // Grab all tasks & projects
        // $tasks = Task::all();
        // $projects = Project::all();

        // // Create a review request for a random project
        // $p_one = $projects->random();
        // ReviewRequest::create([
        //     "user_id" => $moi->id,
        //     "reviewrequestable_type" => get_class($p_one),
        //     "reviewrequestable_id" => $p_one->id,
        //     "reason" => "completed_project",
        // ]);

        // // Create reviews for all projects from moi
        // foreach ($projects as $project)
        // {
        //     Review::create([
        //         "user_id" => $moi->id,
        //         "reviewable_type" => get_class($project),
        //         "reviewable_id" => $project->id,
        //         "rating" => 10,
        //         "message" => "It was very awesome to work on this project.",
        //         "public" => true,
        //     ]);
        // }

        // // Create a review request for a random task
        // $t_one = $tasks->random();
        // ReviewRequest::create([
        //     "user_id" => $moi->id,
        //     "reviewrequestable_type" => get_class($t_one),
        //     "reviewrequestable_id" => $t_one->id,
        //     "reason" => "completed_task",
        // ]);

        // // Create a review of a user
        // Review::create([
        //     "user_id" => $moi->id,
        //     "reviewable_type" => get_class($toi),
        //     "reviewable_id" => $toi->id,
        //     "rating" => 10,
        //     "message" => "Great guy",
        // ]);

        // // Create a review of a task
        // $t_two = $tasks->random();
        // Review::create([
        //     "user_id" => $moi->id,
        //     "reviewable_type" => get_class($t_two),
        //     "reviewable_id" => $t_two->id,
        //     "rating" => 8,
        //     "message" => "Great task",
        // ]);

        // // Create a review of a project
        // $p_two = $projects->random();
        // Review::create([
        //     "user_id" => $moi->id,
        //     "reviewable_type" => get_class($p_two),
        //     "reviewable_id" => $p_two->id,
        //     "rating" => 6,
        //     "message" => "Medium project this one."
        // ]);
    }
}
