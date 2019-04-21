<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('log')->create('logs', function (Blueprint $table) {
            $table->increments('id')->comment('日志表');
            $table->integer('user_id')->nullable()->comment('用户id');
            $table->string('method', 10)->comment('请求方法:GET、POST、PUT、PATCH、DELETE、HEAD');
            $table->string('root')->comment('网站url');
            $table->string('path')->comment('url 路径');
            $table->string('url')->comment('url，不包含参数');
            $table->string('full_url')->comment('url，包含所有参数');
            $table->string('ip')->comment('访问者ip');
            $table->string('area')->default('')->comment('地址');
            $table->text('params')->nullable()->comment('请求参数');
            $table->string('referer')->nullable()->comment('referer 头信息');
            $table->string('user_agent')->nullable()->comment('user-agent 头信息');
            $table->string('device')->nullable()->comment('设备');
            $table->string('browser')->nullable()->comment('客户端');
            $table->string('browser_version')->nullable()->comment('客户端版本号');
            $table->string('platform')->nullable()->comment('操作系统');
            $table->string('platform_version')->nullable()->comment('操作系统版本号');
            $table->boolean('is_mobile')->default(0)->comment('是否为手机');
            $table->text('request_headers')->comment('请求头信息');
            $table->timestamp('request_time')->nullable()->comment('请求时的时间戳');
            $table->string('status_code', 10)->comment('响应的状态码');
            $table->text('response_content')->nullable()->comment('响应的主体内容');
            $table->text('response_headers')->nullable()->comment('响应头信息');
            $table->timestamp('response_time')->nullable()->comment('返回响应时的时间戳');
            $table->timestamps();

            $table->index('user_id');
            $table->index('path');
            $table->index('status_code');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
