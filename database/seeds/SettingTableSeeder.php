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
                'ak' => env('QINIU_ACCESS_KEY'),
                'sk' => env('QINIU_SECRET_KEY'),
                'queue' => env('QINIU_QUEUE'),
                'callback' => env('QINIU_CALLBACK'),
                'public_bucket' => env('QINIU_IMGS_BUCKET'),
                'public_domain' => env('QINIU_IMGS_DOMAINS_DEFAULT'),
                'private_bucket' => env('QINIU_LESSONS_BUCKET'),
                'private_domain' => env('QINIU_LESSONS_DOMAINS_DEFAULT'),
                'slice_bucket' => env('QINIU_VIDEOS_BUCKET'),
                'slice_domain' => env('QINIU_VIDEOS_DOMAINS_DEFAULT'),
            ]
        ]);

        // 短信
        \App\Models\Setting::create([
            'namespace' => \App\Enums\SettingType::SMS,
            'value' => [
                'ak' => env('ACCESS_KEY_ID'),
                'sk' => env('ACCESS_KEY_SECRET'),
                'sign_name' => env('SIGN_NAME'),
                'register_template_code' => env('REGISTER_TEMPLATE_CODE'),
                'password_template_code' => env('PASSWORD_TEMPLATE_CODE'),
                'login_template_code' => env('PASSWORD_TEMPLATE_CODE'),
                'verify_template_code' => env('VERIFY_TEMPLATE_CODE'),
                'expires' => 10,
                'variable' => [
                    'product' => env('APP_NAME')
                ]
            ]
        ]);

        // 网站配置
        \App\Models\Setting::create([
            'namespace' => \App\Enums\SettingType::SITE,
            'value' => [
                'copyright' => '@2018',
                'description' => '测试描述信息',
                'domain' => '测试域名',
                'email' => '测试管理员邮箱',
                'stat_code' => '',
                'icp' => '@2019',
                'sub_title' => '测试副标题',
                'title' => '测试主标题',
                'logo' => null,
                'ico' => null,
                'keywords' => '测试关键字',
            ]
        ]);

        // 微信支付
        \App\Models\Setting::create([
            'namespace' => \App\Enums\SettingType::WECHAT_PAY,
            'value' => [
                'appid' => env('WECHAT_PAY_APPID'),
                'app_id' => env('WECHAT_PAY_APP_ID'),
                'miniapp_id' => env('WECHAT_PAY_MINIAPP_ID'),
                'mch_id' => env('WECHAT_PAY_MCH_ID'),
                'key' => env('WECHAT_PAY_KEY'),
                'cert_client' => storage_path('app/certs/cert_client.pem'),
                'cert_key' => storage_path('app/certs/cert_key.pem'),
                'on' => true,
            ]
        ]);

        // 邮件
        \App\Models\Setting::create([
            'namespace' => \App\Enums\SettingType::EMAIL,
            'value' => [
                'driver' => 'smtp',
                'host' => env('MAIL_HOST'),
                'port' => env('MAIL_PROT'),
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'encryption' => env('MAIL_ENCRYPTION'),
                'from' => [
                    'address' => env('MAIL_FROM_ADDRESS'),
                    'name' => env('MAIL_FROM_NAME'),
                ],
                'expires' => 1440
            ]
        ]);
    }
}
