<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 七牛
        \App\Models\Setting::create([
            'namespace' => \App\Enums\SettingType::QINIU,
            'value' => [
                'driver' => 'qiniu',
                'ak' => 'n4zzJj5YXIbGdhk-oZoGJsqL6xBj2SdT353LJdWY',
                'sk' => 'BETtIctD3j6WXJjsUxe7EM5g-jBX5WG9ci7Ppovr',
                'queue' => 'luwnto',
                'callback' => 'test.ydma.cn/qiniucallback',
                'public_bucket' => 'yun-images',
                'public_domain' => 'qiniu-img.luwnto.com',
                'private_bucket' => 'yun-teacher',
                'private_domain' => 'qiniu-lesson.luwnto.com',
                'slice_bucket' => 'yun-slice',
                'slice_domain' => 'qiniu-slice.luwnto.com',
            ]
        ]);

        // 短信
//        \App\Models\Setting::create([
//            'namespace' => \App\Enums\SettingType::SMS,
//            'value' => [
//                'ak' => env('ACCESS_KEY_ID'),
//                'sk' => env('ACCESS_KEY_SECRET'),
//                'sign_name' => env('SIGN_NAME'),
//                'register_template_code' => env('REGISTER_TEMPLATE_CODE'),
//                'password_template_code' => env('PASSWORD_TEMPLATE_CODE'),
//                'login_template_code' => env('PASSWORD_TEMPLATE_CODE'),
//                'verify_template_code' => env('VERIFY_TEMPLATE_CODE'),
//                'expires' => 10,
//                'variable' => [
//                    'product' => env('APP_NAME')
//                ]
//            ]
//        ]);
//
//        // 网站配置
//        \App\Models\Setting::create([
//            'namespace' => \App\Enums\SettingType::SITE,
//            'value' => [
//                'copyright' => '@2018',
//                'description' => '测试描述信息',
//                'domain' => '测试域名',
//                'email' => '测试管理员邮箱',
//                'stat_code' => '',
//                'icp' => '@2019',
//                'sub_title' => '测试副标题',
//                'title' => '测试主标题',
//                'logo' => null,
//                'ico' => null,
//                'keywords' => '测试关键字',
//            ]
//        ]);
//
//        // 微信支付
//        \App\Models\Setting::create([
//            'namespace' => \App\Enums\SettingType::WECHAT_PAY,
//            'value' => [
//                'appid' => env('WECHAT_PAY_APPID'),
//                'app_id' => env('WECHAT_PAY_APP_ID'),
//                'miniapp_id' => env('WECHAT_PAY_MINIAPP_ID'),
//                'mch_id' => env('WECHAT_PAY_MCH_ID'),
//                'key' => env('WECHAT_PAY_KEY'),
//                'cert_client' => storage_path('app/certs/cert_client.pem'),
//                'cert_key' => storage_path('app/certs/cert_key.pem'),
//                'on' => true,
//            ]
//        ]);
//
//        // 邮件
//        \App\Models\Setting::create([
//            'namespace' => \App\Enums\SettingType::EMAIL,
//            'value' => [
//                'driver' => 'smtp',
//                'host' => env('MAIL_HOST'),
//                'port' => env('MAIL_PROT'),
//                'username' => env('MAIL_USERNAME'),
//                'password' => env('MAIL_PASSWORD'),
//                'encryption' => env('MAIL_ENCRYPTION'),
//                'from' => [
//                    'address' => env('MAIL_FROM_ADDRESS'),
//                    'name' => env('MAIL_FROM_NAME'),
//                ],
//                'expires' => 1440
//            ]
//        ]);
    }
}
