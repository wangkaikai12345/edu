<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户');
            $table->integer('follow_id')->comment('被关注的用户');
            $table->boolean('is_pair')->default(false)->comment('是否互相关注');
            $table->timestamps();

            $table->unique(['user_id', 'follow_id']);
            $table->index('follow_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
    }
}
