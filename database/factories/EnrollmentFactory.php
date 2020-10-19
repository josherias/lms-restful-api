<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enrollment;
use App\Instructor;
use App\User;
use Faker\Generator as Faker;

$factory->define(Enrollment::class, function (Faker $faker) {

    $instructor = Instructor::has('courses')->get()->random();
    $student = User::all()->except($instructor->id)->random();

    return [
        'student_id' => $student->id,
        'course_id' => $instructor->courses->random()->id,
    ];
});
