<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Review::class, function (Faker $faker){
    $updated = $faker->dateTimeThisYear();
    return [
        'user_id' => 1,
        'course_id' => 1,
        'plan_id' => 1,
        'content' => $faker->text(),
        'rating' => $faker->numberBetween(1, 5),
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated
    ];
});
