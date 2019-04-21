<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\UserRequest;
use App\Http\Transformers\PlanMemberTransformer;
use App\Models\Plan;
use App\Models\User;
use Dingo\Api\Http\Request;

class MeController extends Controller
{
    /**
     * @SWG\Tag(name="web/me",description="登录用户信息")
     */

    /**
     * @SWG\Put(
     *  path="/my-profile",
     *  tags={"web/me"},
     *  summary="个人信息更新",
     *  description="无法通过此接口修改手机、邮箱、密码",
     *  @SWG\Parameter(name="avatar",in="formData",type="string",description="头像"),
     *  @SWG\Parameter(name="tags",in="formData",type="array",@SWG\Items(type="string")),
     *  @SWG\Parameter(name="signature",in="formData",type="string",description="个性签名"),
     *  @SWG\Parameter(name="profile[title]",in="formData",type="string",description="头衔"),
     *  @SWG\Parameter(name="profile[name]",in="formData",type="string",description="真实姓名"),
     *  @SWG\Parameter(name="profile[idcard]",in="formData",type="string",description="身份证件号码"),
     *  @SWG\Parameter(name="profile[gender]",in="formData",type="string",enum={"male","female","secret"},description="性别",default="secret"),
     *  @SWG\Parameter(name="profile[birthday]",in="formData",type="string",format="date-time",description="出生日期"),
     *  @SWG\Parameter(name="profile[city]",in="formData",type="string",description="所在城市"),
     *  @SWG\Parameter(name="profile[qq]",in="formData",type="string",description="所在城市"),
     *  @SWG\Parameter(name="profile[about]",in="formData",type="string",description="个性介绍"),
     *  @SWG\Parameter(name="profile[company]",in="formData",type="string",description="公司"),
     *  @SWG\Parameter(name="profile[job]",in="formData",type="string",description="工作"),
     *  @SWG\Parameter(name="profile[school]",in="formData",type="string",description="大学"),
     *  @SWG\Parameter(name="profile[major]",in="formData",type="string",description="专业"),
     *  @SWG\Parameter(name="profile[weibo]",in="formData",type="string",description="微博"),
     *  @SWG\Parameter(name="profile[weixin]",in="formData",type="string",description="微信"),
     *  @SWG\Parameter(name="profile[is_qq_public]",in="formData",type="boolean",description="公开QQ",default=false),
     *  @SWG\Parameter(name="profile[is_weixin_public]",in="formData",type="boolean",description="公开微信",default=false),
     *  @SWG\Parameter(name="profile[is_weibo_public]",in="formData",type="boolean",description="公开微博",default=false),
     *  @SWG\Parameter(name="profile[site]",in="formData",type="string",description="个人网站"),
     *  @SWG\Response(response=204,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(UserRequest $request)
    {
        $me = auth()->user();

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

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/invitation-code",
     *  tags={"web/me"},
     *  summary="输入邀请码",
     *  @SWG\Parameter(name="invitation_code",in="formData",type="string",description="邀请码"),
     *  @SWG\Response(response=201,description="ok"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function updateInvitationCode()
    {
        $this->validate(request(),['invitation_code' => 'required|exists:users,invitation_code']);

        $me = auth()->user();

        $me->inviter_id && $this->response->errorBadRequest(__('Inviter has been input.'));

        $me->inviter_id = User::where('invitation_code', request('invitation_code'))->value('id');

        $me->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Get(
     *  path="/plan-progress",
     *  tags={"web/me"},
     *  summary="我的版本完成进度",
     *  @SWG\Parameter(name="plan_id",in="query",type="integer",required=true),
     *  @SWG\Response(response=200,description="ok",@SWG\Schema(ref="#/definitions/PlanMember")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function getPlanProgress(Request $request)
    {
        $this->validate($request, ['plan_id' => 'required|integer']);

        $me = auth()->user();

        $plan = Plan::findOrFail($request->plan_id);

        $item = $plan->members()->where('user_id', $me->id)->first();

        $task = $plan->getLearningTask();

        if (!$item) {
            return $this->response->array(['meta' => ['task' => $task]]);
        }

        return $this->response->item($item, new PlanMemberTransformer())->setMeta(['task' => $task]);
    }


    /**
     * @SWG\Get(
     *  path="/unread-count",
     *  tags={"web/me"},
     *  summary="未读消息数",
     *  description="",
     *  @SWG\Response(response=200,description="ok",description="维度提醒数：new_notifications_count；未读消息数：new_messages_count；总数：total_num"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function unread()
    {
        $me = auth()->user();

        $new_notifications_count = $me->new_notifications_count;
        $new_messages_count = $me->new_messages_count;

        $total_num = $new_messages_count + $new_notifications_count;

        return $this->response->array(compact('new_notifications_count', 'new_messages_count', 'total_num'));
    }
}