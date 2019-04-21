<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Topic::class, function (Faker $faker) {
    $updated = $faker->dateTimeThisYear;
    return [
        'type' => \App\Enums\TopicType::getRandomValue(),
        'content' => $faker->paragraph,
        'is_stick' => $faker->boolean,
        'is_elite' => $faker->boolean,
        'title' => $faker->word,
        'user_id' => 1,
        'course_id' => 1,
        'plan_id' => 1,
        'task_id' => 1,
        'reply_num' => random_int(1, 100),
        'hit_count' => random_int(10, 300),
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated
    ];
});
