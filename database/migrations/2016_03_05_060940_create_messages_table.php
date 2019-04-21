<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 当发送私信时，默认存入两条数据。这是为了兼容一方删除，另一方未删除的状况。
         */
        Schema::create('mc_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('conversation_id')->comment('会话ID');
            $table->integer('sender_id')->comment('发送人');
            $table->integer('recipient_id')->comment('接收人');
            $table->text('body')->comment('具体消息内容');
            $table->string('type')->default('text')->comment('类型：文本');
            $table->string('uuid', 36)->comment('一条私信，产生两条数据，该字段用于表示为同一消息');
            $table->timestamps();

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
        Schema::drop('mc_messages');
    }
}
