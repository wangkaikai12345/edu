<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->text(8),
        'icon' => $faker->imageUrl(24, 24),
        'path' => $faker->text(8),
        'seq' => random_int(0, 10000)
    ];
});
