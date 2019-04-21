<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 充值产品表：标题title、价格price、可兑换虚拟币coin、额外赠送虚拟币extra_coin、
        Schema::create('rechargings', function (Blueprint $table) {
            $table->increments('id')->comment('充值表');
            $table->string('title')->comment('标题');
            $table->unsignedBigInteger('price')->default(0)->comment('价格');
            $table->unsignedBigInteger('origin_price')->default(0)->comment('原价格');
            $table->unsignedBigInteger('coin')->default(0)->comment('等值虚拟币个数');
            $table->unsignedBigInteger('extra_coin')->default(0)->comment('额外赠送虚拟币个数');
            $table->unsignedBigInteger('income')->default(0)->comment('收益');
            $table->unsignedBigInteger('bought_count')->default(0)->comment('购买次数');
            $table->string('status')->default(\App\Enums\Status::DRAFT)->comment('状态');
            $table->integer('user_id')->comment('创建人');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rechargings');
    }
}
