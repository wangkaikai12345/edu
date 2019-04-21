<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id')->comment('任务 ID');
            $table->integer('paper_result_id')->comment('所属试卷答题记录id');
            $table->integer('paper_id')->comment('测试 ID');
            $table->integer('question_id')->comment('题目 ID');
            $table->integer('user_id')->comment('用户 ID');
            // 客观题答案
            $table->string('objective_answer')->nullable()->comment('客观题答案');
            // 主观题答案
            $table->text('subjective_answer')->nullable()->comment('主观题答案');
            // 教师评语
            $table->text('explain')->nullable()->comment('教师评语');
            $table->string('status')->comment(''); // 未答题1 // 未批阅2 // 正确3 // 错误4
            $table->string('type')->comment('类型：单选single、多选multiple，主观 answer');
            $table->tinyInteger('rate')->default(1)->comment('难度评星：[1,2,3,4,5]');
            $table->unsignedInteger('score')->comment('题目得分');
            $table->timestamps();


            $table->index('user_id');
            $table->index('paper_id');
            $table->index('question_id');
            $table->index('task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_results');
    }
}
