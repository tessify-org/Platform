<?php

use App\Models\Notification;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Notification::class, function (Faker $faker) {
    return [
        'title' => $faker->words(rand(3, 6), true),
        'description' => $faker->paragraphs(3, true),
    ];
});
