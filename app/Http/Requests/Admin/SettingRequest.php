<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Rules\CertRule;

/**
 * Class SettingRequest
 * @package App\Http\Requests\Admin
 */
class SettingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->namespace) {
            case 'qiniu':
                return [
                    'ak' => 'nullable|string|max:100',
                    'sk' => 'nullable|string|max:100',
                    'queue' => 'nullable|string',
                    'public_bucket' => 'nullable|string',
                    'public_domain' => 'nullable|string',
                    'private_bucket' => 'nullable|string',
                    'private_domain' => 'nullable|string',
                    'slice_bucket' => 'nullable|string',
                    'slice_domain' => 'nullable|string',
                ];
                break;
            case 'sms':
                return [
                    'ak' => 'nullable||string',
                    'sk' => 'nullable||string',
                    'sign_name' => 'nullable||string',
                    'register_template_code' => 'nullable||string',
                    'password_template_code' => 'nullable||string',
                    'login_template_code' => 'nullable||string',
                    'verify_template_code' => 'nullable||string',
                    'expires' => 'nullable||integer',
                    'variable' => 'nullable|array'
                ];
                break;
            case 'site':
                return [
                    'copyright' => 'nullable|string',
                    'description' => 'nullable|string',
                    'domain' => 'nullable|string',
                    'email' => 'nullable|string',
                    'stat_code' => 'nullable|string',
                    'icp' => 'nullable|string',
                    'sub_title' => 'nullable|string',
                    'title' => 'nullable|string',
                    'logo' => 'nullable|string',
                    'ico' => 'nullable|string',
                ];
                break;
            case 'wechat_pay':
                return [
                    'appid' => 'nullable||string',
                    'app_id' => 'nullable||string',
                    'miniapp_id' => 'nullable||string',
                    'mch_id' => 'nullable||string',
                    'key' => 'nullable||string',
                    'cert_client' => ['required', 'nullable', 'string', new CertRule()],
                    'cert_key' => ['required', 'nullable', 'string', new CertRule()],
                    'on' => 'boolean',
                ];
                break;
            case 'ali_pay':
                return [
                    'app_id' => 'nullable|string',
                    'private_key' => 'nullable|string',
                    'ali_public_key' => 'nullable|string',
                    'return_url' => 'nullable|string',
                    'on' => 'boolean',
                ];
                break;
            case 'email':
                return [
                    'driver' => 'nullable|string|in:smtp',
                    'host' => 'nullable|string',
                    'port' => 'nullable|integer',
                    'username' => 'nullable|string',
                    'password' => 'nullable|string',
                    'encryption' => 'nullable|string',
                    'from.address' => 'nullable|string',
                    'from.name' => 'nullable|string',
                    'expires' => 'nullable|integer'
                ];
                break;
            case 'register':
                return [
                    'register_mode' => 'nullable|string|in:phone,email,email_phone,closed',
                    'verified_email_login' => 'bool',
                    'email_title' => 'nullable|string|max:100',
                    'email_content' => 'nullable|string',
                    'register_limit' => 'nullable|integer',
                    'register_expires' => 'nullable|integer',
                    'is_sent_welcome_notification' => 'bool',
                    'welcome_title' => 'nullable|string',
                    'welcome_content' => 'nullable|string',
                ];
                break;
            case 'login':
                return [
                    'is_limit' => 'bool',
                    'password_error_times_for_user' => 'nullable|integer',
                    'password_error_times_for_ip' => 'nullable|integer',
                    'expires' => 'nullable|integer'
                ];
                break;
            case 'avatar':
                return [
                    'image' => 'nullable',
                ];
                break;
            case 'message':
                return [
                    'allow_user_to_user' => 'bool',
                    'allow_user_to_teacher' => 'bool',
                    'allow_teacher_to_user' => 'bool'
                ];
                break;
            case 'header_nav':
            case 'footer_nav':
            case 'friend_link':
                return [];
                break;
            default:
                return [];
        }
    }
}
