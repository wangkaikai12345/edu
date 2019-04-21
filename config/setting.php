<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 缓存配置
    |--------------------------------------------------------------------------
     */
    'cache' => [
        /**
         * 缓存键
         */

        'key' => 'setting:',

        /**
         * 缓存时间,单位：分钟
         */

        'expired' => 1440
    ],

    'default' => [
        /*
        |--------------------------------------------------------------------------
        | 七牛云存储服务
        |--------------------------------------------------------------------------
        |
        | ak                七牛 AK
        | sk                七牛 SK
        | queue             媒体队列
        | callback          回调地址
        | public_bucket     公库
        | public_domain     公库域名
        | private_bucket    私库
        | private_domain    私库域名
        | slice_bucket      切片库
        | slice_domains     切片域名
        |
        */

        'qiniu' => [
            'driver' => null,
            'ak' => null,
            'sk' => null,
            'queue' => null,
            'callback' => null,
            'public_bucket' => null,
            'public_domain' => null,
            'private_bucket' => null,
            'private_domain' => null,
            'slice_bucket' => null,
            'slice_domain' => null,
        ],

        /*
        |--------------------------------------------------------------------------
        | 短信服务
        |--------------------------------------------------------------------------
        |
        | ak                    AK
        | sk                    SK
        | sign_name             签名
        | register_template_code注册验证模板
        | password_template_code修改密码模板
        | login_template_code   登录验证模板
        | verify_template_code  身份验证模板
        | expires               短信有效时长
        | variable              模板变量数组，你可以自定义变量（但需要注意，code 为验证码，无法改变）
        |
        */
        'sms' => [
            'ak' => null,
            'sk' => null,
            'sign_name' => null,
            'register_template_code' => null,
            'password_template_code' => null,
            'login_template_code' => null,
            'verify_template_code' => null,
            'expires' => 10,
            'variable' => [
                'product' => '产品名称'
            ]
        ],

        /*
        |--------------------------------------------------------------------------
        | 站点信息
        |--------------------------------------------------------------------------
        |
        | copyright         版权
        | description       描述
        | domain            域名
        | email             邮箱
        | stat_code         统计代码
        | icp               ICP 版权
        | sub_title         副标题
        | title             名称
        |
        */
        'site' => [
            'copyright' => '@2018',
            'description' => '让学习成为一种习惯',
            'domain' => 'http://test.ydma.cn',
            'email' => 'baronbool92@gmail.com',
            'stat_code' => '你的统计代码',
            'icp' => '@2018',
            'sub_title' => '副标题',
            'title' => '网站名称',
            'logo' => null,
            'ico' => null,
        ],

        /*
        |--------------------------------------------------------------------------
        | 微信支付
        |--------------------------------------------------------------------------
        |
        | appid         APP_ID
        | app_id        公众号ID
        | miniapp_id    小程序ID
        | mch_id        商户ID
        | key           密钥
        | cert          证书
        | cert_key      证书 key
        | notify_url    回调地址
        |
        */

        'wechat_pay' => [
            'appid' => null,
            'app_id' => null,
            'miniapp_id' => null,
            'mch_id' => null,
            'key' => null,
            'cert_client' => null,
            'cert_key' => null,
            'on' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | 支付宝支付
        |--------------------------------------------------------------------------
        |
        | app_id            支付宝 APP_ID
        | private_key       支付宝 PRIVATE_KEY
        | api_public_key    支付宝 API_PUBLIC_KEY
        | notify_url        异步回调地址
        |
        */

        'ali_pay' => [
            'app_id' => null,
            'private_key' => null,
            'ali_public_key' => null,
            'return_url' => null,
            'on' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | 邮件服务
        |--------------------------------------------------------------------------
        |
        | driver        驱动
        | host          地址
        | port          端口
        | username      账户
        | password      密码
        | encryption    加密
        | from[address] 来源地址
        | from[name]    来源名称
        | expires       邮件有效时长（单位：分钟）
        |
        */
        'email' => [
            'driver' => 'smtp',
            'host' => null,
            'port' => 465,
            'username' => null,
            'password' => null,
            'encryption' => 'ssl',
            'from' => [
                'address' => null,
                'name' => null
            ],
            'expires' => 1440
        ],

        /*
        |--------------------------------------------------------------------------
        | 官网头部导航
        |--------------------------------------------------------------------------
        |
        |
        |
        */
        'header_nav' => [
            [
                'key' => '0-1',
                'link' => '/course',
                'name' => '头部导航',
                'status' => true,
                'target' => true,
            ],
            [
                'key' => '0-2',
                'link' => '/course',
                'name' => '可以对此项进行自定义',
                'status' => true,
                'target' => true,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 官网底部导航
        |--------------------------------------------------------------------------
        |
        |
        |
        */
        'footer_nav' => [
            [
                'key' => '0-1',
                'link' => 'http://www.ydma.cn',
                'name' => '底部导航',
                'status' => true,
                'target' => true,
            ],
            [
                'key' => '0-2',
                'link' => 'http://www.ydma.cn',
                'name' => '可以对此项进行自定义',
                'status' => true,
                'target' => true,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 友链
        |--------------------------------------------------------------------------
        |
        |
        |
        */
        'friend_link' => [
            [
                'key' => '0-1',
                'link' => 'http://www.ydma.cn',
                'name' => '友链',
                'status' => true,
                'target' => true,
            ],
            [
                'key' => '0-2',
                'link' => 'http://www.ydma.cn',
                'name' => '可以对此项进行自定义',
                'status' => true,
                'target' => true,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | 注册配置
        |--------------------------------------------------------------------------
        |
        | register_mode                 注册模式 : email|phone|all|closed
        | email_title                   邮件激活标题
        | email_content                 邮件激活内容
        | register_limit                注册接口限制限制次数，限制次数/每分钟
        | register_expires              注册接口限制分钟，限制次数/每分钟
        | is_sent_welcome_notification  是否发送欢迎通知
        | welcome_title                 通知标题
        | welcome_content               通知内容
        */
        'register' => [
            'register_mode' => 'all',
            'email_title' => 'default',
            'email_content' => 'default',
            'register_limit' => 3,
            'register_expires' => 60 * 24,
            'is_sent_welcome_notification' => true,
            'welcome_title' => '',
            'welcome_content' => '',
        ],

        /*
        |--------------------------------------------------------------------------
        | 登录配置
        |--------------------------------------------------------------------------
        |
        | login_mode                            登录模式：email|phone|all|closed
        | is_limit_user                         开启登录账户限制
        | is_limit_ip                           开启登录IP 限制
        | password_error_times_for_user         针对用户的最大错误次数
        | password_error_times_for_ip           针对IP的最大错误次数
        | expires                               限制登录时长，单位：分钟
        | only_allow_verified_email_to_login    是否仅允许已验证邮箱登录: true|false
        |
        */
        'login' => [
            'login_mode' => 'all',
            'is_wx_login' => false,
            'is_limit_ip' => false,
            'is_limit_user' => true,
            'password_error_times_for_user' => 5,
            'password_error_times_for_ip' => 5,
            'expires' => 60,
            'only_allow_verified_email_to_login' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | 默认头像配置
        |--------------------------------------------------------------------------
        |
        | image     头像地址
        |
        */
        'avatar' => [
            'image' => null,
        ],

        /*
        |--------------------------------------------------------------------------
        | 私信配置
        |--------------------------------------------------------------------------
        |
        | allow_user_to_user        允许学生给学生发送
        | allow_user_to_teacher     允许学生给教师发送
        | allow_teacher_to_user     允许教师给学生发送
        |
        */
        'message' => [
            'allow_user_to_user' => true,
            'allow_user_to_teacher' => true,
            'allow_teacher_to_user' => true
        ],
    ],

];