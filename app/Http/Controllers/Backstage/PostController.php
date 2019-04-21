<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\PostRequest;
use App\Models\CategoryGroup;
use App\Models\Post;
use App\Models\TagGroup;
use App\Models\User;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PostController extends Controller
{
    /**
     * 列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 创建人搜索
        if (!empty($username = $request->input('creator'))) {
            // 查询用户ID
            $user_ids = User::where('username', 'like', $username)->pluck('id');
            // 赋值
            $request->offsetSet('creator_ids', $user_ids);
        }

        // 分类数据
        $categories = CategoryGroup::where('name', 'post')->first()->categories()->where('parent_id', 0)->get();

        // 文章数据
        $posts = Post::search($request->all())->with(['user', 'category'])->latest()->paginate();

        // 返回页面
        return view('admin.post.index', compact('posts', 'categories'));
    }


    /**
     * 列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recommendIndex(Request $request)
    {
        // 创建人搜索
        if (!empty($username = $request->input('creator'))) {
            // 查询用户ID
            $user_ids = User::where('username', 'like', $username)->pluck('id');
            // 赋值
            $request->offsetSet('creator_ids', $user_ids);
        }

        // 分类数据
        $categories = CategoryGroup::where('name', 'post')->first()->categories()->where('parent_id', 0)->get();

        $posts = Post::search($request->all())->with(['user', 'category'])->where('is_recommend', 1)->latest()->paginate();

        return view('admin.post.recommend_index', compact('posts', 'categories'));
    }

    /**
     * 创建页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = CategoryGroup::where('name', 'post')->first()->categories()->where('parent_id', 0)->get();

        $tags = TagGroup::where('name', 'post')->first()->tags()->get();

        $domain = config('filesystems.disks.imgs.domains.default');

        return view('admin.post.create', compact('categories', 'tags', 'domain'));
    }


    /**
     * 添加成功
     *
     * @param PostRequest $request
     * @return Response|void
     */
    public function store(PostRequest $request)
    {

        try {

            DB::beginTransaction();

            // 实例化
            $post = Post::make($request->only(['title', 'subtitle', 'category_id', 'body', 'status']));

            // 添加属性
            $post->is_essence = $request->input('is_essence', false);
            $post->is_stick = $request->input('is_stick', false);
            $post->user_id = auth()->user()->id;


            // 保存
            $post->save();

            // 添加标签
            $post->tags()->sync($request->input('tags'));

            DB::commit();

            return new Response([], 201);
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return $this->response->error('添加失败.', 500);
        }
    }


    /**
     * 修改
     *
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Post $post)
    {
        $hasTags = $post->tags()->pluck('id')->toArray();

        $categories = CategoryGroup::where('name', 'post')->first()->categories()->where('parent_id', 0)->get();

        $tags = TagGroup::where('name', 'post')->first()->tags()->get();

        $domain = config('filesystems.disks.imgs.domains.default');

        return view('admin.post.edit', compact('post', 'categories', 'tags', 'domain', 'hasTags'));
    }


    /**
     * 添加成功
     *
     * @param Post $post
     * @param PostRequest $request
     * @return Response|void
     */
    public function update(Post $post, PostRequest $request)
    {

        try {

            DB::beginTransaction();

            // 实例化
            $post->update($request->only(['title', 'subtitle', 'category_id', 'body', 'status', 'is_essence', 'is_stick']));

            // 添加标签
            $post->tags()->sync($request->input('tags'));

            DB::commit();

            return new Response([], 204);
        } catch (\Exception $exception) {
            report($exception);
            DB::rollBack();
            return $this->response->error('更新失败.', 500);
        }
    }


    /**
     * 删除
     *
     * @param Post $post
     * @return Response
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return $this->response->noContent();
    }


    /**
     * 发布文章
     *
     * @param Post $post
     * @return Response
     */
    public function publish(Post $post)
    {
        if ($post->status == 'published') {
            return $this->response->errorForbidden('操作异常');
        }

        return $this->updatePostStatus($post, 'published');
    }


    /**
     * 关闭文章
     *
     * @param Post $post
     * @return Response
     */
    public function close(Post $post)
    {
        if ($post->status == 'closed') {
            return $this->response->errorForbidden('操作异常');
        }

        return $this->updatePostStatus($post, 'closed');
    }


    /**
     * 置顶
     *
     * @param Post $post
     * @return Response
     */
    public function essence(Post $post)
    {
        if ((boolean)$post->is_essence) {
            return $this->response->errorForbidden('操作异常');
        }

        $post->is_essence = true;
        $post->save();

        return $this->response->noContent();
    }


    /**
     * 取消置顶
     *
     * @param Post $post
     * @return Response
     */
    public function unEssence(Post $post)
    {
        if (!(boolean)$post->is_essence) {
            return $this->response->errorForbidden('操作异常');
        }

        $post->is_essence = false;
        $post->save();

        return $this->response->noContent();
    }


    /**
     * 置顶
     *
     * @param Post $post
     * @return Response
     */
    public function stick(Post $post)
    {
        if ((boolean)$post->is_stick) {
            return $this->response->errorForbidden('操作异常');
        }

        $post->is_stick = true;
        $post->save();

        return $this->response->noContent();
    }


    /**
     * 取消置顶
     *
     * @param Post $post
     * @return Response
     */
    public function unStick(Post $post)
    {
        if (!(boolean)$post->is_stick) {
            return $this->response->errorForbidden('操作异常');
        }

        $post->is_stick = false;
        $post->save();

        return $this->response->noContent();
    }


    /**
     * 推荐页
     *
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recommendShow(Post $post)
    {
        return view('admin.post.recommend', compact('post'));
    }


    /**
     * 推荐
     *
     * @param Post $post
     * @return Response
     */
    public function recommend(Post $post, Request $request)
    {
        $this->validate($request, ['recommend_seq' => ['required', 'numeric',]]);

        $post->is_recommend = true;
        $post->recommend_at = now();
        $post->recommend_seq = $request->input('recommend_seq');
        $post->save();

        return $this->response->noContent();
    }


    /**
     * 取消推荐
     *
     * @param Post $post
     * @return Response
     */
    public function unRecommend(Post $post)
    {
        $post->is_recommend = false;
        $post->recommend_at = null;
        $post->recommend_seq = 0;
        $post->save();

        return $this->response->noContent();
    }


    /**
     * 更新文章状态
     *
     * @param Post $post
     * @param $status
     * @return Response
     */
    protected function updatePostStatus(Post $post, $status)
    {
        $post->status = $status;
        $post->save();

        return $this->response->noContent();
    }
}
