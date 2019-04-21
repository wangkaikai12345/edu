<?php

namespace App\Traits;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait HitPostTrait
{
    // 缓存相关配置
    protected $cache_key = 'hit_post';
    protected $cache_expire_in_minutes = 1440;

    /**
     * 热点文章
     *
     * @return mixed
     */
    public function getHitPosts()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function () {
            return $this->formatHitPosts();
        });
    }


    /**
     * 缓存重新加载
     */
    public function calculateAndCacheHitPosts()
    {
        $hitPosts = $this->formatHitPosts();

        // 并加以缓存
        $this->cacheHitPosts($hitPosts);
    }
    

    /**
     * 处理热点文章
     *
     * @return array
     */
    private function formatHitPosts()
    {
        $posts = Post::select('id', 'title', 'view_count', 'vote_count', 'updated_at', 'recommend_seq')->get()->toArray();

        // 循环处理数据
        foreach ($posts as &$post) {
            // 计算时间
            $diffHours = Carbon::parse($post['updated_at'])->diffInHours();
            // 计算分数
            $fraction = ($post['vote_count'] + log($post['view_count'] + 2) / 2 + log(($post['recommend_seq'] + 2) / 2)) / ($diffHours + 2);
            // 复制
            $post['fraction'] = $fraction;
        }

        // 去除引用
        unset($post);

        // 排序
        $posts = array_sort($posts, function ($post) {
            return $post['fraction'];
        });

        // 反转
        $posts = array_reverse($posts, true);

        // 只获取我们想要的数量
        $posts = array_slice($posts, 0, 10, true);

        // 返回数据
        return $posts;
    }


    /**
     * 清除缓存重新写入
     *
     * @param $hitPosts
     */
    private function cacheHitPosts($hitPosts)
    {
        // 将数据放入缓存中
        Cache::put($this->cache_key, $hitPosts, $this->cache_expire_in_minutes);
    }

}