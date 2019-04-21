<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SettingType;
use App\Http\Requests\Admin\SettingRequest;
use Facades\App\Models\Setting;
use App\Http\Controllers\Controller;
use Cache;
use Storage;

class SettingController extends Controller
{
    /**
     * @SWG\Tag(name="admin/setting",description="系统设置")
     */

    /**
     * @SWG\Get(
     *  path="/admin/settings/{namespace}",
     *  tags={"admin/setting"},
     *  summary="详情",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",required=true,name="namespace",enum={"ali_pay","avatar","email","login","message","qiniu","register","site","sms","wechat_pay","header_nav","footer_nav","friend_link"},type="string",required=true,description="系统配置的命名空间（分类标识）：支付宝、头像、邮箱、登录、私信、七牛、注册、站点、短信、微信支付、头部导航、底部导航、友链"),
     *  @SWG\Response(response=200,description="请根据命名空间查询对应的 Model，即返回值"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show($namespace)
    {
        if (!in_array($namespace, SettingType::getValues())) {
            $this->response->errorBadRequest(__('Setting does not exists'));
        }

        return $this->response->array(Setting::namespace($namespace));
    }

    /**
     * @SWG\Put(
     *  path="/admin/settings/{namespace}",
     *  tags={"admin/setting"},
     *  summary="更新",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",required=true,name="namespace",enum={"ali_pay","avatar","email","login","message","qiniu","register","site","sms","wechat_pay","header_nav","footer_nav","friend_link"},type="string",required=true,description="系统配置的命名空间（分类标识）：支付宝、头像、邮箱、登录、私信、七牛、注册、站点、短信、微信支付、头部导航、底部导航、友链"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update($namespace, SettingRequest $request)
    {
        if (!in_array($namespace, SettingType::getValues())) {
            $this->response->errorBadRequest(__('Setting does not exists'));
        }

        $item = Setting::where('namespace', $namespace)->first();

        // 存在即更新，否则即添加
        if (in_array($namespace, [SettingType::HEADER_NAV, SettingType::FOOTER_NAV, SettingType::FRIEND_LINK]) && $item) {
            $item->value = $request->all();
            $item->save();
        } else if (in_array($namespace, [SettingType::HEADER_NAV, SettingType::FOOTER_NAV, SettingType::FRIEND_LINK]) && !$item) {
            $item = new \App\Models\Setting();
            $item->namespace = $namespace;
            $item->value = $request->all();
            $item->save();
        } else if ($item) {
            $item->value = array_merge($item->value, $request->all());
            $item->save();
        } else {
            $setting            = new \App\Models\Setting();
            $fields             = $setting->namespace($namespace);
            $setting->namespace = $namespace;
            $setting->value     = array_merge($fields, $this->trimDomain($request->all()));
            $setting->save();
        }

        return $this->response->noContent();
    }

    /**
     * @SWG\Delete(
     *  path="/admin/settings/{namespace}",
     *  tags={"admin/setting"},
     *  summary="配置清空",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(in="path",required=true,name="namespace",enum={"ali_pay","avatar","email","login","message","qiniu","register","site","sms","wechat_pay","header_nav","footer_nav","friend_link"},type="string",required=true,description="系统配置的命名空间（分类标识）：支付宝、头像、邮箱、登录、私信、七牛、注册、站点、短信、微信支付、头部导航、底部导航、友链"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function destroy($namespace)
    {
        $item = Setting::where(compact('namespace'))->firstOrFail();

        $item->delete();

        // 删除微信证书
        if ($namespace == SettingType::WECHAT_PAY) {
            Storage::disk('local')->delete(['certs/cert_client.pem', 'certs/cert_key.pem']);
        }

        Cache::forget(config('setting.cache.key') . $namespace);

        return $this->response->noContent();
    }

    /**
     * 移除 domain 的路径符
     *
     * @param $params
     */
    private function trimDomain(array $params)
    {
        $domains = ['public_domain', 'private_domain', 'slice_domain', 'domain', 'return_url'];

        return collect($params)->map(function ($value, $key) use ($domains) {
            return in_array($key, $domains) ? trim($value, '/') : $value;
        })->toArray();
    }
}
