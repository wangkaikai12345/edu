<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->string('code', 36)->primary()->comment('优惠码');
            $table->string('batch', 36)->comment('批次码');
            $table->string('type')->comment('优惠类型');
            $table->integer('value')->comment('优惠类型对应的值');
            $table->timestamp('expired_at')->nullable()->comment('过期');
            $table->integer('user_id')->comment('创建人');
            $table->integer('consumer_id')->nullable()->comment('消费者');
            $table->timestamp('consumed_at')->nullable()->comment('消费时间');
            $table->integer('product_id')->nullable()->comment('适用商品ID');
            $table->string('product_type')->nullable()->comment('适用商品类型');
            $table->string('status', 16)->default(\App\Enums\CouponStatus::UNUSED)->comment('使用状态');
            $table->string('remark')->nullable()->comment('备注信息');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
