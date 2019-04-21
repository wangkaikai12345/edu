<?php

namespace App\Handlers;

use App\Enums\SettingType;
use App\Enums\UserType;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Navigation;
use App\Models\Slide;
use App\Models\User;
use Facades\App\Models\Setting;

class IndexHandler
{
    public $domain;

    private $avatar;

    public function __construct()
    {
        $this->domain = Setting::namespace(SettingType::QINIU)['public_domain'];
        $this->avatar = Setting::namespace(SettingType::AVATAR)['image'];
    }

    /**
     * 头部导航
     *
     * @return mixed
     * @author 王凯
     */
    public function header_nav()
    {
        return Navigation::head()->with('children')->get();

    }

    /**
     * 底部导航
     *
     * @return mixed
     * @author 王凯
     */
    public function footer_nav()
    {
        return Navigation::footer()->where('status', 1)->with('children')->get();

    }

    /**
     * @author 王凯
     */
    public function pay_type()
    {
        $alipay = Setting::namespace(SettingType::ALI_PAY)['on'];
        $wechat = Setting::namespace(SettingType::WECHAT_PAY)['on'];

        return ['alipay' => $alipay, 'wechat' => $wechat];
    }

    /**
     * 网站配置信息
     *
     * @return mixed
     * @author 王凯
     */
    public function site()
    {
        return Setting::namespace(SettingType::SITE);
    }

    /**
     * 获取注册配置
     *
     * @return mixed
     * @author 王凯
     */
    public function register()
    {
        return Setting::namespace(SettingType::REGISTER);
    }

    /**
     * 获取登陆配置
     *
     * @return mixed
     * @author 王凯
     */
    public function login()
    {
        return Setting::namespace(SettingType::LOGIN);
    }

    /**
     * 轮播图信息
     *
     * @return mixed
     * @author 王凯
     */
    public function slide()
    {
        // 轮播图
        return Slide::oldest('seq')->select(Slide::$baseFields)->get();
    }

    /**
     * 课程信息
     *
     * @param $sort
     * @return mixed
     * @author 王凯
     */
    public function course($sort = 'created_at')
    {
        if (!in_array($sort, ['created_at', 'hit_count', 'rating'])) {
            return [];
        }

        return Course::published()->with('default_plan')->notCopy()->latest($sort)->take(config('theme.index_course_num'))->get();
    }

    /**
     * 班级信息
     * 
     * @return mixed
     * @author 王凯
     */
    public function classroom()
    {
        return Classroom::published()
            ->where('is_show', 1)
            ->latest('members_count')
            ->select(['id', 'title', 'origin_price', 'price', 'cover', 'courses_count', 'members_count'])
            ->limit(config('theme.course_num'))
            ->get();
    }

    /**
     * 推荐教师信息
     *
     * @return mixed
     * @author 王凯
     */
    public function teacher()
    {
       return  User::role(UserType::TEACHER)
           ->where('is_recommended', 1)
           ->select(User::$baseFields)
           ->orderBy('recommended_seq')
           ->limit(config('theme.index.teacher_num'))
           ->get();
    }

    /**
     * 课程分类分类下的课程4个
     *
     * @return array
     * @author 王凯
     */
    public function course_category()
    {
        $category = Category::where('category_group_id', CategoryGroup::where('name', 'course')->value('id'))
            ->where('parent_id', 0)
            ->select(Category::$baseFields)
            ->with(['children' => function ($query) {
                $query->select(Category::$baseFields);
            }])->get();

        return collect($category)->map(function ($item) {
            $item['courses'] = Course::where('category_first_level_id', $item['id'])
                ->where('is_recommended', 1)
                ->latest('recommended_seq')
                ->take(2)
                ->select(Course::$baseFields)
                ->get();
            return $item;
        })->toArray();
    }

    public function __call($name, $params)
    {
        return [];
    }

    public function __get($name)
    {
        return [];
    }
}