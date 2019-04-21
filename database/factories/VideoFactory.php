<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Video::class, function (Faker $faker) {
    return [
        'media_uri' => 'Fjac_tqowttfkdGMbjSQASFbdffj/',
        'hash' => str_random(36),
        'length' => random_int(1, 1000),
        'status' => \App\Enums\VideoStatus::getRandomValue(),
    ];
});
