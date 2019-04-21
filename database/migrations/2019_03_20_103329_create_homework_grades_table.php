<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworkGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homework_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 64)->comment('评分标题');
            $table->string('status', 16)->default(\App\Enums\Status::PUBLISHED)->comment('发布状态');
            $table->string('remarks', 255)->comment('备注');
            $table->text('comment_bad')->comment('差评');
            $table->text('comment_middle')->comment('中评');
            $table->text('comment_good')->comment('好评');
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
