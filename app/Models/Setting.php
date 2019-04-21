<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Setting",
 *      type="object",
 *      required={},
 *      description="系统配置模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="namespace",type="string",description="配置名称"),
 *      @SWG\Property(property="value",type="integer",description="配置值"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 */
class Setting extends Model
{
    use SortableTrait;

    /**
     * @var string 系统设置
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    public $sortable = ['*'];

    /**
     * @var array
     */
    protected $casts = ['value' => 'array',];

    /**
     * @var array
     */
    protected $fillable = ['namespace', 'value'];

    /**
     * 获取对应的配置信息
     */
    public function namespace($namespace)
    {
        return Cache::remember(config('setting.cache.key') . $namespace, config('setting.cache.expired'), function () use ($namespace) {
            $setting = $this->where('namespace', $namespace)->first();
            if ($setting) {
                return $setting->value;
            }
            return config("setting.default.{$namespace}");
        });
    }
}

/**
 * @SWG\Definition(
 *      definition="SettingAliPay",
 *      type="object",
 *      required={},
 *      description="支付宝网页支付配置模型",
 *      @SWG\Property(property="app_id",type="string",description="支付宝 APP ID"),
 *      @SWG\Property(property="private_key",type="string",description="支付宝私钥"),
 *      @SWG\Property(property="ali_public_key",type="integer",description="支付宝公钥"),
 *      @SWG\Property(property="return_url",type="string",description="同步回调地址"),
 *      @SWG\Property(property="on",type="bool",description="是否开启支付宝服务")
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingAvatar",
 *      type="object",
 *      required={},
 *      description="默认头像配置模型",
 *      @SWG\Property(property="image",type="string",description="通过云存储服务上传默认头像，此处存储的是地址"),
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingEmail",
 *      type="object",
 *      required={},
 *      description="邮箱服务配置模型",
 *      @SWG\Property(property="driver",type="string",description="驱动（目前仅支持 smtp"),
 *      @SWG\Property(property="host",type="string",description="host 主机地址"),
 *      @SWG\Property(property="port",type="string",description="端口"),
 *      @SWG\Property(property="username",type="string",description="用户名"),
 *      @SWG\Property(property="password",type="string",description="密码"),
 *      @SWG\Property(property="encryption",type="string",description="加密方式"),
 *      @SWG\Property(property="from",type="string",description="来源",@SWG\Schema(
 *          @SWG\Property(property="address",example="ydma@ydma.com"),
 *          @SWG\Property(property="name",example="猿代码"),
 *      )),
 *      @SWG\Property(property="expires",type="string",description="邮件有效期；单位：分钟"),
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingLogin",
 *      type="object",
 *      required={},
 *      description="登录配置模型",
 *      @SWG\Property(property="login_mode",type="string",enum={"email","phone","all","closed"},description="注册模式"),
 *      @SWG\Property(property="is_limit_ip",type="bool",default=false,description="是否开启登录IP 限制"),
 *      @SWG\Property(property="is_limit_user",type="string",default=false,description="是否开启登录账户限制"),
 *      @SWG\Property(property="password_error_times_for_user",type="integer",default=0,description="对于用户而言的登录最大错误次数，与expires相配合；即在 expires 分钟内，允许用户登录错误 password_error_times_for_user 次"),
 *      @SWG\Property(property="password_error_times_for_ip",type="integer",default=0,description="对于IP而言的登录最大错误次数，与expires相配合；即在 expires 分钟内，允许该IP登录错误 password_error_times_for_ip 次"),
 *      @SWG\Property(property="expires",type="integer",description="限制时长，单位：分钟"),
 *      @SWG\Property(property="only_allow_verified_email_to_login",type="string",description="是否仅允许已验证的邮箱进行登录"),
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingMessage",
 *      type="object",
 *      required={},
 *      description="私信模型",
 *      @SWG\Property(property="allow_user_to_user",type="bool",description="允许学员向学员发送私信"),
 *      @SWG\Property(property="allow_user_to_teacher",type="bool",default=false,description="允许学员向教师发送私信"),
 *      @SWG\Property(property="allow_teacher_to_user",type="bool",default=false,description="允许教师向学员发送私信"),
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingQiniu",
 *      type="object",
 *      required={},
 *      description="七牛配置模型",
 *      @SWG\Property(property="ak",type="string",description="七牛 AK"),
 *      @SWG\Property(property="sk",type="string",default=false,description="七牛 SK"),
 *      @SWG\Property(property="queue",type="string",default=false,description="七牛多媒体队列"),
 *      @SWG\Property(property="callback",type="string",default=false,description="七牛切片回调地址"),
 *      @SWG\Property(property="public_bucket",type="string",default=false,description="公共库 bucket"),
 *      @SWG\Property(property="public_domain",type="string",default=false,description="公共库域名 domain"),
 *      @SWG\Property(property="private_bucket",type="string",default=false,description="私有库 bucket"),
 *      @SWG\Property(property="private_domain",type="string",default=false,description="私有库 domain"),
 *      @SWG\Property(property="slice_bucket",type="string",default=false,description="切片库 bucket"),
 *      @SWG\Property(property="slice_domain",type="string",default=false,description="切片库 domain"),
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingRegister",
 *      type="object",
 *      required={},
 *      description="注册模型",
 *      @SWG\Property(property="register_mode",type="string",enum={"email","phone","all","closed"},description="注册模式"),
 *      @SWG\Property(property="email_title",type="string",default=false,description="邮件标题"),
 *      @SWG\Property(property="email_content",type="string",default=false,description="邮件内容"),
 *      @SWG\Property(property="register_limit",type="integer",default=false,description="IP 注册限制次数，与注册限制时长配置，即每 register_expires 分钟内，允许 register_limit 次注册"),
 *      @SWG\Property(property="register_expires",type="integer",default=false,description="IP 注册限制时长，单位：分钟"),
 *      @SWG\Property(property="is_sent_welcome_notification",type="bool",default=false,description="注册成功后是否发送通知"),
 *      @SWG\Property(property="welcome_title",type="string",description="欢迎通知的标题"),
 *      @SWG\Property(property="welcome_content",type="string",description="欢迎通知的内容"),
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingSite",
 *      type="object",
 *      required={},
 *      description="站点配置模型",
 *      @SWG\Property(property="title",type="string",description="网站标题"),
 *      @SWG\Property(property="sub_title",type="string",description="子标题"),
 *      @SWG\Property(property="copyright",type="string",description="版权信息"),
 *      @SWG\Property(property="description",type="string",description="描述信息，用于 SEO"),
 *      @SWG\Property(property="stat_code",type="string",description="统计代码，用于统计"),
 *      @SWG\Property(property="icp",type="string",description="ICP 信息"),
 *      @SWG\Property(property="logo",type="string",description="网站 Logo"),
 *      @SWG\Property(property="ico",type="string",description="网站图标 favicon.ico"),
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingSms",
 *      type="object",
 *      required={},
 *      description="短信服务模型",
 *      @SWG\Property(property="ak",type="string",description="阿里云通信配置 AK"),
 *      @SWG\Property(property="sk",type="string",description="阿里云通信配置 SK"),
 *      @SWG\Property(property="sign_name",type="string",description="签名"),
 *      @SWG\Property(property="register_template_code",type="string",description="注册验证模板"),
 *      @SWG\Property(property="password_template_code",type="string",description="修改密码模板"),
 *      @SWG\Property(property="login_template_code",type="string",description="登录验证模板"),
 *      @SWG\Property(property="verify_template_code",type="string",description="身份验证模板"),
 *      @SWG\Property(property="expires",type="string",description="过期时间"),
 *      @SWG\Property(
 *          property="variable",
 *          type="object",
 *          description="存有自定义变量的 JSON 对象",
 *          @SWG\Property(type="string",property="product",description="这里你可以自定义变量"),
 *          example={"product":"猿代码"}
 *     )
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingWechatPay",
 *      type="object",
 *      required={},
 *      description="微信支付模型",
 *      @SWG\Property(property="appid",type="string",description="微信开放平台 APP_ID"),
 *      @SWG\Property(property="app_id",type="string",description="公众号 APP_ID"),
 *      @SWG\Property(property="miniapp_id",type="string",description="小程序 APP_ID"),
 *      @SWG\Property(property="mch_id",type="string",description="商户 ID"),
 *      @SWG\Property(property="key",type="string",description="微信 Key"),
 *      @SWG\Property(property="cert_client",type="file",description="应用证书"),
 *      @SWG\Property(property="cert_key",type="file",description="应用证书配对的 Key"),
 *      @SWG\Property(property="on",type="string",description="是否开启微信支付"),
 * )
 */
/**
 * @SWG\Definition(
 *      definition="SettingHeaderNav",
 *      type="object",
 *      required={},
 *      description="头部导航/友链模型/底部导航模型",
 *      @SWG\Property(property="key",type="string",description="前端组件需要设置的 key"),
 *      @SWG\Property(property="link",type="string",description="链接，允许为外站（完整域名），允许为本站（根路径域名）。"),
 *      @SWG\Property(property="name",type="string",description="名称"),
 *      @SWG\Property(property="status",type="string",description="状态：true/false 显示/隐藏"),
 *      @SWG\Property(property="target",type="string",description="是否打开新窗口"),
 * )
 */