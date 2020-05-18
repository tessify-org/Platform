<?php

use App\Models\User;
use Tessify\Core\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("notifications")->delete();

        $nick = User::where("email", "nick.verheijen@minbzk.nl")->first();

        factory(Notification::class, 10)->create([
            "user_id" => $nick->id,
        ]);
    }
}
