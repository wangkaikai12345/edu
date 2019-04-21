<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/7/12
 * Time: 15:00
 */

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Classroom;
use App\Models\Course;
use Facades\App\Models\Setting;
use App\Models\Slide;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * @SWG\Tag(name="web/home",description="首页")
     */
    /**
     * @var string 域名
     */
    public $domain;
    /**
     * @var string 头像
     */
    private $avatar;

    public function __construct()
    {
        $this->domain = Setting::namespace(SettingType::QINIU)['public_domain'];
        $this->avatar = Setting::namespace(SettingType::AVATAR)['image'];
    }

    /**
     * @SWG\Get(
     *  path="/home",
     *  tags={"web/home"},
     *  summary="首页所需数据",
     *  @SWG\Parameter(name="per_page",in="query",type="integer",description="数量展示",default=5),
     *  @SWG\Response(response=200,description="ok",@SWG\Schema(
     *      @SWG\Property(property="slides",description="轮播图"),
     *      @SWG\Property(property="latest",description="最新课程"),
     *      @SWG\Property(property="hottest",description="最热课程"),
     *      @SWG\Property(property="highest",description="评分最高课程"),
     *      @SWG\Property(property="navigation",description="导航，在导航的顶级菜单中包含 courses 参数，用于展示选项卡的推荐课程"),
     *      @SWG\Property(property="teachers",description="推荐教师"),
     *      @SWG\Property(property="header_nav",description="头部导航"),
     *      @SWG\Property(property="footer_nav",description="底部导航"),
     *      @SWG\Property(property="classrooms",description="推荐课程，没有则为空"),
     *  )),
     * )
     */
    public function index(Slide $slide, Course $course, Category $category)
    {
        $perPage = self::perPage(request('per_page', 10));

        // 轮播图
        $slides = $slide->oldest('seq')->select(Slide::$baseFields)->get();
        $slides = $slides->map(function ($item) {
            $item->image = $this->domain . '/' . $item->image;
            return $item;
        });

        // 最新课程
        $latest = $course->published()->notCopy()->select(Course::$baseFields)->latest('created_at')->limit($perPage)->get();
        $latest = $latest->map(function ($item) {
            $item->cover = $item->cover ? $this->domain . '/' . $item->cover : config('app.url') . '/images/cover.png';
            return $item;
        });

        // 最热课程
        $hottest = $course->published()->notCopy()->select(Course::$baseFields)->latest('hit_count')->limit($perPage)->get();
        $hottest = $hottest->map(function ($item) {
            $item->cover = $item->cover ? $this->domain . '/' . $item->cover : config('app.url') . '/images/cover.png';
            return $item;
        });

        // 评分最高课程
        $highest = $course->published()->notCopy()->select(Course::$baseFields)->latest('rating')->limit($perPage)->get();
        $highest = $highest->map(function ($item) {
            $item->cover = $item->cover ? $this->domain . '/' . $item->cover : config('app.url') . '/images/cover.png';
            return $item;
        });

        // 推荐教师（固定为 5 个）
        $teachers = User::role(UserType::TEACHER)->where('is_recommended', 1)->latest('recommended_seq')->select(User::$baseFields)->limit(5)->get();
        $teachers = $teachers->map(function ($item) {
            $item->avatar = $item->avatar ? $this->domain . '/' . $item->avatar : config('app.url') . '/images/avatar.png';
            return $item;
        });
        // 推荐班级
        $classrooms = Classroom::published()
            ->recommend()
            ->sortBySeq()
            ->select(['id', 'title', 'origin_price', 'price', 'cover', 'courses_count', 'members_count'])
            ->limit($perPage)
            ->get();
        $classrooms = $classrooms->map(function ($item) {
            $item->cover = $item->cover ? $this->domain . '/' . $item->cover : config('app.url') . '/images/cover.png';
            return $item;
        });

        // 底部导航
        $footer_nav = Setting::namespace(SettingType::FOOTER_NAV);

        // 头部导航
        $header_nav = Setting::namespace(SettingType::HEADER_NAV);

        // 课程分类
        $navigation = $category->where('category_group_id', 1)
            ->where('parent_id', 0)
            ->select(Category::$baseFields)
            ->with(['children' => function ($query) {
                $query->select(Category::$baseFields);
            }])->get();

        $navigation = collect($navigation)->map(function ($item) {
            $courses = Course::where('category_first_level_id', $item['id'])
                ->where('is_recommended', 1)
                ->latest('recommended_seq')
                ->take(4)
                ->select(Course::$baseFields)
                ->get();
            $item['courses'] = $courses->map(function ($course) {
                $course->cover = $course->cover ? $this->domain . '/' . $course->cover : config('app.url') . '/images/cover.png';
                return $course;
            })->toArray();
            return $item;
        })->toArray();

        return $this->response->array(
            compact('slides', 'latest', 'hottest', 'highest', 'navigation', 'teachers', 'header_nav', 'footer_nav', 'classrooms')
        );
    }
}