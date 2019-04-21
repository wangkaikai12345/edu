<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_conversations', function (Blueprint $table) {
            $table->increments('id')->comment('仅支持1对1的会话');
            $table->integer('user_id')->comment('会话所属人');
            $table->integer('another_id')->comment('会话参与人');
            $table->integer('last_message_id')->nullable()->comment('最后一次消息ID');
            $table->string('uuid', 36)->comment('会话创建会产生两个相同会话，该字段标识其为同一会话。');
            $table->timestamps();

            $table->index('user_id');
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mc_conversations');
    }
}
