<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            // 如果无任何内容，不允许发布
            $table->increments('id');
            $table->integer('course_id')->comment('教学版本id');
            $table->string('course_title')->comment('课程标题');
            $table->string('title', 64)->comment('教学版本标题');
            $table->text('about')->nullable()->comment('教学版本简介');
            $table->string('learn_mode', 16)->default(\App\Enums\LearnMode::FREE)->comment('学习模式');
            $table->string('expiry_mode', 16)->default(\App\Enums\ExpiryMode::FOREVER)->comment('时效模式');
            $table->timestamp('expiry_started_at')->nullable()->comment('当 expiry_mode 为 period 时，该参数生效');
            $table->timestamp('expiry_ended_at')->nullable()->comment('当 expiry_mode 为 period 时，该参数生效');
            $table->integer('expiry_days')->default(0)->comment('进入班级后的有效学习天数');
            $table->text('goals')->nullable()->comment('课程目标');
            $table->text('audiences')->nullable()->comment('适应人群');
            $table->boolean('is_default')->default(true)->comment('是否默认教学版本');
            $table->integer('max_students_count')->default(0)->comment('最大学员数');
            // 预览视频 preview
            $table->string('preview')->nullable()->comment('预览视频');
            $table->string('status', 16)->default(\App\Enums\Status::DRAFT)->comment('发布状态');
            $table->boolean('is_free')->default(true)->comment('是否免费，默认不免费');
            $table->timestamp('free_started_at')->nullable()->comment('免费开始时间');
            $table->timestamp('free_ended_at')->nullable()->comment('免费结束时间');
            $table->text('services')->nullable()->comment('服务');
            $table->boolean('show_services')->default(0)->comment('展示承诺服务 是否在营销页展示服务承诺，默认展示');
            $table->boolean('enable_finish')->default(true)->comment('任务完成规则 是否允许学员强制完成任务，默认 允许');
            $table->integer('income')->default(0)->comment('总收入');
            $table->integer('origin_price')->default(0)->comment('原价');
            $table->bigInteger('price')->default(0)->comment('当前价格');
            $table->integer('origin_coin_price')->default(0)->comment('虚拟币原价');
            $table->integer('coin_price')->default(0)->comment('虚拟币当前价格');
            $table->boolean('locked')->default(false)->comment('是否上锁，默认不上锁');
            $table->boolean('buy')->default(true)->comment('是否允许购买');
            $table->string('serialize_mode', 16)->default(\App\Enums\SerializeMode::NONE)->comment('连载状态');
            $table->tinyInteger('max_discount')->default(100)->comment('最大抵扣百分比');
            $table->boolean('deadline_notification')->default(true)->comment('课程截止日期通知');
            $table->integer('notify_before_days_of_deadline')->default(1)->comment('限期前通知天数');
            $table->float('rating')->default(0)->comment('评分');
            $table->integer('reviews_count')->default(0)->comment('评价数量');
            $table->integer('tasks_count')->default(0)->comment('任务数');
            $table->integer('compulsory_tasks_count')->default(0)->comment('必修任务数');
            $table->integer('students_count')->default(0)->comment('学员数');
            $table->integer('notes_count')->default(0)->comment('笔记数量');
            $table->integer('hit_count')->default(0)->comment('点击数');
            $table->integer('topics_count')->default(0)->comment('话题数');
            $table->integer('user_id')->comment('创建人');
            $table->integer('copy_id')->default(0)->comment('复制ID（用于班级的自定义课程版本）');
            $table->string('copy_type')->default('plan')->comment('复制类型，版本复制或班级复制');
            $table->timestamps();
            $table->softDeletes();

            $table->index('course_title');
            $table->index('course_id');
            $table->index('price');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
