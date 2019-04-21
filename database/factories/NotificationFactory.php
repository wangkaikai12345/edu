<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Notification::class, function (Faker $faker) {
    $created_at = $updated_at = $faker->dateTimeThisYear();

    return [
        'id' => $faker->uuid,
        'type' => $faker->text(8),
        'data' => [
            'title' => '欢迎来到智校云系统',
            'content' => '你好，欢迎来到<em>智校云</em>系统。'
        ],
        'read_at' => null,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
