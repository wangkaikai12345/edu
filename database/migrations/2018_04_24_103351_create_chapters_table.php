<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 32)->comment('章节标题');
            $table->integer('seq')->default(0)->comment('排序序号');
            $table->integer('course_id')->comment('课程 ID');
            $table->integer('plan_id')->comment('版本 ID');
            $table->integer('parent_id')->default(0)->comment('父级 ID');
            $table->integer('user_id')->comment('创建人');
            $table->integer('copy_id')->default(0)->comment('复制ID（用于班级的自定义章节）');
            // 学习目标 text
            $table->text('goals')->nullable()->comment('学习目标');
            // 预计时长 （小时单位）
            $table->integer('length')->default(0)->comment('预计时长，小时为单位');
            $table->timestamps();
            $table->softDeletes();

            $table->index('course_id');
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
        Schema::dropIfExists('chapters');
    }
}
