<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')->comment('类别');
            $table->string('name')->comment('名称');
            $table->string('icon')->nullable()->comment('图标');
            $table->integer('seq')->default(0)->comment('权重');
            $table->integer('parent_id')->default(0)->comment('父级');
            $table->integer('category_group_id')->comment('群组');
            $table->timestamps();
            $table->softDeletes();

            // 索引
            $table->index('category_group_id');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
