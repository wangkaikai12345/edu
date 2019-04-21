<?php

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use Facades\App\Models\Setting;

class SettingController extends Controller
{
    /**
     * @SWG\Tag(name="web/setting",description="配置信息")
     */

    /**
     * @SWG\Get(
     *  path="/settings/site",
     *  tags={"web/setting"},
     *  summary="网站信息配置",
     *  @SWG\Response(response=200,description="ok",@SWG\Schema(ref="#/definitions/SettingSite"))
     * )
     */
    public function site()
    {
        $item = Setting::namespace(SettingType::SITE);

        return $this->response->array($item);
    }

    /**
     * @SWG\Get(
     *  path="/settings/login",
     *  tags={"web/setting"},
     *  summary="登录限制配置",
     *  @SWG\Response(response=200,description="ok",@SWG\Schema(ref="#/definitions/SettingLogin"))
     * )
     */
    public function login()
    {
        $item = Setting::namespace(SettingType::LOGIN);

        return $this->response->array($item);
    }

    /**
     * @SWG\Get(
     *  path="/settings/register",
     *  tags={"web/setting"},
     *  summary="注册限制配置",
     *  @SWG\Response(response=200,description="ok",@SWG\Schema(ref="#/definitions/SettingRegister"))
     * )
     */
    public function register()
    {
        $item = Setting::namespace(SettingType::REGISTER);

        return $this->response->array($item);
    }
}
