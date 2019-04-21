<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Chapter::class, function ($faker) {
    return [
        'title' => $faker->word,
        'parent_id' => 0,
        'user_id' => 1,
        'course_id' => 1,
        'plan_id' => 1
    ];
});
