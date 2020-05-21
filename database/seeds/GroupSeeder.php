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
            "name" => "Awesomesauce",
            "slogan" => [
                "nl" => "Awesomesauce is een manier van leven",
                "en" => "Awesomesauce is a way of life",
            ],
            "description" => [
                "nl" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus felis nisl, faucibus quis ligula vitae, placerat dictum sem. Mauris a laoreet mi, viverra porttitor ex. Pellentesque ac gravida velit, vitae sagittis turpis. Aenean eget ornare ligula. Morbi id hendrerit turpis. Suspendisse egestas sapien a condimentum accumsan. Pellentesque dapibus sodales ipsum. Donec facilisis luctus leo, eu semper est luctus vitae. In condimentum nunc tellus, at fringilla odio fringilla vel.\n\nVivamus ultricies quis ipsum sit amet faucibus. Etiam varius augue id sem pellentesque dictum eu sed neque. Fusce varius arcu elementum ante ultrices, ac blandit risus aliquet. Curabitur turpis risus, elementum non accumsan sed, facilisis nec justo. Donec nec dictum dui, nec porta mi. Suspendisse potenti. Vivamus sem erat, convallis ut porttitor quis, sodales non ex. Aenean dapibus, lorem efficitur suscipit suscipit, sapien tortor vulputate mi, scelerisque consequat quam risus porttitor arcu. Maecenas tempor convallis egestas. Duis vitae dui dignissim, tempor erat at, eleifend lacus. ",
                "en" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus felis nisl, faucibus quis ligula vitae, placerat dictum sem. Mauris a laoreet mi, viverra porttitor ex. Pellentesque ac gravida velit, vitae sagittis turpis. Aenean eget ornare ligula. Morbi id hendrerit turpis. Suspendisse egestas sapien a condimentum accumsan. Pellentesque dapibus sodales ipsum. Donec facilisis luctus leo, eu semper est luctus vitae. In condimentum nunc tellus, at fringilla odio fringilla vel.\n\nVivamus ultricies quis ipsum sit amet faucibus. Etiam varius augue id sem pellentesque dictum eu sed neque. Fusce varius arcu elementum ante ultrices, ac blandit risus aliquet. Curabitur turpis risus, elementum non accumsan sed, facilisis nec justo. Donec nec dictum dui, nec porta mi. Suspendisse potenti. Vivamus sem erat, convallis ut porttitor quis, sodales non ex. Aenean dapibus, lorem efficitur suscipit suscipit, sapien tortor vulputate mi, scelerisque consequat quam risus porttitor arcu. Maecenas tempor convallis egestas. Duis vitae dui dignissim, tempor erat at, eleifend lacus. ",
            ],
        ]);

        $group_one_role_one = GroupRole::create([
            "group_id" => $group_one->id,
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
            "motivation" => "Hoor er graag bij weetje",
        ]);

        $group_one_role_two = GroupRole::create([
            "group_id" => $group_one->id,
            "name" => "Second in command",
        ]);

        $group_two = Group::create([
            "founder_id" => $moi->id,
            "name" => "Anti-Awesome",
            "description" => [
                "nl" => "Wij zijn tegen awesomeheid. Foei.",
                "en" => "We are against awesomeness.",
            ],
        ]);

        $group_two_role_one = GroupRole::create([
            "group_id" => $group_two->id,
            "name" => "Founder",
        ]);
    }
}
