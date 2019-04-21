<?php

namespace App\Http\Middleware;

use App\Jobs\Logger;
use Closure;
use Ip;
use Jenssegers\Agent\Agent;

class LoggerMiddleware
{
    /**
     * 排除记录之外的路由
     *
     * @var array
     */
    public $except = [
        'horizon',
        'api-docs',
        'api/unread-count',
        'api/admin/certs',
        'backstage-admin/certs',
        'manage/chapters/*/tasks/store',
    ];

    /**
     * 不记录的错误状态码
     *
     * @var array
     */
    public $exceptStatusCode = [400, 401, 404, 422, 429, 500];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // 路由白名单过滤
        foreach ($this->except as $uri) {
            if (str_contains($request->path(), $uri)) {
                return $response;
            }
        }

        // 状态码白名单过滤
        if (in_array($response->getStatusCode(), $this->exceptStatusCode)) {
            return $response;
        }

        $agentService = new Agent();

        $requestTime = now();

        if ($response->getStatusCode() !== 500) {
            // 错误日志不需要添加到数据库中
            $data['response_content'] = $response->getContent();
        }

        $userAgent = $request->userAgent();

        // 设备
        $device = $agentService->device();
        // 系统及版本号
        $platform = $agentService->platform();
        $platform_version = $agentService->version($platform);
        // 浏览器及版本号
        $browser = $agentService->browser();
        $browser_version = $agentService->version($browser);
        // 根据ip获取地域信息
        $area = Ip::find($request->ip());
//        $area = implode(' ', $area);

        $data = [
            // 请求
            'user_id' => $request->user()->id ?? 0,
            'method' => $request->method(),
            'root' => $request->root(),
            'url' => $request->url(),
            'full_url' => strlen($request->fullUrl()) > 746 ? $request->fullUrl() : substr($request->fullUrl(), 0, 745),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'area' => $area,
            'params' => $request->all(),
            'referer' => $request->headers->get('referer'),
            'user_agent' => $userAgent,
            'request_headers' => $request->headers->all(),
            'request_time' => $requestTime,
            'device' => $device,
            'browser' => $browser,
            'browser_version' => $browser_version,
            'platform' => $platform,
            'platform_version' => $platform_version,
            'is_mobile' => $agentService->isMobile(),
            // 响应
            'status_code' => $response->getStatusCode(),
            'response_headers' => $response->headers->all(),
            'response_time' => $response->getDate(),
        ];

        if ($request->url() === config('pay.alipay.notify_url')) {
            // 转码（支付宝包默认返回的 GBK，转换为UFT-8）
            $data['params']['subject'] = isset($data['params']['subject']) ? iconv('GB2312', 'UTF-8', $data['params']['subject']) : '';
        }

        Logger::dispatch($data);

        return $response;
    }
}
