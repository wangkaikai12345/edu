<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Course::class, function (Faker $faker) {
    $updated = $faker->dateTimeThisYear;
    return [
        'title' => $faker->word,
        'subtitle' => $faker->word,
        'summary' => $faker->sentence,
        'category_id' => 1,
        'goals' => $faker->words,
        'audiences' => $faker->words,
        'cover' => null,
        'status' => \App\Enums\Status::getRandomValue(),
        'serialize_mode' => \App\Enums\SerializeMode::getRandomValue(),
        'is_recommended' => $faker->boolean,
        'recommended_seq' => $faker->numberBetween(1, 1000),
        'recommended_at' => $faker->time(),
        'reviews_count' => $faker->numberBetween(1, 1000),
        'rating' => $faker->numberBetween(1, 5),
        'notes_count' => $faker->numberBetween(1, 1000),
        'students_count' => $faker->numberBetween(1, 1000),
        'hit_count' => $faker->numberBetween(1, 1000),
        'locked' => $faker->boolean,
        'min_course_price' => $faker->numberBetween(1, 1000),
        'max_course_price' => $faker->numberBetween(1, 1000),
        'default_plan_id' => 1,
        'user_id' => 1,
        'discount_id' => 0,
        'discount' => random_int(1, 100),
        'max_discount' => random_int(1, 100),
        'materials_count' => $faker->numberBetween(1, 1000),
        'category_first_level_id' => 0,
        'created_at' => $faker->dateTimeThisYear($updated),
        'updated_at' => $updated
    ];
});