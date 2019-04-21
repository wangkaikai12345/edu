<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Task::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'course_id' => 1,
        'plan_id' => 1,
        'chapter_id' => 1,
        'title' => $faker->word,
        'is_free'=>$faker->boolean,
        'is_optional' => $faker->boolean,
        'status' => $faker->randomElement(['draft', 'published', 'closed']),
        'length' => rand(1,10000),
        'seq' => 1,
        'started_at' => $faker->dateTime,
        'ended_at' => $faker->dateTime,
        'target_type' => \App\Enums\TaskTargetType::getRandomValue(),
        'target_id' => 1,
        'finish_type' => \App\Enums\FinishType::getRandomValue(),
        'finish_detail' => random_int(1, 100)
    ];
});
