<?php

use App\Models\Group;
use App\Models\GroupMember;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => ucfirst(strtolower($faker->word)),
        'description' => $faker->paragraphs(3, true),
    ];
});

$factory->define(GroupRole::class, function(Faker $faker) {
    return [
        'name' => ucfirst(strtolower($faker->word))
    ];
});