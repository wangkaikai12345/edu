<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Reply::class, function (Faker $faker) {
    $updated = $faker->dateTimeThisYear;
    return [
        'is_elite' => $faker->boolean,
        'content' => $faker->paragraph,
        'user_id' => 1,
        'course_id' => 1,
        'plan_id' => 1,
        'topic_id' => 1,
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated,
    ];
});
