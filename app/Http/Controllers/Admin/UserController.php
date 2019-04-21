<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * @SWG\Tag(name="admin/user",description="后台用户")
     */

    /**
     * @SWG\Get(
     *  path="/admin/users",
     *  tags={"admin/user"},
     *  summary="用户",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/UserQuery-username"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-email"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-phone"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-is_email_verified"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-is_phone_verified"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-registered_ip"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-registered_way"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-is_recommended"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-locked"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-last_logined_at"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-invitation_code"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-roles:name"),
     *  @SWG\Parameter(ref="#/parameters/UserQuery-roles:title"),
     *  @SWG\Parameter(ref="#/parameters/User-sort"),
     *  @SWG\Parameter(ref="#/parameters/User-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/UserPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $users = User::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/users",
     *  tags={"admin/user"},
     *  summary="添加",
     *  description="添加用户会自动分配为学生角色，如需更改，请传递 role 角色字段",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="formData",name="username",type="string",required=true,description="用户名"),
     *  @SWG\Parameter(in="formData",name="email",type="string",description="邮箱"),
     *  @SWG\Parameter(in="formData",name="phone",type="string",description="手机"),
     *  @SWG\Parameter(in="formData",name="password",type="string",required=true,description="密码"),
     *  @SWG\Parameter(in="formData",name="password_confirmation",type="string",required=true,description="确认密码"),
     *  @SWG\Parameter(in="formData",name="role",type="string",enum={"student","teacher","admin"},default="student",description="设置用户角色"),
     *  @SWG\Response(response=201,description="ok",ref="#/definitions/User"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(UserRequest $request, User $user)
    {
        $attributes = $request->only(['username', 'email', 'phone']);
        $attributes['password'] = bcrypt($request->password);
        $attributes['invitation_code'] = str_random(6);

        DB::transaction(function () use ($user, $attributes, $request) {
            $user->fill($attributes);
            $user->save();
            $user->profile()->create();
            $user->assignRole($request->role ?? UserType::STUDENT);
        });

        return $this->response->item($user, new UserTransformer())->setStatusCode(201);
    }

    /**
     * @SWG\Get(
     *  path="/admin/users/{user_id}",
     *  tags={"admin/user"},
     *  summary="详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="user_id",type="integer",required=true),
     *  @SWG\Response(response=200,description="ok",ref="#/definitions/User"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(User $user)
    {
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @SWG\Put(
     *  path="/admin/users/{user_id}",
     *  tags={"admin/user"},
     *  summary="更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="user_id",type="integer",required=true),
     *  @SWG\Parameter(in="formData",name="tags",type="string",description="标签：数组"),
     *  @SWG\Parameter(in="formData",name="signature",type="string",description="签名"),
     *  @SWG\Parameter(in="formData",name="phone",type="string",description="手机"),
     *  @SWG\Parameter(in="formData",name="email",type="string",description="邮箱"),
     *  @SWG\Parameter(in="formData",name="profile[title]",type="string",description="头衔"),
     *  @SWG\Parameter(in="formData",name="profile[name]",type="string",description="真实姓名"),
     *  @SWG\Parameter(in="formData",name="profile[idcard]",type="string",description="身份证号"),
     *  @SWG\Parameter(in="formData",name="profile[gender]",enum={"male","female","secret"},type="string",description="性别"),
     *  @SWG\Parameter(in="formData",name="profile[birthday]",type="string",description="生日：1992-09-09"),
     *  @SWG\Parameter(in="formData",name="profile[city]",type="string",description="城市"),
     *  @SWG\Parameter(in="formData",name="profile[qq]",type="string",description="QQ"),
     *  @SWG\Parameter(in="formData",name="profile[about]",type="string",description="个人介绍"),
     *  @SWG\Parameter(in="formData",name="profile[company]",type="string",description="企业"),
     *  @SWG\Parameter(in="formData",name="profile[job]",type="string",description="工作"),
     *  @SWG\Parameter(in="formData",name="profile[school]",type="string",description="毕业学校"),
     *  @SWG\Parameter(in="formData",name="profile[major]",type="string",description="专业"),
     *  @SWG\Parameter(in="formData",name="profile[weibo]",type="string",description="微博"),
     *  @SWG\Parameter(in="formData",name="profile[weixin]",type="string",description="微信"),
     *  @SWG\Parameter(in="formData",name="profile[is_qq_public]",type="integer",enum={0,1},description="是否公开QQ"),
     *  @SWG\Parameter(in="formData",name="profile[is_weixin_public]",type="integer",enum={0,1},description="是否公开微信"),
     *  @SWG\Parameter(in="formData",name="profile[is_weibo_public]",type="integer",enum={0,1},description="是否公开微博"),
     *  @SWG\Parameter(in="formData",name="profile[site]",type="string",description="个人网站"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(User $user, UserRequest $request)
    {
        DB::transaction(function () use ($user, $request) {
            $user->fill(array_only($request->all(), ['avatar', 'username', 'phone', 'email', 'tags', 'signature']));
            $user->save();
            if ($request->profile) {
                $profile = $user->profile;
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
        });

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/admin/users/{user_id}/block",
     *  tags={"admin/user"},
     *  summary="禁用与恢复",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="user_id",type="integer",required=true),
     *  @SWG\Parameter(in="formData",name="type",type="string",enum={"block","unblock"},description="禁用、恢复",default="block"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function block(User $user)
    {
        $type = request('type', 'block');

        if ($type === 'block') {
            $user->locked = true;
            $user->save();
        } else {
            $user->locked = false;
            $user->save();
        }

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/admin/users/{user_id}/reset",
     *  tags={"admin/user"},
     *  summary="重置密码",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="user_id",type="integer",required=true),
     *  @SWG\Parameter(in="formData",name="password",type="string",description="密码"),
     *  @SWG\Parameter(in="formData",name="password_confirmation",type="string",description="去人密码"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function reset(User $user, Request $request)
    {
        $this->validate($request, ['password' => 'required|min:6|confirmed']);

        $user->password = bcrypt($request->password);
        $user->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Get(
     *  path="/admin/teachers",
     *  tags={"admin/user"},
     *  summary="教师列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="last_logined_at",in="query",type="string",format="date-time",description="登录时间"),
     *  @SWG\Parameter(name="created_at",in="query",type="string",format="date-time",description="注册时间"),
     *  @SWG\Parameter(name="registered_way",in="query",type="string",enum={"web","weibo","qq","weixin","wxapp","import"},description="注册方式：网站、微博、QQ、微信、微信小程序、导入"),
     *  @SWG\Parameter(name="username",in="query",type="string",description="账户"),
     *  @SWG\Parameter(name="email",in="query",type="string",description="邮箱"),
     *  @SWG\Parameter(name="phone",in="query",type="string",description="手机号"),
     *  @SWG\Parameter(name="last_logined_ip",in="query",type="string",description="登录 IP"),
     *  @SWG\Parameter(name="roles:name",in="query",type="string",description="角色名称"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/UserPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function teacher()
    {
        $users = User::role(UserType::TEACHER)->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * @SWG\Patch(
     *  path="/admin/teachers/{user_id}/recommend",
     *  tags={"admin/teacher"},
     *  summary="推荐/取消教师",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",name="user_id",type="integer",required=true,description="被推荐用户"),
     *  @SWG\Parameter(in="formData",name="is_recommended",type="boolean",description="是否推荐",default=true),
     *  @SWG\Parameter(in="formData",name="recommended_seq",type="integer",description="排序越大则越靠前展示"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function recommend(User $user)
    {
        !$user->hasRole(UserType::TEACHER) && $this->response->errorBadRequest(__('Only teacher can be recommended.'));

        $isRecommended = (bool)request('is_recommended', true);
        $recommendedSeq = (int)request('recommended_seq', 0);

        $user->is_recommended = $isRecommended;
        $user->recommended_seq = $isRecommended ? $recommendedSeq : 0;
        $user->recommended_at = $isRecommended ? now() : null;
        $user->save();

        return $this->response->noContent();
    }
}
