<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media_uri')->comment('媒体文件地址');
            $table->string('hash')->comment('文件哈希（用户秒传）');
            $table->integer('length')->default(0)->comment('视频长度，单位：秒');
            $table->string('status', 16)->default(\App\Enums\VideoStatus::UNSLICED)->comment('切片状态');
            // 视频默认倍速
            $table->integer('speed')->default(0)->comment('视频默认倍速');
            $table->timestamps();
            $table->softDeletes();

            $table->index('media_uri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
