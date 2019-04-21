<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use Illuminate\Support\Facades\Redis;

class SearchController extends Controller
{
    /**
     * @var string 热词键
     */
    private $hotWordKey = 'zset:hot:words';

    public function index()
    {
        // 搜索关键字
        $keyword = request('keyword');
        if (!$keyword) {
            return ajax('400', '无搜索关键字', []);
        }

        // TODO 为热词做时间限制
        // 记录关键字
        Redis::zincrBy($this->hotWordKey, 1, $keyword);

        $data = [
            'domain' => \Facades\App\Models\Setting::namespace(\App\Enums\SettingType::QINIU)['public_domain']
        ];

        // 全局搜索
        $data['course'] = Course::where('title', 'like', "%{$keyword}%")->get();

        $data['classroom'] = Classroom::where('title', 'like', "%{$keyword}%")->get();

        return ajax('200', '搜索成功', $data);

    }

//    public function hot()
//    {
//        // 返回前一百个热词
//        $data = Redis::zrevRange($this->hotWordKey, 0, 100);
//
//        return $this->response->array($data);
//    }
}
