<?php

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Facades\App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class EmailBindController extends Controller
{
    /**
     * @SWG\Tag(name="web/email",description="邮箱")
     */

    /**
     * 绑定成功后跳转的前台页面
     *
     * @var string
     */
    public $bindRedirectUri = '/email/confirm';

    /**
     * @SWG\Get(
     *  path="/email",
     *  tags={"web/email"},
     *  summary="邮箱绑定/更新邮箱",
     *  description="",
     *  @SWG\Parameter(name="code",in="query",type="string",description="激活码"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function update(Request $request)
    {
        $row = PasswordReset::where('token', $request->code)->first();

        $emailSetting = Setting::namespace(SettingType::EMAIL);

        $expiredMinutes = data_get($emailSetting, 'expires', 24 * 60);

        if (!$row || $row->created_at->addMinutes($expiredMinutes)->gt(now())) {
            $verified = 0;
        } else {
            $user = User::where('email', $row->email)->first();
            $user->is_email_verified = true;
            $user->save();
            $verified = 1;
        }

        $queryString = http_build_query(['is_email_verified' => $verified]);

        return redirect()->to($this->bindRedirectUri . '?' . $queryString);
    }
}
