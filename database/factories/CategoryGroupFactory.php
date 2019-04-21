<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\CategoryGroup::class, function (Faker $faker) {
    return [
        'name' => str_random(4)
    ];
});
