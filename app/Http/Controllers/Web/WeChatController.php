<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\WxUserBindRequest;
use App\Models\User;
use Dingo\Api\Exception\ResourceException;
use Facades\App\Models\User as StaticUser;
use Illuminate\Support\Facades\Cookie;
use Log;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class WeChatController extends Controller
{
    protected $app;

    protected $cache_time = 30;

    /**
     * Construct
     *
     * WeChatController constructor.
     */
    public function __construct()
    {
        $this->app = app('wechat.official_account');
    }


    /**
     * 微信授权登录
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function wechatLogin(Request $request)
    {
        // 获取app实例
        $app = \EasyWeChat::officialAccount();

        // 用户刷新页面重新登录
        try {
            // 获取授权模块
            $oauth = $app->oauth;

            // 获取用户信息
            $info = $oauth->user();
        } catch (\Exception $exception) {
            return redirect()->to(route('login'));
        }


        // 数据查询
        $user = User::WhereOpenid($info->id)->first();

        // COOKIE标志
        $weChatFlag = Uuid::uuid4()->getHex();

        if (!empty($user)) {

            // 手机号不为空
            if (empty($user->phone)) {
                $this->needBind(['Event' => 'subscribe', 'EventKey' => 'qrscene_' . $weChatFlag], $user->toArray());

                return response()->view('frontend.review.bind')->cookie(User::WECHAT_FLAG, $weChatFlag, $this->cache_time);
            } else {
                // 用户登录
                \Auth::guard('web')->login($user);

                return redirect()->to($request->session()->get('redirectPath'));
            }
        }

        // 用户名
        $username = $this->filterEmoji($info->nickname);

        // 处理重复
        $username = StaticUser::formatWxUsername($username);

        // GUID
        $guid = Uuid::uuid4()->getHex();

        // 保存数据
        $this->needBind(['Event' => 'subscribe', 'EventKey' => 'qrscene_' . $weChatFlag], ['guid' => $guid, 'username' => $username, 'open_id' => $info->id,]);

        // 返回页面
        return response()->view('frontend.review.bind')->cookie(User::WECHAT_FLAG, $weChatFlag, $this->cache_time);
    }


    /**
     * 验证登录
     *
     * @param Request $request
     * @return bool
     */
    public function wechatLoginCheck(Request $request)
    {
        // 获取标记
        $weChatFlag = $request->input('wechat_flag');

        // 为空
        if (empty($weChatFlag)) {
            return $this->response->error('登录失败', 404);
        }

        // 获取ID
        $id = Cache::get(User::LOGIN_WECHAT . $weChatFlag);

        // ID不为空查询用户
        if (!empty($id)) {

            $user = User::where(compact('id'))->first();

            // 用户不存在
            if (empty($user)) {
                return $this->response->error('登录失败', 404);
            }

            \Auth::guard('web')->login($user);


            // 清除COOKIE
            Cookie::forget(User::WECHAT_FLAG);
            Cookie::queue(Cookie::forget(User::WECHAT_FLAG));

            \Log::info('登录成功');
            // 返回登录
            return response()->json(['message' => 'success', 'status_code' => 200, 'url' => $request->session()->get('redirectPath') ?? '/'], 200)
                ->cookie(User::WECHAT_FLAG, '', 0, '', '', false, true);

        }


        // 需要绑定
        $is_need = Cache::get(User::NEED_BIND . $weChatFlag);

        // 未找到需要绑定的标记
        if (empty($is_need)) {
            return $this->response->error('登录失败', 404);
        }

        // 查询到数据跳转绑定页进行绑定
        return response()->json(['message' => 'success', 'status_code' => 201], 200);
    }

    /**
     * 微信用户绑定页面
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function wechatUserBindShow(Request $request)
    {
        // 查询 cookie，如果没有就重新生成一次
        if (!$weChatFlag = $request->cookie(User::WECHAT_FLAG)) {
            return redirect()->to(route('login'));
        }

        // 获取用户信息
        $user = Cache::get(User::NEED_BIND_USER . $weChatFlag);

        // 用户信息不存在
        if (empty($user)) {
            return redirect()->to(route('login'));
        }

        // 返回页面
        return frontend_view('bind', compact('weChatFlag'));
    }


    /**
     * 微信用户绑定
     *
     * @param WxUserBindRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wechatUserBind(WxUserBindRequest $request)
    {
        // 查询 cookie，如果没有就重新生成一次
        if (!$weChatFlag = $request->cookie(User::WECHAT_FLAG)) {
            throw new ResourceException('绑定失败', ['message' => '绑定失败']);
        }

        // 获取验证码，存在即验证，否则错误响应
        if (!($verifyData = \Cache::get($request->input('verification_key')))) {
            throw new ResourceException('验证码不存在', ['verification_key' => '验证码不存在']);
        }

        // 验证码验证失败
        if (!hash_equals((string)$verifyData['code'], (string)$request->input('verification_code'))) {
            throw new ResourceException('验证码错误', ['verification_code' => '验证码不存在']);
        }

        // 获取用户信息
        $info = Cache::get(User::NEED_BIND_USER . $weChatFlag);

        // 不存在
        if (empty($info)) {
            throw new ResourceException('请重新扫描二维码进行绑定', ['message' => '请重新扫描二维码进行绑定']);
        }

        // 获取OPEN_ID
        $open_id = $info['open_id'];

        // 查询用户信息
        $user = User::whereOpenid($open_id)->first();

        // 用户不存在进行创建
        if (empty($user)) {
            $user = $this->createWxUser($info, $request->input('phone'));
        } else {
            $user->phone = $request->input('phone');
            $user->save();
        }

        // 登录
        \Auth::login($user);

        // 清除COOKIE
        Cookie::forget(User::WECHAT_FLAG);
        Cookie::queue(Cookie::forget(User::WECHAT_FLAG));

        // 返回跳转信息
        return response()->json(['url' => $request->session()->get('redirectPath') ?? '/'], 200)
            ->cookie(User::WECHAT_FLAG, '', 0, '', '', false, true);
    }


    /**
     * 获取二维码图片
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getWxPic(Request $request)
    {
        // 查询 cookie，如果没有就重新生成一次
        if (!$weChatFlag = $request->cookie(User::WECHAT_FLAG)) {
            $weChatFlag = Uuid::uuid4()->getHex();
        }

        // 缓存微信带参二维码
        if (!$url = Cache::get(User::QR_URL . $weChatFlag)) {
            // 有效期 30分钟的二维码
            $qrCode = $this->app->qrcode;
            $result = $qrCode->temporary($weChatFlag, $this->cache_time * 60);
            $url = $qrCode->url($result['ticket']);

            Cache::put(User::QR_URL . $weChatFlag, $url, now()->addDay());
        }

        // 自定义参数返回给前端，前端轮询
        return response()->json(compact('url', 'weChatFlag'), 200)
            ->cookie(User::WECHAT_FLAG, $weChatFlag, $this->cache_time);
    }

    /**
     * 微信消息接入（这里拆分函数处理）
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function serve()
    {
        $app = $this->app;

        $app->server->push(function ($message) {
            if ($message) {
                $method = camel_case('handle_' . $message['MsgType']);

                if (method_exists($this, $method)) {
                    $this->openid = $message['FromUserName'];

                    return call_user_func_array([$this, $method], [$message]);
                }

                Log::info('无此处理方法:' . $method);
            }
        });

        return $app->server->serve();
    }

    /**
     * 事件引导处理方法（事件有许多，拆分处理）
     *
     * @param $event
     *
     * @return mixed
     */
    protected function handleEvent($event)
    {
        Log::info('事件参数：', [$event]);

        $method = camel_case('event_' . $event['Event']);
        Log::info('处理方法:' . $method);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], [$event]);
        }

        Log::info('无此事件处理方法:' . $method);
    }


    /**
     * 取消订阅
     *
     * @param $event
     * @return null
     */
    protected function eventUnsubscribe($event)
    {
        return null;
    }


    /**
     * 扫描带参二维码事件
     *
     * @param $event
     * @throws \Throwable
     */
    public function eventSCAN($event)
    {
        $this->eventSubscribe($event);
    }


    /**
     * 订阅
     *
     * @param $event
     *
     * @throws \Throwable
     */
    protected function eventSubscribe($event)
    {// 获取用户OPEN_ID
        $openId = $this->openid;

        $user = User::whereOpenid($openId)->first();

        // 查询到用户但是手机号为空则需要进行绑定手机号的操作
        // 查询到用户并且手机号不为空则不需要进行绑定手机号的操作直接进行登录的操作
        if (!empty($user)) {

            // 手机号不为空
            if (empty($user->phone)) {
                $this->needBind($event, $user->toArray());
                return;
            } else {
                $this->markTheLogin($event, $user->id);
                return;
            }
        }

        // 没有查询到用户
        // 获取微信用户信息
        $wxUser = $this->app->user->get($openId);

        // 用户名处理重复
        $username = StaticUser::formatWxUsername($this->filterEmoji($wxUser['nickname']));

        // 返回信息
        $this->needBind($event, ['guid' => Uuid::uuid4()->getHex(), 'username' => $username, 'open_id' => $openId]);
    }


    /**
     * 标记可登录
     *
     * @param $event
     * @param $id
     */
    public function markTheLogin($event, $id)
    {
        if (empty($event['EventKey'])) {
            return;
        }

        $eventKey = $event['EventKey'];

        // 关注事件的场景值会带一个前缀需要去掉
        if ($event['Event'] == 'subscribe') {
            $eventKey = str_after($event['EventKey'], 'qrscene_');
        }

        Log::info('EventKey:' . $eventKey, [$event['EventKey']]);

        // 标记前端可登陆
        Cache::put(User::LOGIN_WECHAT . $eventKey, $id, now()->addMinute($this->cache_time));
    }


    /**
     * 需要进行绑定账号
     *
     * @param $user
     */
    protected function needBind($event, $user)
    {
        $eventKey = $event['EventKey'];

        // 关注事件的场景值会带一个前缀需要去掉
        if ($event['Event'] == 'subscribe') {
            $eventKey = str_after($event['EventKey'], 'qrscene_');
        }

        Cache::put(User::NEED_BIND . $eventKey, true, now()->addMinutes($this->cache_time));
        Cache::put(User::NEED_BIND_USER . $eventKey, $user, now()->addMinutes($this->cache_time));
    }


    /**
     * 创建微信用户
     *
     * @param $info
     * @param $phone
     * @return mixed
     */
    protected function createWxUser($info, $phone)
    {
        try {

            //写入数据库
            \DB::beginTransaction();

            // 用户
            $user = User::create(array_merge($info, compact('phone')));

            // 用户信息
            $user->profile()->create([]);

            \DB::commit();

            return $user;
        } catch (\Exception $exception) {
            \DB::rollBack();
            report($exception);
        }

    }

    /**
     * 替换掉emoji表情
     *
     * @param $text
     * @param string $replaceTo
     * @return mixed|string
     */
    private function filterEmoji($text, $replaceTo = '?')
    {
        $clean_text = "";
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, $replaceTo, $text);
        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, $replaceTo, $clean_text);
        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, $replaceTo, $clean_text);
        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, $replaceTo, $clean_text);
        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, $replaceTo, $clean_text);
        return $clean_text;
    }
}
