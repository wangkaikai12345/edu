<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('classroom_id');
            $table->integer('course_id');
            $table->integer('plan_id');
            $table->integer('seq')->default(0)->comment('排序');
            $table->boolean('is_synced')->default(true)->comment('是否同步课程');
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
        Schema::dropIfExists('classroom_courses');
    }
}
