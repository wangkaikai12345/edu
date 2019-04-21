<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 64)->comment('课程标题');
            $table->string('subtitle')->nullable()->comment('课程副标题');
            $table->text('summary')->nullable()->comment('课程简介');
            $table->integer('category_id')->nullable()->comment('分类ID');
            $table->text('goals')->nullable()->comment('课程目标');
            $table->text('audiences')->nullable()->comment('目标人群');
            $table->string('cover')->nullable()->comment('封面图片');
            // 前台是否允许购买
            $table->string('status', 16)->default(\App\Enums\Status::DRAFT)->comment('发布状态');
            $table->string('serialize_mode')->default(\App\Enums\SerializeMode::NONE)->comment('连载状态');
            $table->boolean('is_recommended')->default(false)->comment('是否推荐');
            $table->integer('recommended_seq')->default(0)->comment('推荐序号');
            $table->timestamp('recommended_at')->nullable()->comment('推荐日期');
            $table->integer('hit_count')->default(0)->comment('课程点击数');
            // 是否允许更新
            $table->boolean('locked')->default(false)->comment('是否锁住，默认不锁住');
            $table->integer('min_course_price')->default(0)->comment('已发布教学版本的最低价格');
            $table->integer('max_course_price')->default(0)->comment('已发布教学版本的最高价格');
            $table->integer('default_plan_id')->default(0)->comment('默认的教学版本 id');
            $table->integer('discount_id')->default(0)->comment('折扣活动ID');
            $table->float('discount')->default(0)->comment('折扣');
            $table->float('max_discount')->default(0)->comment('最大抵扣百分比');
            $table->integer('materials_count')->default(0)->comment('上传的资料数量');
            $table->integer('reviews_count')->default(0)->comment('课程评论次数');
            $table->float('rating')->default(0)->comment('课程评分');
            $table->integer('notes_count')->default(0)->comment('课程笔记数');
            $table->integer('students_count')->default(0)->comment('课程学员数');
            $table->integer('user_id')->comment('创建人');
            $table->integer('category_first_level_id')->default(0)->comment('一级分类ID（非分类群组），用于首页的选项卡导航');
            $table->integer('copy_id')->default(0)->comment('复制ID（用于班级的自定义课程）');
            $table->timestamps();
            $table->softDeletes();

            $table->index('title');
            $table->index('category_id');
            $table->index('min_course_price');
            $table->index('max_course_price');
            $table->index('default_plan_id');
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
        Schema::dropIfExists('courses');
    }
}
