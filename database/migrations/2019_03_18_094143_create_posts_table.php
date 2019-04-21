<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('文章标题');
            $table->string('subtitle')->nullable()->comment('文章副标题');
            $table->text('body')->comment('文章内容');

            $table->string('status', 16)->default(\App\Enums\Status::DRAFT)->comment('发布状态');
            $table->unsignedInteger('category_id')->comment('分类ID');

            $table->unsignedInteger('vote_count')->default(0)->comment('点赞数量');
            $table->unsignedInteger('view_count')->default(0)->comment('查看数量');
            $table->unsignedInteger('reply_count')->default(0)->comment('回复数量');

            $table->boolean('is_recommend')->default(false)->comment('是否推荐');
            $table->timestamp('recommend_at')->nullable()->comment('推荐时间');
            $table->integer('recommend_seq')->default(0)->comment('推荐序号');
            $table->boolean('is_essence')->default(false)->comment('是否精华');
            $table->boolean('is_stick')->default(false)->comment('是否置顶');
            $table->unsignedInteger('last_user_id')->default(0)->comment('最后回复人');
            $table->timestamp('last_reply_at')->nullable()->comment('最后回复时间');

            $table->string('slug')->nullable()->comment('SEO友好的URI');

            $table->unsignedInteger('user_id')->comment('创建人');
            $table->timestamps();
            $table->softDeletes();

            $table->index('title');
            $table->index('user_id');
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
        Schema::dropIfExists('posts');
    }
}
