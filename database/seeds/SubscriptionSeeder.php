<?php

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Ministry;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $users = User::all();
        // $ministries = Ministry::all();
        // $organizations = Organization::all();
        // $projects = Project::all();
        // $tasks = Task::all();

        // foreach ($users as $user)
        // {
        //     foreach ($ministries->shuffle()->take(3) as $ministry)
        //     {
        //         $user->subscribe($ministry);
        //     }

        //     foreach ($organizations->shuffle()->take(3) as $organization)
        //     {
        //         $user->subscribe($organization);
        //     }

        //     foreach ($projects->shuffle()->take(3) as $project)
        //     {
        //         $user->subscribe($project);
        //     }

        //     foreach ($tasks->shuffle()->take(3) as $task)
        //     {
        //         $user->subscribe($task);
        //     }
        // }
    }
}
