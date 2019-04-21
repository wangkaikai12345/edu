<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->comment(' 题目标题');
            $table->string('type')->comment('类型：单选single、多选multiple，主观 answer');
            $table->tinyInteger('rate')->default(1)->comment('难度评星：[1,2,3,4,5]');
            $table->text('options')->nullable()->comment("选项：[{id:1,title:'哈哈哈',type='text'}]");
            $table->string('answers')->nullable()->comment('答案：1,2,3。排序好ID数组');
            $table->integer('user_id')->comment('创建人');
            $table->text('explain')->nullable()->comment('题目解析');
            // 状态
            $table->string('status')->default(\App\Enums\QuestionStatus::VALID)->comment('题目状态');
            // 正确次数
            $table->string('right_count')->default(0)->comment('正确次数');
            // 错误次数
            $table->string('false_count')->default(0)->comment('错误次数');

            $table->timestamps();
            $table->softDeletes();
            
            $table->index('type');
            $table->index('rate');
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
        Schema::dropIfExists('questions');
    }
}
