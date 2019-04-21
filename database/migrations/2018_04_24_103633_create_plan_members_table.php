<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->comment('课程 ID');
            $table->integer('plan_id')->comment('教学版本 ID');
            $table->integer('user_id')->comment('用户 ID');
            $table->integer('order_id')->nullable()->comment('订单ID');
            $table->timestamp('deadline')->nullable()->comment('学习截止日期');
            $table->string('join_type', 16)->comment('加入方式');
            $table->integer('learned_count')->default(0)->comment('已学任务数');
            $table->integer('learned_compulsory_count')->default(0)->comment('已学习的必修任务数量');
            $table->integer('credit')->default(0)->comment('学分');
            $table->integer('notes_count')->default(0)->comment('笔记数量');
            $table->timestamp('note_last_updated_at')->nullable()->comment('最新的笔记更新时间');
            $table->boolean('is_finished')->default(false)->comment('是否已学完，默认未学完');
            $table->timestamp('finished_at')->nullable()->comment('学习完成时间');
            $table->string('status')->default(\App\Enums\StudentStatus::BEGINNING)->comment('学员状态');
            $table->string('remark')->nullable()->comment('备注');
            $table->timestamp('last_learned_at')->nullable()->comment('最后一次学习日期');
            $table->softDeletes();
            $table->timestamps();

            $table->index('course_id');
            $table->index('plan_id');
            $table->index('user_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_members');
    }
}
