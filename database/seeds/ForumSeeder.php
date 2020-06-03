<?php

use App\Models\User;
use App\Models\Group;
use App\Models\Forum;
use App\Models\ForumThread;
use App\Models\ForumThreadPost;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("forums")->delete();
        DB::table("forum_threads")->delete();
        DB::table("forum_thread_posts")->delete();

        $me = User::find(1);

        //
        // Create general forum
        //

        // Generate general forum
        $general = Forum::create([
            "title" => "General forum",
            "editable" => false,
            "deletable" => false,
        ]);
        
        // Create rules thread
        $general_rules_thread = ForumThread::create([
            "forum_id" => $general->id,
            "user_id" => $me->id,
            "title" => "Huisregels",
            "message" => "Hier volgen een aantal huisregels: wees lief.",
            "sticky" => true,
            "order" => 0,
        ]);
        
        // Create introduction thread
        $general_intros_thread = ForumThread::create([
            "forum_id" => $general->id,
            "user_id" => $me->id,
            "title" => "Introducties",
            "message" => "Welkom! In deze thread kan je je voorstellen aan iedereen op het platform. Wie ben je en wat doe je?",
            "sticky" => true,
            "order" => 1,
        ]);

        //
        // Create group forums
        //

        foreach (Group::all() as $group)
        {
            Forum::create([
                "forumable_type" => get_class($group),
                "forumable_id" => $group->id,
                "title" => "Group forum",
                "editable" => false,
                "deletable" => false,
            ]);
        }
    }
}
