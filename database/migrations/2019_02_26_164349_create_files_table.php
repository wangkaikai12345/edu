<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('origin_name')->comment('文件源名称');
            $table->string('name')->comment('文件名称');
            $table->string('hash')->comment('文件Hash值');
            $table->string('url')->comment('文件地址');
            $table->integer('status')->default(0)->comment('文件状态 0为未使用 1为使用中');
            $table->string('task_id')->comment('任务id');
            $table->string('user_id')->comment('上传人id');
            $table->string('mime_type', 50)->nullable()->default(null)->comment('文件mime类型');
            $table->integer('length')->default(0)->comment('文件长度');
            $table->string('suffix', 50)->nullable()->default(null)->comment('文件后缀');

            $table->index('mime_type');
            $table->index('suffix');
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
        Schema::dropIfExists('files');
    }
}
