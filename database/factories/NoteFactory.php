<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Note::class, function (Faker $faker){
    $updated = $faker->dateTimeThisYear();
    return [
        'content' => $faker->text(191),
        'is_public' => $faker->boolean,
        'user_id' => 1,
        'course_id' => 1,
        'plan_id' => 1,
        'task_id' => 1,
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated,
    ];
});
