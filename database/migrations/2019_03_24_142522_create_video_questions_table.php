<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paper_id')->comment('考试id');
            $table->integer('video_id')->comment('视频id');
            $table->integer('video_time')->comment('视频播放时间');
            $table->integer('pattern')->default(1)->comment('弹题模式');
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
        Schema::dropIfExists('video_questions');
    }
}
