<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\NotificationRequest;
use App\Jobs\BatchSendNotification;
use App\Models\Notification;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    /**
     * 列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $type = $request->type;
        $user_id = $request->user_id;
        $read_at = $request->read_at;

        $notifications = Notification::when($type, function ($query) use ($type) {
            return $query->where('type', $type);
        })->when($user_id, function ($query) use ($user_id) {
            return $query->where('notifiable_id', $user_id);
        })->when($read_at, function ($query) use ($read_at) {
            return $read_at ? $query->whereNotNull('read_at') : $query->whereNull('read_at');
        })->with('notifiable')->sorted()->paginate(self::perPage());


        return view('admin.notification.index', compact('notifications'));
    }

    /**
     * 添加
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        return view('admin.notification.create');
    }


    /**
     * 保存
     *
     * @param NotificationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(NotificationRequest $request)
    {
        $userIds = $request->user_ids ?? [];

        $all = empty($userIds) ? true : false;

        $title = $request->input('title', __('Application Notification'));
        $content = $request->input('content');

        BatchSendNotification::dispatch($title, $content, $userIds, $all)->onConnection('redis-long-running');

        return $this->response->created(null, []);
    }


    /**
     * 删除
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
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

    /**
     * 用户搜索
     *
     * @return mixed
     */
    public function users()
    {
        $username = \request()->input('username');

        if (empty($username)) {
            return $this->response->array([]);
        }

        $users = User::where('username', 'like', '%' . $username . '%')->get(['id', 'username'])->toArray();


        return $this->response->array($users);
    }
}