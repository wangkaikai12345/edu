<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('classroom_id');
            $table->integer('user_id');
            $table->string('remark')->nullable()->comment('备注');
            $table->string('type')->default(\App\Enums\StudentType::OFFICIAL)->comment('学员类型');
            $table->string('status')->default(\App\Enums\StudentStatus::BEGINNING)->comment('学员状态');
            // 已通关数
            $table->integer('pass_count')->default(0)->comment('已通关数(节的数量)');
            $table->integer('current_chap')->default(0)->comment('当前关的id');
            $table->integer('average_score')->default(0)->comment('平均分数');
            $table->integer('learned_count')->default(0)->comment('已学任务数');
            $table->integer('learned_compulsory_count')->default(0)->comment('已学必修任务数');
            $table->timestamp('deadline')->nullable()->comment('学习截止日期');
            $table->timestamp('finished_at')->nullable()->comment('完成时间');
            $table->timestamp('exited_at')->nullable()->comment('退出时间');
            $table->timestamp('last_learned_at')->nullable()->comment('最后一次学习日期');
            $table->timestamps();

            $table->index(['classroom_id', 'user_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classroom_members');
    }
}
