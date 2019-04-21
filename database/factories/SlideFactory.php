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

$factory->define(App\Models\Slide::class, function (Faker $faker) {
    $updated = $faker->dateTimeThisYear();
    return [
        'title' => $faker->word,
        'seq' => random_int(0, 100),
        'image' => $faker->imageUrl(1920, 450),
        'link' => $faker->url,
        'description' => $faker->sentence(),
        'user_id' => 1,
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated
    ];
});
