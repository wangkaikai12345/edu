<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->integer('user_id')->comment('用户ID');
            $table->string('title')->nullable()->comment('头衔');
            $table->string('name')->nullable()->comment('真实姓名');
            $table->string('idcard',18)->nullable()->comment('身份证号码');
            $table->string('gender', 8)->default(\App\Enums\Gender::SECRET)->comment('性别');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('city',64)->nullable()->comment('城市');
            $table->text('about')->nullable()->comment('自我介绍');
            $table->string('company')->nullable()->comment('公司');
            $table->string('job')->nullable()->comment('职业/工作');
            $table->string('school')->nullable()->comment('毕业院校');
            $table->string('major')->nullable()->comment('所学专业');
            $table->string('qq',32)->nullable()->comment('QQ');
            $table->string('weibo')->nullable()->comment('微博');
            $table->string('weixin')->nullable()->comment('微信');
            $table->boolean('is_qq_public')->default(false)->comment('qq是否公开，默认为不公开');
            $table->tinyInteger('is_weixin_public')->default(false)->comment('微信是否公开，默认为不公开');
            $table->tinyInteger('is_weibo_public')->default(false)->comment('微博是否公开公开，默认为不公开');
            $table->string('site')->nullable()->comment('个人网站/个人空间');
            $table->timestamps();

            $table->primary('user_id');
            $table->index('name');
            $table->unique('idcard');
            $table->index('weixin');
            $table->index('weibo');
            $table->index('qq');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile');
    }
}
