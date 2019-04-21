<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaperResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id')->comment('任务 ID');
            $table->integer('plan_id')->comment('版本 ID');
            $table->string('paper_title')->nullable()->comment('试卷名称-冗余字段');
            $table->integer('paper_id')->comment('试卷 ID');
            $table->integer('user_id')->comment('用户 ID');
            $table->string('mark_type')->default('auto')->comment('批阅类型 auto自动 teacher老师批阅');
            // 考试分数
            $table->integer('score')->default(0)->comment('考试分数');
            // 及格分数
            $table->integer('pass_score')->default(0)->comment('及格分数');
            // 学生分数
            $table->integer('answer_score')->default(0)->comment('答题分数');
            $table->integer('time')->default(0)->comment('考试用时');
            $table->timestamp('start_at')->nullable()->comment('学员进入考试的开始时间');
            $table->timestamp('end_at')->nullable()->comment('学员考试结束的时间');
            $table->tinyInteger('right_count')->default(0)->comment('正确个数');
            // 错误个数
            $table->tinyInteger('false_count')->default(0)->comment('错误个数');
            // 状态 （未阅卷，阅卷）
            $table->integer('is_mark')->default(0)->comment('状态 （未阅卷，阅卷）');
            // 阅卷人
            $table->integer('reader_id')->comment('阅卷人');

            $table->tinyInteger('finished_count')->default(0)->comment('答完个数');
            $table->tinyInteger('is_finished')->default(0)->comment('是否已完成此次考试');

            $table->timestamps();
            $table->index(['task_id', 'paper_id', 'user_id']);
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
        Schema::dropIfExists('paper_results');
    }
}
