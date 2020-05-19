<?php

use Carbon\Carbon;
use App\Models\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    $slogan = $faker->sentence;
    $desc = $faker->paragraph(10, true);
    return [
        "title" => $faker->words(rand(2, 3), true),
        "slogan" => [
            "nl" => $slogan,
            "en" => $slogan,
        ],
        "description" => [
            "nl" => $desc,
            "en" => $desc,
        ],
        "starts_at" => Carbon::now()->format("Y-m-d"),
        "ends_at" => Carbon::now()->addQuarter()->format("Y-m-d")
    ];
});