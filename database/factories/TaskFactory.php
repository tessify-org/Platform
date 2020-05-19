<?php

use App\Models\Task;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Task::class, function (Faker $faker) {
    $title = ucfirst($faker->words(rand(3, 6), true));
    $desc = $faker->paragraphs(3, true);
    return [
        'title' => [
            'nl' => $title,
            'en' => $title,
        ],
        'description' => [
            'nl' => $desc,
            'en' => $desc,
        ],
        'complexity' => rand(1, 10),
        'estimated_hours' => rand(1, 40),
    ];
});
