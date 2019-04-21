<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->increments('id')->comment('话题/问答/任务回复表');
            $table->text('content')->comment('评论内容');
            $table->string('status', 16)->default(\App\Enums\TopicStatus::QUALIFIED)->comment('合格、违规');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('course_id')->comment('课程ID');
            $table->integer('plan_id')->nullable()->comment('版本ID');
            $table->integer('task_id')->nullable()->comment('任务ID');
            $table->integer('topic_id')->comment('话题/问答ID');
            $table->boolean('is_elite')->default(false)->comment('是否是精华，默认 否');
            $table->timestamps();

            $table->index('user_id');
            $table->index('course_id');
            $table->index('plan_id');
            $table->index('topic_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
