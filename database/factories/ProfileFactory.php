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

$factory->define(App\Models\Profile::class, function (Faker $faker) {
    $updated = $faker->dateTimeThisYear();
    return [
        'title' => $faker->word,
        'name' => $faker->name,
        'idcard' => random_int(100000000000000000, 600000000000000000),
        'gender' => \App\Enums\Gender::getRandomValue(),
        'birthday' => now()->toDateTimeString(),
        'city' => $faker->city,
        'qq' => $faker->numberBetween(10000, 9999999999),
        'about' => $faker->sentence(),
        'company' => $faker->company,
        'job' => $faker->jobTitle,
        'school' => $faker->company,
        'major' => $faker->word,
        'weibo' => $faker->url,
        'weixin' => $faker->url,
        'is_qq_public' => $faker->boolean,
        'is_weixin_public' => $faker->boolean,
        'is_weibo_public' => $faker->boolean,
        'site' => $faker->safeEmailDomain,
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated
    ];
});
