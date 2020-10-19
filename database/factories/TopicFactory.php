<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Topic;
use Faker\Generator as Faker;

$factory->define(Topic::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'video' => $faker->randomElement(['1.mp4', '2.mp4']),
        'notes' => $faker->randomElement(['1.pdf', '2.pdf']),
    ];
});
