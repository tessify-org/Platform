<?php

use Carbon\Carbon;
use App\Models\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
    ];
});