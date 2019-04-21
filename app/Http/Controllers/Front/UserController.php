<?php

namespace App\Http\Controllers\Front;

use App\Enums\FavoriteType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\FollowRequest;
use App\Http\Requests\Front\UserRequest;
use App\Models\ClassroomMember;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\Follow;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\PlanTeacher;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 个人主页
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function show(User $user, Request $request)
    {
        if (auth('web')->id()) {
            $user['follows'] = auth('web')->user()->followers()->pluck('follow_id')->toArray() ?? [];
        }

        switch ($request->rel) {
            // 请求学习的课程
            case 'learning':
                $request->offsetSet('sort', 'created_at,desc');

                $user['rel'] = PlanMember::filtered()
                    ->with(['course', 'plan'])
                    ->sorted()
                    ->where('user_id', $user->id)
                    ->paginate(8)
                    ->appends($request->only(['rel']));
                break;
            // 请求学习的班级
            case 'classroom':
                $user['rel'] = ClassroomMember::filtered()
                    ->latest()
                    ->where('user_id', $user->id)
                    ->paginate(8)
                    ->appends($request->only(['rel']));
                break;
            // 请求教学课程
            case 'teaching':
                $user['rel'] = PlanTeacher::filtered()
                    ->with(['course', 'plan'])
                    ->sorted()
                    ->where('user_id', $user->id)
                    ->paginate(8)
                    ->appends($request->only(['rel']));

                break;
            // 请求收藏的课程
            case 'collect':
                $user['rel'] = Favorite::where('user_id', $user->id)
                    ->where('model_type', FavoriteType::COURSE)
                    ->with('model')
                    ->sorted()
                    ->paginate(8)
                    ->appends($request->only(['rel']));;

                break;
            // 请求粉丝／关注数据
            case 'fans':
                $user['rel'] = Follow::where('follow_id', $user->id)
                    ->with(['user' => function ($query) {
                        $query->withCount(['fans', 'followers']);
                    }])
                    ->filtered()
                    ->sorted()
                    ->paginate(10)
                    ->appends($request->only(['rel']));;

                break;
            case 'follows':
                $user['rel'] = Follow::where('user_id', $user->id)
                    ->with(['follow' => function ($query) {
                        $query->withCount(['fans', 'followers']);
                    }])
                    ->filtered()
                    ->sorted()
                    ->paginate(10)
                    ->appends($request->only(['rel']));;
                break;
            default:

        }

        return frontend_view('my.my', compact('user'));
    }

    /**
     * 个人设置
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function edit(User $user)
    {
        if ($user->id != auth('web')->id()) abort(404);

        return frontend_view('personal.information', compact('user'));
    }

    /**
     * 个人信息更新
     *
     * @param User $user
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function update(User $user, UserRequest $request)
    {
        if ($user->id != auth('web')->id()) abort(404);

        $me = auth('web')->user();

        // 更新
        \DB::transaction(function () use ($request, $me) {
            $me->update($request->only(['signature', 'avatar', 'tags']));
            if ($request->profile) {
                $profile = $me->profile;
                $profile->fill(array_only($request->profile, [
                    'title',
                    'name',
                    'idcard',
                    'gender',
                    'birthday',
                    'city',
                    'about',
                    'company',
                    'job',
                    'school',
                    'major',
                    'qq',
                    'weixin',
                    'weibo',
                    'is_qq_public',
                    'is_weixin_public',
                    'is_weibo_public',
                    'site'
                ]));
                $profile->save();
            }
            return $me;
        });

        return back()->with('success', '更新成功');
    }

    /**
     * 用户关注
     *
     * @param User $user
     * @param FollowRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function store(FollowRequest $request)
    {
        if ($request->follow_id == auth('web')->id()) {
            return ajax('400', __('Operation is not supported.'));
        }

        if (!$item = Follow::where('user_id', auth('web')->id())->where('follow_id', $request->follow_id)->first()) {
            $item = new Follow($request->all());
            $item->user_id = auth('web')->id();
            $item->save();
            return ajax('200', '关注成功', ['关注成功']);
        } else {
            $item->delete();
            return ajax('200', '取消关注成功');
        }
    }

}