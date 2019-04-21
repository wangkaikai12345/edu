<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */
    'qiniu_list' => env('QINIU_SPLICE_LIST'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

        // 文件存储库
        'store' => [
            'driver' => 'qiniu',
            'domains' => [
                'default' => env('QINIU_STORE_DOMAINS_DEFAULT'),   //你的七牛域名
                'https' => '',
                'custom' => env('QINIU_STORE_DOMAINS_CUSTOM'),     //你的自定义域名
            ],
            'access_key' => env('QINIU_ACCESS_KEY'),  //AccessKey
            'access' => '',  //AccessKey'
            'secret_key' => env('QINIU_SECRET_KEY'),  //SecretKey
            'bucket' => env('QINIU_STORE_BUCKET'),  //Bucket名字
            'notify_url' => '',  //持久化处理回调地址, 是配置在这里的
        ],

        // 图像存储
        'imgs' => [
            'driver' => 'qiniu',
            'domains' => [
                'default' => env('QINIU_IMGS_DOMAINS_DEFAULT'),   //你的七牛域名
                'https' => '',
                'custom' => env('QINIU_IMGS_DOMAINS_CUSTOM'),     //你的自定义域名
            ],
            'access_key' => env('QINIU_ACCESS_KEY'),  //AccessKey
            'access' => '',  //AccessKey'
            'secret_key' => env('QINIU_SECRET_KEY'),  //SecretKey
            'bucket' => env('QINIU_IMGS_BUCKET'),  //Bucket名字
            'notify_url' => '',  //持久化处理回调地址, 是配置在这里的
        ],

        // 视频私有库, 上传时用
        'lessons' => [
            'driver' => 'qiniu',
            'domains' => [
                'default' => env('QINIU_LESSONS_DOMAINS_DEFAULT'),   //你的七牛域名
                'https' => '',
                'custom' => env('QINIU_LESSONS_DOMAINS_CUSTOM'),     //你的自定义域名
            ],
            'access_key' => env('QINIU_ACCESS_KEY'),  //AccessKey
            'access' => '',  //AccessKey'
            'secret_key' => env('QINIU_SECRET_KEY'),  //SecretKey
            'bucket' => env('QINIU_LESSONS_BUCKET'),  //Bucket名字
            'notify_url' => env('QINIU_LESSONS_DOMAINS_NOTIFY_URL'),  //持久化处理回调地址, 是配置在这里的
        ],

        // 视频共有库, 分片后用
        'videos' => [
            'driver' => 'qiniu',
            'domains' => [
                'default' => env('QINIU_VIDEOS_DOMAINS_DEFAULT'),   //你的七牛域名
                'https' => '',
                'custom' => env('QINIU_VIDEOS_DOMAINS_CUSTOM'),     //你的自定义域名
            ],
            'access_key' => env('QINIU_ACCESS_KEY'),  //AccessKey
            'access' => '',  //AccessKey'
            'secret_key' => env('QINIU_SECRET_KEY'),  //SecretKey
            'bucket' => env('QINIU_VIDEOS_BUCKET'),  //Bucket名字
            'notify_url' => '',  //持久化处理回调地址, 是配置在这里的
        ],

        'qiniu' => [
            'public' => [
                'ak' => env('QINIU_AK'),   // 七牛 accessKey
                'sk' => env('QINIU_SK'),   // 七牛 secretKey
                'queue' => env('QINIU_QUEUE'),   // 持久化处理的队列
                'callback' => env('QINIU_CALLBACK'),  // 七牛队列异步回调函数
                'bucket' => env('QINIU_PUBLIC_BUCKET'),  // 七牛共有库 bucket
                'slice_bucket' => env('QINIU_SLICE_BUCKET'),// 七牛切片库
                'domain' => env('QINIU_PUBLIC_DOMAIN'),  // 七牛共有库域名 public.xxx.com
                'slice_domain' => env('QINIU_SLICE_DOMAIN'),  // 七牛切片库域名 public.xxx.com
            ],
            'private' => [
                'ak' => env('QINIU_AK'),   // 七牛 accessKey
                'sk' => env('QINIU_SK'),   // 七牛 secretKey
                'queue' => env('QINIU_QUEUE'),   // 持久化处理的队列
                'callback' => env('QINIU_CALLBACK'),  // 七牛队列异步回调函数
                'bucket' => env('QINIU_PRIVATE_BUCKET'), // 七牛私有库 bucket
                'slice_bucket' => env('QINIU_SLICE_BUCKET'),// 七牛切片库
                'domain' => env('QINIU_PRIVATE_DOMAIN'), // 七牛私有库域名 private.xxx.com
                'slice_domain' => env('QINIU_SLICE_DOMAIN'), // 七牛切片库域名 private.xxx.com
            ],

            // 七牛云配置
            'driver' => 'qiniu',
            'domains' => [
                'default' => env('QINIU_VIDEOS_DOMAINS_DEFAULT'), //你的七牛域名
                'https' => '',         //你的HTTPS域名
                'custom' => env('QINIU_VIDEOS_DOMAINS_CUSTOM'),               //Useless 没啥用，请直接使用上面的 default 项
            ],
            'access_key' => env('QINIU_ACCESS_KEY'),  //AccessKey
            'secret_key' => env('QINIU_SECRET_KEY'),  //SecretKey
            'bucket' => env('QINIU_VIDEOS_BUCKET'),  //Bucket名字
            'notify_url' => env('QINIU_LESSONS_DOMAINS_NOTIFY_URL'),  //持久化处理回调地址
            'access' => 'public', //空间访问控制 public 或 private
        ],
    ],
];
