<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Question::class, function (Faker $faker) {
    for ($i=1;$i<5;$i++) {
        $options[] = [
            'id' => $i, 'content' => $faker->word, 'type' => 'text'
        ];
    }
    return [
        'title' => $faker->sentence,
        'type' => \App\Enums\QuestionType::getRandomValue(),
        'options' => $options,
        'answers' => [1],
        'user_id' => 1,
        'course_id' => 1,
        'plan_id' => 1,
        'chapter_id' => 1,
        'difficulty' => random_int(1, 5),
        'explain' => $faker->paragraph,
    ];
});
