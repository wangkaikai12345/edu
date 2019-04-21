<?php

namespace App\Http\Controllers\Web;

use App\Http\Transformers\NotificationTransformer;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * @SWG\Tag(name="web/notification",description="前台我的通知")
     */

    /**
     * @SWG\Get(
     *  path="/my-notifications",
     *  tags={"web/notification"},
     *  summary="列表",
     *  description="默认以 created_at 降序",
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Parameter(name="type",in="query",type="string",enum={"read","unread","all"},default="all",description="已读、未读、所有，默认所有"),
     *  @SWG\Response(response=200,description="Success"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index()
    {
        $perPage = self::perPage(request('per_page', 15));

        $me = auth()->user();
        
        self::orderBy();

        switch (request('type', 'all')) {
            case 'read':
                $data = $me->notifications()->whereNotNull('read_at')->paginate($perPage);
                break;
            case 'unread':
                $data = $me->notifications()->whereNull('read_at')->paginate($perPage);
                break;
            case 'all':
            default:
                $data = $me->notifications()->paginate($perPage);
        }

        return $this->response->paginator($data, new NotificationTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/my-notifications/{notification_id}",
     *  tags={"web/notification"},
     *  summary="详情/设置已读",
     *  description="读取详情后自动标记为已读",
     *  @SWG\Parameter(name="notification_id",in="path",type="string",required=true),
     *  @SWG\Parameter(name="include",in="query",type="string",description="是否包含关联数据：notifiable"),
     *  @SWG\Response(response=200,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show($id)
    {
        $me = auth()->user();

        $item = $me->notifications()->findOrFail($id);
        if (!$item->read_at) {
            \DB::transaction(function () use ($me, $item) {
                $me->decrement('new_notifications_count');
                $item->markAsRead();
            });
        }

        return $this->response->item($item, new NotificationTransformer());
    }

    /**
     * @SWG\Patch(
     *  path="/my-notifications/read-all",
     *  tags={"web/notification"},
     *  summary="标记全部已读",
     *  @SWG\Response(response=204,description="Success"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function readAll()
    {
        $me = auth()->user();

        $me->markAsRead();

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/my-notifications/{notification_id}",
     *  tags={"web/notification"},
     *  summary="删除通知",
     *  @SWG\Response(response=204,description="Success"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy($id)
    {
        $me = auth()->user();

        $item = $me->notifications()->findOrFail($id);

        \DB::transaction(function () use ($item, $me) {
            // 查询是否为未读通知
            if (!$item->read_at) {
                $me->decrement('new_notifications_count');
            }

            $item->delete();
        });

        return $this->response->noContent();
    }
}
