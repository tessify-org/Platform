<?php

use App\Models\User;
use App\Models\Group;
use App\Models\GroupRole;
use App\Models\GroupMember;
use App\Models\GroupMemberApplication;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("groups")->delete();
        DB::table("group_roles")->delete();
        DB::table("group_members")->delete();
        DB::table("group_member_applications")->delete();

        $moi = User::find(1);
        $toi = User::find(2);

        $group_one = Group::create([
            "founder_id" => $moi->id,
            "title" => "Awesomesauce",
            "description" => "Alleen voor awesome mensen",
        ]);

        $group_one_role_one = GroupRole::create([
            "name" => "Founder",
        ]);

        $group_one_member_founder = GroupMember::create([
            "user_id" => $moi->id,
            "group_id" => $group_one->id,
            "group_role_id" => $group_one_role_one->id,
        ]);

        $group_one_member_application = GroupMemberApplication::create([
            "user_id" => $toi->id,
            "group_id" => $group_one->id,
        ]);

        $group_one_role_two = GroupRole::create([
            "name" => "Second in command",
        ]);

        $group_two = Group::create([
            "founder_id" => $moi->id,
            "title" => "Anti-Awesome",
            "description" => "Wij zijn tegen awesomeheid. Foei."
        ]);

        $group_two_role_one = GroupRole::create([
            "name" => "Founder",
        ]);
    }
}
