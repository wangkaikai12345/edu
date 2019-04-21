<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchaController extends Controller
{
    /**
     * @SWG\Tag(name="web/verification",description="验证码")
     */

    /**
     * @SWG\Post(
     *  path="/captcha",
     *  tags={"web/verification"},
     *  summary="获取图形验证码",
     *  description="直接获取，不依赖于邮箱、手机等，过期时间 2 分钟",
     *  @SWG\Response(response=201,description="ok",@SWG\Schema(
     *      @SWG\Property(property="captcha_key",description="图形验证码 Key"),
     *      @SWG\Property(property="expired_at",description="过期时间"),
     *      @SWG\Property(property="captcha_image_content",description="图形验证码二进制图片")
     *  )),
     * )
     */
    public function store(CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-' . str_random(15);

        // 创建验证码
        $captcha = $captchaBuilder->build();

        $expiredAt = now()->addMinutes(2);

        \Cache::put($key, ['code' => $captcha->getPhrase()], $expiredAt);
        
        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
