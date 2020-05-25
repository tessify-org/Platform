<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GovernmentSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(TaskSeeder::class);
        $this->call(MessageSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(SubscriptionSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(WhitelistedDomainSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(PollSeeder::class);
    }
}
