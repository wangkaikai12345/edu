<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Refund::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'order_id' => 1,
        'status' => \App\Enums\OrderStatus::CREATED,
        'payment' => \App\Enums\Payment::ALIPAY,
        'payment_sn' => $faker->uuid,
        'user_id' => 1,
        'reason' => $faker->text(20),
        'currency' => \App\Enums\Currency::CNY,
        'applied_amount' => random_int(1, 1000),
        'refunded_amount' => random_int(1, 1000),
        'handled_at' => $faker->dateTimeThisYear(),
        'handler_id' => 1,
        'handled_reason' => $faker->text(20),
    ];
});
