<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\TagGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->text(8),
        'description' => $faker->text(8),
        'tags_count' => random_int(1, 10),
    ];
});
