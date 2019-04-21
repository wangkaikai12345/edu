<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_results', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('classroom_id')->default(0)->comment('班级ID');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('plan_id')->comment('版本ID');
            $table->integer('chapter_id')->comment('节ID');
            // 进度（int）默认 1
            $table->integer('progress')->default(1)->comment('进度'); // 1测试 2开篇 3任务 4作业 5扩展
            // 分数
            $table->integer('score')->default(0)->comment('分数');
            // 完成用时
            $table->integer('time')->default(0)->comment('完成用时');
            // 状态（学习中， 作业待审批，已通关）
            $table->string('status', 16)->default(\App\Enums\ClassroomResultStatus::LEARN)->comment('任务状态');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classroom_results');
    }
}
