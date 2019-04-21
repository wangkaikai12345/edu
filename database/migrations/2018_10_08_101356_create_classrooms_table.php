<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 32)->comment('名称');
            $table->text('description')->nullable()->comment('简介');
            // 副标题
            $table->string('subtitle')->nullable()->comment('副标题');
            // 是否展示
            $table->boolean('is_show')->default(false)->comment('是否展示');
            // 是否可购买
            $table->boolean('is_buy')->default(false)->comment('是否可购买');
            // 虚拟币价格
            $table->integer('coin_price')->default(0)->comment('虚拟币现价格');
            // 虚拟币原价格
            $table->integer('origin_coin_price')->default(0)->comment('虚拟币原价格');
            // 服务（text）
            $table->text('services')->nullable()->comment('服务');
            $table->boolean('show_services')->default(false)->comment('是否展示服务，默认不展示');
            // 预览视频
            $table->string('preview')->nullable()->comment('预览视频');
            // seo关键字
            $table->text('seo')->nullable()->comment('seo关键字');
            // 视频倍速（默认）
            $table->integer('speed')->default(0)->comment('视频默认倍速');
            $table->string('status', 16)->default(\App\Enums\Status::DRAFT)->comment('状态');
            $table->string('expiry_mode', 16)->comment('有效天数/截止日期/永久有效');
            $table->timestamp('expiry_started_at')->nullable()->comment('时间范围开始日期');
            $table->timestamp('expiry_ended_at')->nullable()->comment('时间范围截止日期');
            $table->integer('expiry_days')->default(0)->comment('有效天数');
            $table->integer('category_id')->default(0)->comment('分类');
            $table->integer('price')->default(0)->comment('现价格');
            $table->integer('origin_price')->default(0)->comment('原价格');
            $table->string('cover')->nullable()->comment('封面图片');
            $table->boolean('is_recommended')->default(false)->comment('是否推荐');
            $table->timestamp('recommended_at')->nullable()->comment('推荐时间');
            $table->integer('recommended_seq')->default(0)->comment('推荐序号');
            $table->integer('members_count')->default(0)->comment('成员个数');
            $table->integer('courses_count')->default(0)->comment('课程个数');
            // 阶段数（章的数量）
            // 模式（手动，通关，全部）
            $table->string('learn_mode', 16)->default(\App\Enums\ClassroomMode::HAND)->comment('学习模式');

            $table->integer('user_id')->comment('创建人');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
}
