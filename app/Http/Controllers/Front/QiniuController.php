<?php

namespace App\Http\Controllers\Front;

use App\Enums\CloudStorageFileType;
use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Doc;
use App\Models\Image;
use App\Models\Ppt;
use App\Models\Video;
use App\Models\Setting;
use App\Rules\CustomEnumRule;
use App\Services\Uploader;
use Illuminate\Http\Request;
use Qiniu\Http\Error;

class QiniuController extends Controller
{

    private $uploader;

    public function __construct()
    {
        // 获取七牛配置
        if (!($setting = Setting::where('namespace', SettingType::QINIU)->value('value'))) {
            return ajax('200', __('Setting does not exists.'));
        }

        $this->uploader = new Uploader($setting);
    }

    public function token(Request $request)
    {

//        $this->validate($request, [
//            'type' => ['required', new CustomEnumRule(CloudStorageFileType::class)],
//            'hash' => 'required|string'
//        ]);
//
//        // 查询是否已存在文件
//        $model = resolve('App\\Models\\' . studly_case($request->type));
//        if ($media = $model->where('hash', $request->hash)->first()) {
//            return $this->response->item($media, resolve('App\\Http\\Transformers\\' . studly_case($request->type) . 'Transformer'));
//        }
//
//        $prefix = $request->type . '/';
//
//        // 仅当为视频时，会进入私有库之中
//        if ($request->type === CloudStorageFileType::VIDEO) {
//            return $this->response->array($this->uploader->privateToken($prefix));
//        } else {
//            return $this->response->array($this->uploader->token($prefix));
//        }
        return response()->json($this->uploader->token('image/'));
    }

    public function destroy($key)
    {
        $response = $this->uploader->delete($key);
        if ($response instanceof Error) {
            $this->response->error($response->message(), $response->code());
        }

        return $this->response->noContent();
    }

    public function toDatabase(Request $request)
    {
        $this->validate($request, [
            'media_uri' => 'required|string',
            'hash' => 'required|string',
            'type' => ['required', new CustomEnumRule(CloudStorageFileType::class)],
        ]);

        if ($request->type !== CloudStorageFileType::VIDEO && !$stat = $this->uploader->stat($request->media_uri)) {
            $this->response->errorBadRequest(__('Recourse loss.'));
        }

        switch ($request->type) {
            case 'image':
                $media = Image::firstOrCreate($request->only('media_uri'), [
                    'hash' => $request->hash,
                    'length' => $stat['fsize'],
                ]);
                break;
            case 'video':
                // 获取视频长度
                $response = $this->uploader->privateAvInfo($request->media_uri);
                $length = floor($response['format']['duration']);

                // 七牛切片（切片成功与否均保存到数据库之中）
                $response = $this->uploader->persistentVideo($request->media_uri);
                $media = Video::firstOrCreate([
                    'media_uri' => $request->media_uri,
                    'hash' => $request->hash,
                ], ['status' => 'slicing', 'length' => $length]);

                // 当切片失败后
                if (empty($response['id'])) {
                    $media->status = 'unsliced';
                    $media->save();
                    \Log::useFiles(storage_path('logs/qiniu.log'));
                    \Log::error($response);
                    $this->response->error(__('Sliced error.'), 500);
                }
                break;
            case 'audio':
                $media = Audio::firstOrCreate([
                    'media_uri' => $request->media_uri,
                    'hash' => $request->hash,
                    'length' => 0
                ]);
                break;
            case 'ppt':
                $media = Ppt::firstOrCreate([
                    'media_uri' => $request->media_uri,
                    'hash' => $request->hash,
                    'length' => $request->length ?? 0,
                ]);
                break;
            case 'doc':
                $media = Doc::firstOrCreate([
                    'media_uri' => $request->media_uri,
                    'hash' => $request->hash,
                    'length' => $request->length ?? 0,
                ]);
                break;
        }

        return $this->response->item($media, resolve('App\\Http\\Transformers\\' . studly_case($request->type) . 'Transformer'));
    }

    public function persistentStatus()
    {
        $id = request('id', null);
        $response = $this->uploader->persistentStatus($id);

        if ($response instanceof Error) {
            $this->response->error($response->message(), $response->code());
        }

        return $this->response->array(['ret' => $response['ret']]);
    }

    public function avinfo()
    {
        $key = request('key');

        $response = $this->uploader->privateAvInfo($key);

        return $this->response->array(json_decode($response, true));
    }

    /**
     * 七牛切片完成后的回调方法
     */
    public function callBack(Request $request, Video $video)
    {
        $key = $request['inputKey'];

        // 查询课程
        $video = $video->where('media_uri', $key)->first();

        $video->status = 'sliced';
        $video->save();

        $resp = array('ret' => 'success');

        return json_encode($resp);
    }
}
