<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Test::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'course_id' => 1,
        'user_id' => 1
    ];
});
