<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 256)->comment('轮播图标题');
            $table->integer('seq')->default(0)->comment('轮播图显示顺序，数值越大，越先显示');
            $table->string('image')->comment('轮播图地址');
            $table->string('link')->nullable()->comment('点击图片跳转的目标网址');
            $table->string('description')->nullable()->comment('轮播图描述');
            $table->integer('user_id')->comment('创建者');
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('slides');
    }
}
