<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->comment('订单表');
            $table->string('title', 191)->comment('订单标题');
            $table->unsignedBigInteger('price_amount')->comment('订单金额');
            $table->unsignedBigInteger('pay_amount')->comment('应付金额');
            $table->string('currency')->default(\App\Enums\Currency::CNY)->comment('货币类型');
            $table->integer('user_id')->comment('买家ID');
            $table->integer('seller_id')->default(0)->comment('卖家ID');
            $table->string('status', 16)->default(\App\Enums\OrderStatus::CREATED)->comment('订单状态');
            $table->string('trade_uuid', 32)->comment('支付交易号，生成订单时创建');
            $table->unsignedBigInteger('paid_amount')->nullable()->comment('最终付款的金额，支付成功后记录');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->string('payment', 32)->nullable()->comment('第三方支付平台，支付成功后记录');
            $table->timestamp('finished_at')->nullable()->comment('订单完成时间（超过退款期限自动进行结束）');
            $table->integer('closed_user_id')->nullable()->comment('关闭交易的用户ID');
            $table->string('closed_message')->nullable()->comment('订单关闭原因');
            $table->timestamp('closed_at')->nullable()->comment('订单关闭时间');
            $table->timestamp('refund_deadline')->nullable()->comment('申请退款截止日期');
            $table->nullableMorphs('product');
            $table->string('coupon_code')->nullable()->comment('优惠码');
            $table->string('coupon_type')->nullable()->comment('优惠类型');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('trade_uuid');
            $table->index('price_amount');
            $table->index('pay_amount');
            $table->index('user_id');
            $table->index('closed_user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
