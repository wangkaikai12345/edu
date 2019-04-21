<?php

namespace App\Http\Controllers\Front;

use App\Http\Transformers\NotificationTransformer;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * 我的通知列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function index()
    {
        $notify = auth('web')->user()->notifications()->paginate(config('theme.my_notify_num'));

        return frontend_view('notice.index', compact('notify'));
    }

    /**
     * 查看通知详情
     *
     * @param $notification
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function show($notification)
    {
        $me = auth('web')->user();

        $item = $me->notifications()->findOrFail($notification);

        if (!$item->read_at) {
            \DB::transaction(function () use ($me, $item) {
                $me->decrement('new_notifications_count');
                $item->markAsRead();
            });
        }

        return frontend_view('notice.notice_modal', compact('item'));
    }

    /**
     * 全部已读
     *
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function readAll()
    {
        auth('web')->user()->markAsRead();

        return back()->with('success', '全部设置已读成功');
    }

    /**
     * 删除通知
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function destroy($id)
    {
        $me = auth('web')->user();

        $item = $me->notifications()->findOrFail($id);

        \DB::transaction(function () use ($item, $me) {
            // 查询是否为未读通知
            if (!$item->read_at) {
                $me->decrement('new_notifications_count');
            }

            $item->delete();
        });

        return back()->with('success', '删除成功');
    }


}
