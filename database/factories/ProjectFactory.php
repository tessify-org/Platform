<?php

use Carbon\Carbon;
use App\Models\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        "title" => $faker->words(rand(2, 3), true),
        "slogan" => $faker->sentence,
        "description" => $faker->paragraph(10, true),
        "starts_at" => Carbon::now()->format("Y-m-d"),
        "ends_at" => Carbon::now()->addQuarter()->format("Y-m-d")
    ];
});