@component('mail::message')
# 身份验证

尊敬的 {{ $user->username }} 您好，您正在进行绑定邮箱的操作，如果这是您的邮箱，那么您可以点击下方按钮进行绑定；如果这不是您的邮箱，那么请您忽略此邮件。激活邮件的有效期截止至 {{ $expiredAt }}，请您及时激活。

@component('mail::button', ['url' => $url])
绑定
@endcomponent

谢谢！<br>
{{ config('app.name') }}
@endcomponent
