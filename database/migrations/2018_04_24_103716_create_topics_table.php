<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // title content status is_audited user_id course_id plan_id task_id replies_count hit_count
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id')->comment('话题/问答');
            $table->string('type', 16)->default(\App\Enums\TopicType::DISCUSSION)->comment('话题类型');
            $table->string('title')->comment('标题');
            $table->text('content')->comment('内容');
            $table->boolean('is_stick')->default(false)->comment('是否置顶，默认不置顶');
            $table->boolean('is_elite')->default(false)->comment('是否是精华，默认不是');
            $table->boolean('is_audited')->default(false)->comment('是否审核');
            $table->integer('user_id')->comment('作者ID');
            $table->integer('course_id')->comment('课程ID');
            $table->integer('plan_id')->comment('版本ID');
            $table->integer('task_id')->nullable()->comment('任务ID');
            $table->integer('replies_count')->default(0)->comment('回复量');
            $table->integer('hit_count')->default(0)->comment('点击量');
            $table->integer('latest_replier_id')->nullable()->comment('最后回复人ID');
            $table->timestamp('latest_replied_at')->nullable()->comment('最后回复时间');
            $table->string('status', 16)->default(\App\Enums\TopicStatus::QUALIFIED)->comment('正常、违规');
            $table->timestamps();

            $table->index('user_id');
            $table->index('course_id');
            $table->index('plan_id');
            $table->index('latest_replier_id');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
