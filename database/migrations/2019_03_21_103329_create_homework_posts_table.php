<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworkPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homework_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 64)->comment('作业标题');
            $table->integer('homework_id')->comment('所属作业');
            $table->integer('course_id')->comment('所属课程');
            $table->integer('plan_id')->comment('所属版本');
            $table->integer('task_id')->comment('所属任务');
            $table->integer('classroom_id')->nullable()->comment('所属班级');
            $table->string('package', 255)->nullable()->comment('提交的作业包');
            $table->text('code')->nullable()->comment('提交的代码');
            $table->text('post_img')->nullable()->comment('提交的图片');
            $table->text('description')->nullable()->comment('作业简介');
            $table->integer('user_id')->comment('提交人');
            $table->integer('teacher_id')->default(1)->comment('要批改的老师');
            $table->text('grades')->nullable()->comment('各项得分-数组');
            $table->integer('result')->nullable()->default(0)->comment('最终成绩');
            $table->text('student_review')->nullable()->comment('学生疑问');
            $table->text('teacher_review')->nullable()->comment('老师回复');
            $table->string('correct_media', 64)->nullable()->comment('批改视频');
            $table->string('status', 16)->default(\App\Enums\HomeworkPostStatus::READING)->comment('作业提交的状态');
            $table->string('locked', 16)->default(\App\Enums\HomeworkPostLocked::OPEN)->comment('是否启用');

            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('homework_posts');
    }
}
