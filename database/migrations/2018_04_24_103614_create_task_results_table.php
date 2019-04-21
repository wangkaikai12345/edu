<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->comment('课程ID');
            // 班级ID 做班级的学习记录
            $table->integer('classroom_id')->default(0)->comment('班级ID');
            $table->integer('plan_id')->comment('版本ID');
            $table->integer('task_id')->comment('任务ID');
            $table->integer('user_id')->comment('用户ID');
            $table->string('status', 16)->default(\App\Enums\TaskResultStatus::START)->comment('任务状态');
            $table->integer('time')->default(0)->comment('单位：秒');
            $table->timestamp('finished_at')->nullable()->comment('任务完成时间');
            $table->timestamps();
            $table->softDeletes();

            $table->index('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_results');
    }
}
