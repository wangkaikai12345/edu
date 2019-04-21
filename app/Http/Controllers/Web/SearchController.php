<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Transformers\CourseTransformer;
use App\Http\Transformers\TopicTransformer;
use App\Http\Transformers\PlanTransformer;
use App\Http\Transformers\UserTransformer;
use App\Models\Course;
use App\Models\Plan;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

class SearchController extends Controller
{
    /**
     * @SWG\Tag(name="web/search",description="前台全局搜索相关")
     */

    /**
     * @var string 热词键
     */
    private $hotWordKey = 'zset:hot:words';

    /**
     * @SWG\Get(
     *  path="/search",
     *  tags={"web/search"},
     *  summary="全局搜索列表",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="q",in="query",type="string",description="关键字"),
     *  @SWG\Parameter(name="type",in="query",type="string",enum={"course","plan","user","topic"},default="course",description="搜索类型：课程、教学版本、用户、话题"),
     *  @SWG\Response(response=204,description="无数据"),
     *  @SWG\Response(response=200,description="通过对应的类型搜索返回相应 Model")
     * )
     */
    public function index()
    {
        // 搜索类型
        $type = request('type', 'course');

        // 搜索关键字
        $keyword = request('q');
        if (!$keyword) {
            return $this->response->noContent();
        }

        // 页数显示
        $perPage = request('per_page', 15);

        // TODO 为热词做时间限制
        // 记录关键字
        Redis::zincrBy($this->hotWordKey, 1, $keyword);

        // 全局搜索
        switch ($type) {
            case 'course':
                $data = Course::where('title', 'like', "%{$keyword}%")
                    ->orWhere('subtitle', 'like', "%{$keyword}%")
                    ->orWhere('summary', 'like', "%{$keyword}%")->paginate($perPage);
                return $this->response->paginator($data, new CourseTransformer());
                break;
            case 'plan':
                $data = Plan::where('title', 'like', "%{$keyword}%")
                    ->orWhere('about', 'like', "%{$keyword}%")
                    ->paginate($perPage);
                return $this->response->paginator($data, new PlanTransformer());
                break;
            case 'user':
                $data = User::where('username', 'like', "%{$keyword}%")
                    ->paginate($perPage);
                return $this->response->paginator($data, new UserTransformer());
                break;
            case 'topic':
                $data = Topic::where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%")
                    ->paginate($perPage);
                return $this->response->paginator($data, new TopicTransformer());
                break;
            default:
                $data = Course::where('title', 'like', "%{$keyword}%")
                    ->orWhere('subtitle', 'like', "%{$keyword}%")
                    ->orWhere('summary', 'like', "%{$keyword}%")
                    ->paginate($perPage);
                return $this->response->paginator($data, new CourseTransformer());
        }
    }

    /**
     * @SWG\Get(
     *  path="/search/hot-words",
     *  tags={"web/search"},
     *  summary="热词列表",
     *  @SWG\Response(response=200,description="热词列表",@SWG\Schema(type="array",@SWG\Items(type="string")))
     * )
     */
    public function hot()
    {
        // 返回前一百个热词
        $data = Redis::zrevRange($this->hotWordKey, 0, 100);

        return $this->response->array($data);
    }
}
