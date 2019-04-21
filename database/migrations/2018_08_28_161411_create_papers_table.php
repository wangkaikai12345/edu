<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {

            $table->increments('id');
            $table->string('title')->comment('试卷题目');
            // 预计用时
            $table->integer('expect_time')->default(0)->comment('预计用时');
            $table->integer('user_id')->comment('创建人');
            $table->integer('questions_count')->default(0)->comment('题目个数');
            $table->integer('total_score')->default(0)->comment('总分');
            // 状态
            $table->string('status')->default(\App\Enums\PaperStatus::VALID)->comment('试卷状态');
            // 类型
            $table->string('type')->default('test')->comment('试卷状态');
            // 备注
            $table->text('extra')->nullable()->comment('备注');
            // 及格分数
            $table->integer('pass_score')->default(0)->comment('及格分数');
            $table->integer('single_count')->default(0)->comment('单选个数');
            $table->integer('multiple_count')->default(0)->comment('多选个数');
            $table->integer('answer_count')->default(0)->comment('回答个数');
            $table->integer('copy_id')->default(0)->comment('复制ID（用于班级的自定义任务）');

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
        Schema::dropIfExists('papers');
    }
}
