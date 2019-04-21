<?php

namespace App\Http\Controllers\Front;

use App\Enums\SettingType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\ClassroomResult;
use App\Models\Plan;
use Facades\App\Models\Setting;
use Illuminate\Http\Request;
use App\Traits\JoinTrait;

class ClassroomController extends Controller
{
    use JoinTrait;

    /**
     * 班级列表
     *
     * @param Request $request
     * @param Classroom $classroom
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function index(Request $request, Classroom $classroom, Category $category)
    {
        $request->flash();

        // 课程数据
        $classrooms = $classroom
            ->published()
            ->where('is_show', 1)
            ->filtered()
            ->sorted()
            ->paginate(config('theme.course_index_number'))
            ->appends($request->only(['sort', 'category_id', 'category_first_level_id']));

        // 分类数据(分类群组为2的班级分类)
        $categories = $category->where(['category_group_id' => 2, 'parent_id' => 0])->with('children')->get();

        return frontend_view('classroom.index', compact('classrooms', 'categories'));
    }

    /**
     * 班级详情
     *
     * @param Classroom $classroom
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function show(Classroom $classroom)
    {
        return frontend_view('classroom.show', compact('classroom'));
    }

    /**
     * 班级的版本详情
     *
     * @param Classroom $classroom
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function plans(Classroom $classroom)
    {
        // 数据验证
        $classroom->status != 'published' && abort(404, '班级不存在');

        $control = $classroom->isControl();
        $member = $classroom->isMember();
        if (!$control && !$member) {
            abort(404, '您还没有加入班级');
        }

        // 获取班级的阶段和关
        $chapters = $classroom->chaptersChildren();

        // 只获取关
        $chaps = $classroom->chaps();

        if (!$chapters || !$chaps) {
            abort(404, '班级还没有阶段和关');
        }

        // 获取班级的关数
        $total = $chaps->count();

        // 获取已经通关的关数
        $already = $member ? $member->pass_count : 0;

        // 获取用户当前处的关
        $current = $member ? Chapter::find($member->current_chap) : $chapters[0]['children'][0];

        $sort = $chaps->search($current);

        $preChapter = $sort ? $chaps[$sort - 1] : $current;

        $pre = $preChapter->classroomResult($classroom->id);

        // 获取目前处的关的相同进度的人数
        $same = $current ? ClassroomResult::where('chapter_id', $current->id)->where('status', '<>', 'pass')->groupBy('user_id')->count() : 0;

        // 初始化阶段的锁定
        $chaptersLock = false;
        foreach ($chapters as $chapter) {

            // 锁住阶段
            if ($chaptersLock) {
                $chapter['lock'] = true;
            } else {
                $chapter['lock'] = false;
            }

            if (is_numeric($chapter->children->search($current))) {
                $chaptersLock = true;

                // 开始锁关
                if ($chapter->children->count()) {

                    $chapLock = false;
                    foreach ($chapter->children as $child) {

                        if ($chapLock) {
                            $child['lock'] = true;
                        } else {
                            $child['result'] = $child->classroomResult($classroom->id);
                            $child['lock'] = false;
                        }

                        if ($child->id == $current->id) {
                            $chapLock = true;
                        }
                    }
                }

            }
        }

        // 最近7关的成绩
        $score = [0, 0, 0, 0, 0, 0, 0];

        $index = $chaps->search($current);

        // 如果通过的关数达到七关
        foreach ($score as $k => $value) {
            if ($index >= 6) {
                $ch = isset($chaps[$index - 6 + $k]) ? $chaps[$index - 6 + $k]->classroomResult($classroom->id) : 0;
                $score[$k] = $ch ? $ch->score : 0;
            } else {
                // 没有达到七关
                $ch = isset($chaps[$k]) ? $chaps[$k]->classroomResult($classroom->id) : 0;
                $score[$k] = $ch ? $ch->score : 0;
            }
        }

        // 进度排名前三名
        $ranking = ClassroomMember::where('classroom_id', $classroom->id)->with('user')->latest('pass_count')->get();

        $myRank = $member ? $ranking->pluck('id')->search($member->id) + 1 : 1;

        // 成绩排名前三名
        $mark = ClassroomMember::where('classroom_id', $classroom->id)->with('user')->latest('average_score')->get();

        $myMark = $member ? $mark->pluck('id')->search($member->id) + 1 : 1;

        // 返回页面
        return frontend_view('classroom.plan_index',
            compact('classroom', 'chapters', 'total', 'already', 'same', 'current', 'pre', 'score',
                'ranking', 'myRank', 'mark', 'myMark')
        );
    }

    /**
     * 班级购买
     *
     * @param Course $course
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @author 王凯
     */
    public function shopping(Classroom $classroom)
    {
        // 加入信息
        $member = $classroom->isMember();

        // 是否管理
        $control = $classroom->isControl();

        if ($member || $control) {
            return redirect()->route('classrooms.show', $classroom);
        }

        // 查询是否免费，直接加入
        if (!$classroom->coin_price && !$classroom->price) {

            if ($this->freeOrInside('classroom', $classroom->id, auth('web')->id(), 'free')) {
                return redirect()->route('classrooms.plans', $classroom)->with('success', '加入班级成功');
            } else {
                return back()->withErrors('班级异常，暂时不能加入');
            }
        }

        // 查询购买版本是否已经创建订单
        $order = $classroom->orders()->where(['user_id' => auth('web')->id(), 'status' => 'created'])->first();

        $alipay = [];
        $wechat = [];

        if ($order) {
            // 获取支付配置
            $alipay = Setting::namespace(SettingType::ALI_PAY)['on'];
            $wechat = Setting::namespace(SettingType::WECHAT_PAY)['on'];
        }

        return frontend_view('classroom.shopping', compact('classroom', 'order', 'alipay', 'wechat'));
    }
}