<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/5/12
 * Time: 15:12
 */

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;
use Qiniu\Storage\BucketManager;

class Uploader
{
    /**
     * @var string
     */
    private $ak;
    /**
     * @var string
     */
    private $sk;
    /**
     * @var string
     */
    private $publicBucket;
    /**
     * @var string
     */
    private $privateBucket;
    /**
     * @var string
     */
    private $sliceBucket;
    /**
     * @var string
     */
    private $publicDomain;
    /**
     * @var string
     */
    private $privateDomain;
    /**
     * @var string
     */
    private $sliceDomain;
    /**
     * @var string
     */
    private $queue;
    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var BucketManager
     */
    private $manager;
    /**
     * @var string
     */
    private $policy = [
        'mime_type' => '${mimeType}',
        'ext' => '${ext}',
        'key' => '${key}',
        'bucket' => '${bucket}',
        'size' => '${fsize}'
    ];

    public function __construct(array $config)
    {
        $this->ak = $config['ak'];
        $this->sk = $config['sk'];
        $this->queue = $config['queue'];
        $this->callback = $config['callback'];
        $this->publicBucket = $config['public_bucket'];
        $this->publicDomain = $config['public_domain'];
        $this->privateBucket = $config['private_bucket'];
        $this->privateDomain = $config['private_domain'];
        $this->sliceBucket = $config['slice_bucket'];
        $this->sliceDomain = $config['slice_domain'];
        $this->auth = new Auth($this->ak, $this->sk);
        $this->manager = new BucketManager($this->auth);
    }

    /**
     * 获取切片Token
     *
     * @return array
     */
    public function privateToken($prefix)
    {
        $key = $prefix . date('Y-m-d') . '-' . str_random(8);

        $token = $this->auth->uploadToken($this->privateBucket, $key, 3600, $this->policy);

        return [
            'domain' => $this->sliceDomain,
            'media_uri' => $key,
            'token' => $token
        ];
    }

    /**
     * 获取Token
     *
     * @return array
     */
    public function token($prefix)
    {
        $key = $prefix . date('Y-m-d') . '-' . str_random(8);

        $token = $this->auth->uploadToken($this->publicBucket, $key, 3600, $this->policy);

        return [
            'domain' => $this->publicDomain,
            'media_uri' => $key,
            'token' => $token
        ];
    }

    /**
     * 获取资源元信息
     *
     * @param $key
     * @param bool $isPublic
     * @return boolean
     */
    public function stat($key, $isPublic = true)
    {
        $arr = $this->manager->stat($isPublic ? $this->publicBucket : $this->privateBucket, $key);

        return empty($arr[0]) ? false : $arr[0];
    }

    /**
     * 获取公共信息接口
     */
    public function avInfo($key)
    {
        $url = $this->publicDomain . '/' . $key . '?avinfo';

        try {
            $client = new Client(['timeout' => 2.0]);
            $response = $client->request('GET', $url);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            if ($response->getStatusCode() == 404) {
                throw new \Symfony\Component\HttpKernel\Exception\HttpException(400, '资源丢失');
            }
            \Log::useFiles(storage_path('logs/qiniu.log'));
            \Log::error($exception);
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(400, '云存储服务异常');
        }

        return json_decode($response->getBody()->getContents(), true);
    }



    /**
     * 获取私有信息接口
     */
    public function privateAvInfo($key)
    {
        $url = $this->auth->privateDownloadUrl($this->privateDomain . '/' . $key . '?avinfo', 600);

        try {
            $client = new Client(['timeout' => 2.0]);
            $response = $client->request('GET', $url);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            if ($response->getStatusCode() == 404) {
                throw new \Symfony\Component\HttpKernel\Exception\HttpException(400, '资源丢失');
            }
            \Log::useFiles(storage_path('logs/qiniu.log'));
            \Log::error($exception);
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(400, '云存储服务异常');
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 视频转码
     *
     * @param $key
     * @return array
     */
    public function persistentVideo($key)
    {
        // 若文件已经存在，是否强制覆盖
        $force = false;

        // 初始化切片方法
        $pfop = new PersistentFop($this->auth);

        // 另存 bucket 和 key
        $savekey = \Qiniu\base64_urlSafeEncode($this->sliceBucket . ':' . $key);

        // 碎片名字  newKey000001 ...
        $hlsName = \Qiniu\base64_urlSafeEncode($key . '/$(count)');

        // 要进行转码的转码操作。这里是视频切片，其他处理方式参考： http://developer.qiniu.com/docs/v6/api/reference/fop/av/avthumb.html
        $fops = 'avthumb/m3u8/noDomain/1/segtime/5/aq/4/vb/240k/pattern/' . $hlsName . '|' . 'saveas/' . $savekey;

        list($id, $err) = $pfop->execute($this->privateBucket, $key, $fops, $this->queue, $this->callback, $force);

        return [
            'id' => $id, 'error' => $err
        ];
    }

    /**
     * 视频转码状态
     */
    public function persistentStatus($id)
    {
        $pfop = new PersistentFop($this->auth);

        list($ret, $err) = $pfop->status($id);

        return [
            'ret' => $ret,
            'err' => $err
        ];
    }

    /**
     * 删除
     *
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        $response = $this->manager->delete($this->publicBucket, $key);

        return $response;
    }
}