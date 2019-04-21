<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Audio::class, function (Faker $faker) {
    return [
        'media_uri' => 'audio/' . $faker->date('Y-m-d') . '-' . str_random(8),
        'hash' => str_random(36),
        'length' => random_int(1, 1000),
    ];
});
