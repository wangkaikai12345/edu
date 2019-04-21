<?php

namespace App\Http\Controllers\Front\Manage;

use App\Enums\SettingType;
use App\Models\File;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Qiniu\Auth;
use Facades\App\Models\Setting;

class QiniuController extends Controller
{

    public $config;

    public function __construct()
    {
        $this->config = Setting::namespace(SettingType::QINIU);
    }
    /**
     * 返回前端上传需要的token
     */
    public function imgToken()
    {
        $auth = new Auth($this->config['ak'], $this->config['sk']);

        // 生成上传Token
        $token = $auth->uploadToken($this->config['public_bucket']);

        return ['uptoken'=>$token];
    }

    /**
     * 学员作业文件zip
     *
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function homeworkToken()
    {

        $auth = new Auth($this->config['ak'], $this->config['sk']);
        // 生成上传Token
        $token = $auth->uploadToken($this->config['public_bucket']);
        $data = [
            'token' => $token,
            'domain' => $this->config['public_domain'],
        ];
        return ajax('200', '参数返回成功', $data);
    }

    /**
     * 返回前端上传需要的token, 并验证hash
     */
    public function upTokenHash(Request $request)
    {
        $hash = $request->hash;
        // 如果参数中有hash, 验证文件是否存在, 如果存在, 那么直接返回
        if (!empty($hash)) {
            $checkFile = File::where('hash', $hash)->first();
            if (!empty($checkFile)) {
                return ajax('412', '文件已存在', $checkFile);
            }
        }

        $type = $request->type;

        if ($type == 'video'|| $type == 'audio') {  // 音视频私有库
            $bucket = $this->config['private_bucket'];
            $domain = $this->config['private_domain'];
        } else if ($type == 'doc' || $type == 'ppt' || $type == 'zip' ) {
            $bucket = $this->config['public_bucket'];
            $domain = $this->config['public_domain'];
        } else if ($type == 'img') {
            $bucket = $this->config['public_bucket'];
            $domain = $this->config['public_domain'];
        } else {
            return ajax('400', '非法类型');
        }

        $auth = new Auth($this->config['ak'], $this->config['sk']);
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        $data = [
            'key' => $hash,
            'token' => $token,
            'domain' => http_format($domain),
        ];
        return ajax('200', '参数返回成功', $data);
    }

    /**
     * 七牛音视频切片回调
     */
    public function sliceCallback(Request $request, Video $video)
    {
        if ($request['code'] == 0) {
            $video->where('media_uri', $request['inputKey'])->update(['status' => 'sliced']);
        }
    }

    /**
     * 返回七牛加密key
     */
    public function hlsKey()
    {
        if (auth('web')->id()) {
            return response()->download(app_path('Tools/hls128.key'));
        } else {
            abort(404);
        }
    }
}
