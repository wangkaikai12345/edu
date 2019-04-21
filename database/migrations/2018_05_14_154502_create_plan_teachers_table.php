<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->comment('课程ID');
            $table->integer('plan_id')->comment('教学版本ID');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('seq')->default(0)->comment('排序');
            $table->integer('is_show')->default(1)->comment('是否显示 0不显示 1显示');
            $table->timestamps();

            $table->index('course_id');
            $table->index('plan_id');
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
        Schema::dropIfExists('plan_teachers');
    }
}
