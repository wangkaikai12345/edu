<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Favorite::class, function (Faker $faker) {
    $updated = $faker->dateTimeThisYear();
    return [
        'target_id' => 1,
        'target_type' => 'App\Models\Course',
        'user_id' =>  1,
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated
    ];
});
