<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->increments('id')->comment('公告表');
            $table->text('content')->comment('公告内容');
            $table->string('type', 16)->default(\App\Enums\NoticeType::WEB)->comment('公告类型');
            $table->timestamp('started_at')->comment('开始时间');
            $table->timestamp('ended_at')->nullable()->comment('结束时间');
            $table->integer('plan_id')->nullable()->comment('版本ID');
            $table->integer('user_id')->comment('创建人');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notices');
    }
}
