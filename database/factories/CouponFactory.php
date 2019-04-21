<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Coupon::class, function (Faker $faker) {
    switch ($type = \App\Enums\CouponType::getRandomValue()) {
        case \App\Enums\CouponType::DISCOUNT:
            $discount = random_int(1, 10);
            break;
        case \App\Enums\CouponType::VOUCHER:
            $amount = random_int(100, 10000);
            break;
        case \App\Enums\CouponType::AUDITION:
            $audition = now()->addDay();
            break;
    }
    return [
        'code' => $faker->uuid,
        'batch' => $faker->uuid,
        'type' => $type,
        'value' => 10,
        'expired_at' => now()->addDay(),
        'user_id' => $plan_id ?? 0,
        'consumer_id' => null,
        'consumed_at' => null,
        'status' => \App\Enums\CouponStatus::UNUSED,
        'remark' => $audition ?? now()->addDay()
    ];
});
