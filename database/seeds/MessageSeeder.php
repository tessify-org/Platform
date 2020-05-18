<?php

use App\Models\User;
use Tessify\Core\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("messages")->delete();

        $nick = User::where("email", "nick.verheijen@minbzk.nl")->first();
        $others = User::where("email", "!=", "nick.verheijen@minbzk.nl")->get();

        for ($i = 0; $i < 25; $i++) {
            factory(Message::class)->create([
                "sender_id" => $others->random()->id,
                "receiver_id" => $nick->id,
            ]);
            factory(Message::class)->create([
                "sender_id" => $nick->id,
                "receiver_id" => $others->random()->id, 
            ]);
        }


    }
}
