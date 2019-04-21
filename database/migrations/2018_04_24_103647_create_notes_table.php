<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id')->comment('笔记表');
            $table->integer('user_id')->comment('笔记作者 id');
            $table->integer('course_id')->comment('课程ID');
            $table->integer('plan_id')->comment('教学版本ID');
            $table->integer('task_id')->comment('任务id');
            $table->text('content')->comment('内容');
            $table->boolean('is_public')->default(true)->comment('是否公开');
            $table->integer('collection')->default(0)->comment('被收藏数');
            $table->softDeletes();
            $table->timestamps();

            $table->index('plan_id');
            $table->index('user_id');
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
        Schema::dropIfExists('notes');
    }
}
