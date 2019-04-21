<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->increments('id')->comment('支付交易表');
            $table->string('title', 191)->comment('订单标题');
            $table->integer('order_id')->comment('订单ID');
            $table->string('trade_uuid', 32)->comment('订单交易号');
            $table->string('status', 16)->default(\App\Enums\OrderStatus::CREATED)->comment('订单状态');
            $table->string('currency', 16)->default(\App\Enums\Currency::CNY)->comment('货币类型');
            $table->unsignedBigInteger('paid_amount')->comment('实付金额');
            $table->integer('seller_id')->default(0)->comment('卖家ID');
            $table->integer('user_id')->comment('买家ID');
            $table->string('type', 16)->default(\App\Enums\TradeType::PURCHASE)->comment('交易类型');
            $table->string('payment')->comment('第三方支付平台');
            $table->string('payment_sn')->comment('第三方支付平台交易号');
            $table->text('payment_callback')->nullable()->comment('第三方支付平台回调数据');
            $table->timestamp('paid_at')->nullable()->comment('交易时间');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trades');
    }
}
