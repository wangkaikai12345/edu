<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id')->comment('教学版本评论');
            $table->integer('user_id')->comment('作者ID');
            $table->integer('course_id')->comment('课程ID');
            $table->integer('plan_id')->comment('教学版本ID');
            $table->text('content')->comment('内容');
            $table->integer('rating')->comment('评分');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('course_id');
            $table->index('plan_id');
            $table->index('rating');
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
        Schema::dropIfExists('reviews');
    }
}
