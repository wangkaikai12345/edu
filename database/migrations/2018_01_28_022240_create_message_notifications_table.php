<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessageNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_message_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('conversation_id')->comment('会话ID');
            $table->integer('message_id')->comment('消息ID');
            $table->integer('user_id')->comment('被提醒的人');
            $table->integer('is_seen')->commnet('是否已查看');
            $table->timestamps();

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
        Schema::drop('mc_message_notifications');
    }
}
