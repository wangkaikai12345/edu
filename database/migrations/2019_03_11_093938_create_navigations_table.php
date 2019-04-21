<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('导航名称');
            $table->boolean('target')->default(true)->comment('是否新开窗口');
            $table->boolean('status')->default(true)->comment('是否开启');
            $table->string('link')->comment('导航跳转链接');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID');
            $table->enum('type', ['header', 'footer'])->comment('导航类型');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigations');
    }
}
