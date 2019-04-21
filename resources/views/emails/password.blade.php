@component('mail::message')
# 身份验证

尊敬的 {{ $user->username }} 您好，您正在进行重置密码的操作，如果这是您的邮箱，那么您可以点击下方按钮进行重置；如果这不是您的邮箱，那么请您忽略此邮件。重置密码邮件的有效期截止至 {{ $expiredAt }}，请您及时重置。

## 验证码为：{{ $token }}

谢谢！<br>
{{ config('app.name') }}
@endcomponent
