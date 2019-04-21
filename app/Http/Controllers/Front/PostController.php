<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CategoryGroup;
use Illuminate\Http\Request;
use App\Models\Post;


class PostController extends Controller
{


    /**
     * 列表页
     *
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Post $post)
    {
        // 获取数据
        $posts = $post->search($request->only('category_id'))->with(['user', 'category'])->oldest()->paginate();

        // 分类数据
        $categories = $this->categories();

        // 热门文章
        $hitPosts = $post->getHitPosts();

        return view('frontend.review.post.index', compact('posts', 'categories', 'hitPosts'));
    }


    /**
     * 详情页
     *
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Post $post)
    {

        $post->load('user');
        $post->load('category');
        $post->load('tags');
        $post->increment('view_count');

        // 分类数据
        $categories = $this->categories();

        // 热门文章
        $hitPosts = $post->getHitPosts();

        // 返回页面
        return view('frontend.review.post.show', compact('post', 'categories', 'hitPosts'));
    }

    /**
     * 文章点赞
     *
     * @param Post $post
     * @return \Dingo\Api\Http\Response|void
     */
    public function vote(Post $post)
    {
        $mark = $post->votes()->where('user_id', $user_id = auth()->user()->id)->exists();

        if ($mark) {
            return $this->response->errorForbidden('该文章已点过赞');
        }

        $post->votes()->create(compact('user_id'));
        $post->increment('vote_count');

        return $this->response->noContent();
    }


    /**
     * 文章取消点赞
     *
     * @param Post $post
     * @return \Dingo\Api\Http\Response|void
     */
    public function unVote(Post $post)
    {
        $vote = $post->votes()->where('user_id', $user_id = auth()->user()->id)->first();

        if (empty($vote)) {
            return $this->response->errorForbidden('该文章还未点过赞');
        }

        // 删除点赞
        $vote->delete();
        $post->decrement('vote_count');

        return $this->response->noContent();
    }


    /**
     * 获取分类数据
     *
     * @return mixed
     */
    protected function categories()
    {
        return CategoryGroup::where('name', 'post')->first()->categories()->where('parent_id', 0)->get();
    }
}