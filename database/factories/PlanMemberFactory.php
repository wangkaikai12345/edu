<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\PlanMember::class, function (Faker $faker){
    $updated = $faker->dateTimeThisYear();
    return [
        'deadline' => $faker->dateTimeThisYear,
        'learned_count' => random_int(0, 10),
        'learned_compulsory_count' => random_int(0, 10),
        'notes_count' => random_int(0, 10),
        'is_finished' => $faker->boolean,
        'remark' => $faker->text(191),
        'last_learned_at' => $faker->dateTimeThisYear,
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated,
        'course_id' => 1,
        'plan_id' => 1,
        'user_id' => 1,
        'join_type' => \App\Enums\JoinType::getRandomValue(),
        'order_id' => 1,
        'credit' => 100,
        'note_last_updated_at' => $faker->dateTime,
        'finished_at' => $faker->dateTime,
        'locked' => $faker->boolean,
    ];
});
