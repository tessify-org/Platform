<?php

use App\Models\Message;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'subject' => $faker->words(rand(3, 6), true),
        'message' => $faker->paragraphs(3, true),
    ];
});
