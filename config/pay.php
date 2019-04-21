<?php

return [
    'alipay' => [
        'notify_url' => env('ALIPAY_NOTIFY_URL'),
//        'return_url' => env('ALIPAY_RETURN_URL'),
//        'app_id' => env('ALIPAY_APP_ID'),
//        'private_key' => env('ALIPAY_PRIVATE_KEY'),
//        'ali_public_key' => env('ALIPAY_ALI_PUBLIC_KEY'),       // 加密方式： **RSA2**
        'log' => [ // optional
            'file' => storage_path('/logs/alipay.log'),
            'level' => 'debug',
        ],
    ],

    'wechat' => [
//        'appid' => env('WECHAT_PAY_APPID'), // APP APPID
//        'app_id' => env('WECHAT_PAY_APP_ID'), // 公众号 APPID
//        'miniapp_id' => env('WECHAT_PAY_MINIAPP_ID'), // 小程序 APPID
//        'mch_id' => env('WECHAT_PAY_MCH_ID'),
//        'key' => env('WECHAT_PAY_KEY'),
        'notify_url' => env('WECHAT_PAY_NOTIFY_URL'),
//        'cert_client' => storage_path('cert/apiclient_cert.pem'), // optional，退款等情况时用到
//        'cert_key' => storage_path('cert/apiclient_key.pem'),// optional，退款等情况时用到

        'log' => [ // optional
            'file' => storage_path('/logs/wechat.log'),
            'level' => 'debug',
        ],
        // 'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
    ],

];