<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Order::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'price_amount' => random_int(0, 10000),
        'pay_amount' => random_int(0, 10000),
        'currency' => \App\Enums\Currency::CNY,
        'user_id' => 1,
        'seller_id' => 0,
        'status' => \App\Enums\OrderStatus::CREATED,
        'trade_uuid' => $faker->uuid,
        'paid_amount' => 0,
        'paid_at' => $faker->dateTime,
        'payment' => \App\Enums\Payment::ALIPAY,
        'finished_at' => $faker->dateTime,
        'refund_deadline' => $faker->dateTime,
        'closed_user_id' => 1,
        'closed_message' => $faker->sentence,
        'closed_at' => $faker->dateTime,
        'product_id' => 1,
        'product_type' => 'App\Models\Plan',
        'coupon_code' => null,
        'coupon_type' => null
    ];
});
