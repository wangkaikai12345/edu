<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->comment('课程ID');
            $table->integer('plan_id')->comment('版本ID');
            $table->integer('chapter_id')->comment('节ID');
            $table->string('title')->comment('任务标题');
            $table->string('status', 16)->default(\App\Enums\Status::DRAFT)->comment('发布状态');
            $table->boolean('is_free')->default(false)->comment('是否免费，默认不免费');
            $table->boolean('is_optional')->default(false)->comment('是否选修');
            // 关键词（选填）
            $table->text('keyword')->nullable()->comment('关键词');
            // 描述（选填）
            $table->text('describe')->nullable()->comment('描述');
            $table->string('type')->comment('任务类型'); // 教学类型
            $table->integer('user_id')->comment('创建人');
            $table->integer('seq')->default(0)->comment('序号，自动升序排序');
            $table->timestamp('started_at')->nullable()->comment('开始时间');
            $table->timestamp('ended_at')->nullable()->comment('结束时间');
            $table->morphs('target'); // 资源类型
            $table->integer('length')->default(0)->comment('时长'); // 其他类型必填，除视频
            $table->string('finish_type', 16)->default(\App\Enums\FinishType::END)->comment('任务完成类型');
            $table->integer('finish_detail')->default(0)->comment('指定任务完成时间');
            $table->integer('copy_id')->default(0)->comment('复制ID（用于班级的自定义任务）');
            $table->timestamps();
            $table->softDeletes();

            $table->index('course_id');
            $table->index('plan_id');
            $table->index('chapter_id');
            $table->index('title');
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
        Schema::dropIfExists('tasks');
    }
}
