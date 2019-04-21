<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media_uri')->comment('媒体文件地址');
            $table->string('hash')->comment('文件哈希（用户秒传）');
            $table->integer('length')->default(0)->comment('大小，单位：byte');
            $table->timestamps();

            $table->unique('hash');
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
        Schema::dropIfExists('images');
    }
}
