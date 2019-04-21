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

$factory->define(App\Models\User::class, function (Faker $faker) {
    $updated = $faker->dateTimeThisYear();
    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$LaEmGV3wGTG49ewBi5Ao/uPsFeJufimaE8gmNx9N6i6rem.KaHhrm', // 123456
        'remember_token' => str_random(10),
        'invitation_code' => str_random(6),
    ];
});
