<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\SettingType;
use App\Http\Requests\Admin\SettingRequest;
use Facades\App\Models\Setting;
use App\Http\Controllers\Controller;
use Cache;
use Storage;

class SettingController extends Controller
{

    /**
     * 网站配置
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->to(route('backstage.settings.show', ['namespace' => 'site']));
    }

    /**
     * 详情
     *
     * @param $namespace
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($namespace)
    {
        if (!in_array($namespace, SettingType::getValues())) {
            $this->response->errorBadRequest(__('Setting does not exists'));
        }

        $domain = fileDomain();

        $setting = Setting::namespace($namespace);

        return view('admin.setting.' . $namespace, compact('setting', 'domain', 'namespace'));
    }


    /**
     * 更新
     *
     * @param $namespace
     * @param SettingRequest $request
     * @return \Dingo\Api\Http\Response
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
            $setting = new \App\Models\Setting();
            $fields = $setting->namespace($namespace);
            $setting->namespace = $namespace;
            $setting->value = array_merge($fields, $this->trimDomain($request->all()));
            $setting->save();
        }

        return $this->response->noContent();
    }

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

    private function trimDomain(array $params)
    {
        $domains = ['public_domain', 'private_domain', 'slice_domain', 'domain', 'return_url'];

        return collect($params)->map(function ($value, $key) use ($domains) {
            return in_array($key, $domains) ? trim($value, '/') : $value;
        })->toArray();
    }
}
