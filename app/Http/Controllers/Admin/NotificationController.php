<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NotificationRequest;
use App\Http\Transformers\NotificationTransformer;
use App\Jobs\BatchSendNotification;
use App\Models\Notification;
use DB;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * @SWG\Tag(name="admin/notification",description="后台通知相关相关")
     */

    /**
     * @SWG\Get(
     *  path="/admin/notifications",
     *  tags={"admin/notification"},
     *  summary="通知列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="type",in="query",type="string",description="类型"),
     *  @SWG\Parameter(name="user_id",in="query",type="string",description="用户ID"),
     *  @SWG\Parameter(name="read_at",in="query",type="integer",enum={0,1},description="是否已读"),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：notifiable"),
     *  @SWG\Parameter(ref="#/parameters/sort"),
     *  @SWG\Response(response=200,ref="#/responses/NoticePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Request $request)
    {
        $type = $request->type;
        $user_id = $request->user_id;
        $read_at = $request->read_at;

        $data = Notification::when($type, function ($query) use ($type) {
            return $query->where('type', $type);
        })->when($user_id, function ($query) use ($user_id) {
            return $query->where('notifiable_id', $user_id);
        })->when($read_at, function ($query) use ($read_at) {
            return $read_at ? $query->whereNotNull('read_at') : $query->whereNull('read_at');
        })->sorted()->paginate(self::perPage());

        return $this->response->paginator($data, new NotificationTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/admin/notifications",
     *  tags={"admin/notification"},
     *  summary="发送提醒通知",
     *  description="当不指定用户 ID 时，那么则会对所有用户发送通知。",
     *  @SWG\Parameter(name="body",in="body",description="可以任意定义字段",@SWG\Schema(
     *  @SWG\Property(property="user_ids",type="array",@SWG\Items(type="integer"),description="用户 ID 数组"),
     *      @SWG\Property(property="all",type="integer",default=0,description="当传递参数 1 时，则会给全部成员发送消息。"),
     *      @SWG\Property(property="title",type="string",default="系统通知"),
     *      @SWG\Property(property="content",type="string",description="具体发送的内容信息"),
     *  )),
     *  @SWG\Response(response=201,description="",@SWG\Schema(ref="#/definitions/Notice")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(NotificationRequest $request)
    {
        $all = $request->all ? true : false;

        $userIds = $request->user_ids ?? [];

        $title = $request->input('title', __('Application Notification'));
        $content = $request->input('content');

        BatchSendNotification::dispatch($title, $content, $userIds, $all)->onConnection('redis-long-running');

        return $this->response->created(null, []);
    }

    /**
     * @SWG\Get(
     *  path="/admin/notifications/{notification_id}",
     *  tags={"admin/notification"},
     *  summary="通知详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="notice_id",in="path",type="integer",required=true,description="通知 ID"),
     *  @SWG\Parameter(name="include",in="path",in="query",type="string",required=true,description="是否包含关联数据：notifiable"),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/Notice")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Notification $notification)
    {
        return $this->response->item($notification, new NotificationTransformer());
    }

    /**
     * @SWG\Delete(
     *  path="/admin/notifications",
     *  tags={"admin/notification"},
     *  summary="批量撤销",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(name="notification_ids",type="string",in="formData",description="通知ID数组"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy(Request $request)
    {
        $this->validate($request, ['notification_ids' => 'required|array']);

        // 当提醒已读时，则不去修改用户的新消息提醒数；否则将用户的新消息提醒数递减1
        $notifications = Notification::whereIn('id', $request->notification_ids)->get(['id', 'read_at', 'notifiable_id', 'notifiable_type']);

        DB::transaction(function () use ($notifications) {
            $notifications->each(function ($item) {
                if ($item->read_at) {
                    $item->delete();
                } else {
                    $item->notifiable()->decrement('new_notifications_count');
                    $item->delete();
                }
            });
        });

        return $this->response->noContent();
    }
}