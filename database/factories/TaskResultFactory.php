<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\TaskResult::class, function (Faker $faker) {
    return [
        'course_id'=> 1,
        'plan_id' => 1,
        'task_id' => 1,
        'user_id' => 1,
        'finished_at' => null,
        'time' => random_int(1, 100),
        'status' => \App\Enums\TaskResultStatus::getRandomValue(),
    ];
});
