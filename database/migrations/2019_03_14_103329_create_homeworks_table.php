<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 64)->comment('作业标题');
            $table->text('about')->comment('作业简介');
            $table->text('hint')->nullable()->comment('作业提示');
            $table->string('post_type', 64)->comment('作业提交的类型,  索引数组,  zip 附件,  img图片,  code 代码');
            $table->string('grades', 255)->comment('评分标准, 批改标准表的id数组');
            $table->text('grades_content')->comment('评分标准内容-冗余字段');
            $table->string('status', 16)->default(\App\Enums\Status::DRAFT)->comment('发布状态');
            $table->string('type', 16)->default(\App\Enums\HomeworkType::HOMEWORK)->comment('作业类型');
            $table->string('video', 255)->nullable()->comment('讲解视频');
            $table->string('package', 255)->nullable()->comment('资料包');
            $table->integer('time')->default(0)->comment('预计完成时间, 单位秒');
            $table->integer('user_id')->comment('创建人');
            $table->timestamps();
            $table->softDeletes();
            $table->index('title');
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
        Schema::dropIfExists('homeworks');
    }
}
