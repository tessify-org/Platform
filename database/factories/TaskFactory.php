<?php

use App\Models\Task;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => ucfirst($faker->words(rand(3, 6), true)),
        'description' => $faker->paragraphs(3, true),
        'complexity' => rand(1, 10),
        'estimated_hours' => rand(1, 40),
    ];
});
