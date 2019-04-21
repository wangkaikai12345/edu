<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_groups', function (Blueprint $table) {
            $table->increments('id')->comment('标签组');
            $table->string('name', 16)->comment('名字');
            $table->string('description')->nullable()->comment('描述信息');
            $table->integer('tags_count')->default(0)->comment('标签个数');
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
        Schema::dropIfExists('tag_groups');
    }
}
