@component('mail::message')
    # 猿代码用户注册

    您好，您正在进行猿代码用户注册。如果这不是您的邮箱，那么请您忽略此邮件。验证码有效期截止至 {{ $expiredAt }}。

    验证码为：**{{ $token }}**

    谢谢！<br>
    {{ config('app.name') }}
@endcomponent
