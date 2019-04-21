<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->increments('id')->comment('退款表');
            $table->string('title', 191)->comment('订单标题');
            $table->integer('order_id')->comment('订单ID');
            $table->string('status', 16)->default(\App\Enums\OrderStatus::CREATED)->comment('订单状态');
            $table->string('payment')->comment('第三方支付平台');
            $table->string('payment_sn', 64)->comment('第三方支付平台交易号');
            $table->integer('user_id')->comment('买家ID');
            $table->string('reason')->nullable()->comment('退款缘由');
            $table->string('currency')->default(\App\Enums\Currency::CNY)->comment('货币类型');
            $table->unsignedBigInteger('applied_amount')->comment('申请退款金额');
            $table->unsignedBigInteger('refunded_amount')->comment('实际退款金额');
            $table->text('payment_callback')->nullable()->comment('退款返回数据');
            $table->timestamp('handled_at')->nullable()->comment('处理时间');
            $table->integer('handler_id')->nullable()->comment('处理者ID');
            $table->string('handled_reason')->nullable()->comment('处理原因/驳回原因');
            $table->timestamps();
            $table->softDeletes();

            $table->index('title');
            $table->index('user_id');
            $table->index('order_id');
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
        Schema::dropIfExists('refunds');
    }
}
