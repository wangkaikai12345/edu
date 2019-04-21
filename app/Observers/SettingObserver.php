<?php

namespace App\Observers;

use App\Models\Setting;
use Cache;

class SettingObserver
{
    /**
     * 创建/更新
     */
    public function saved(Setting $setting)
    {
        Cache::forget(config('setting.cache.key') . $setting->namespace);
    }
}
