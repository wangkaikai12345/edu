<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content')->comment('内容');
            $table->integer('user_id')->nullable()->comment('内容');
            $table->string('email')->nullable()->commit('邮箱');
            $table->string('qq')->nullable()->commit('qq');
            $table->string('wechat')->nullable()->commit('微信');
            $table->tinyInteger('is_solved')->default(0)->commit('是否已解决。');
            $table->tinyInteger('is_replied')->default(0)->commit('是否已回复反馈人。');
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
        Schema::dropIfExists('feedback');
    }
}
