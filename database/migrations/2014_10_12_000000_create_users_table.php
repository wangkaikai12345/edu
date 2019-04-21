<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid', 32)->nullable()->comment('用户id');
            $table->string('username', 64)->comment('登录用户名');
            $table->string('password')->nullable()->comment('用户密码');
            $table->string('email', 128)->nullable()->comment('邮箱');
            $table->string('phone', 32)->nullable()->comment('手机');
            $table->text('signature')->nullable()->comment('个人签名');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('tags')->nullable()->comment('标签');
            $table->integer('inviter_id')->default(0)->comment('邀请人 id');
            $table->boolean('is_email_verified')->default(false)->comment('邮箱是否为已验证');
            $table->boolean('is_phone_verified')->default(false)->comment('手机号是否为已验证');
            $table->string('registered_ip', 64)->nullable()->comment('注册IP');
            $table->string('registered_way')->default('web')->comment('注册来源:web、weibo、qq、weixin、wxapp、import 网站，微博，QQ，微信，小程序，导入');
            $table->boolean('locked')->default(false)->comment('是否禁用');
            $table->boolean('is_recommended')->default(false)->comment('是否为推荐教师');
            $table->integer('recommended_seq')->default(0)->comment('推荐序号');
            $table->timestamp('recommended_at')->nullable()->comment('推荐时间');
            $table->timestamp('locked_deadline')->nullable()->comment('帐号锁定期限');
            $table->integer('password_error_times')->default(0)->comment('帐号密码错误次数');
            $table->timestamp('last_password_failed_at')->nullable()->comment('上一次密码错误');
            $table->timestamp('last_logined_at')->nullable()->comment('上一次登录时间');
            $table->string('last_logined_ip', 64)->nullable()->comment('上一次登录IP');
            $table->integer('new_messages_count')->default(0)->comment('未读私信数');
            $table->integer('new_notifications_count')->default(0)->comment('未读消息数');
            $table->string('invitation_code')->nullable()->comment('邀请码');
            $table->unsignedBigInteger('coin')->default(0)->comment('虚拟币余额');
            $table->unsignedBigInteger('recharge')->default(0)->comment('虚拟币充值数');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('username');
            $table->unique('email');
            $table->unique('phone');
            $table->unique('invitation_code');
            $table->index('inviter_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
