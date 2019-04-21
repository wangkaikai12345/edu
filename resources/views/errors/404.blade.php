<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>@yield('title', '404')</title>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="stylesheet" href="/404/404.css">
        <script src="/404/jquery-1.10.2.js"></script>
        <script src="/404/jquery.min.js"></script>
    </head>
    <body style="">
    <div class="code">
        <p>ERROR 404</p>
    </div>
    <div class="road">
        <div class="shadow">
            <div class="shelt">
                <div class="head" style="top: 20px;">
                    <div class="eyes">
                        <div class="lefteye">
                            <div class="eyeball"></div>
                            <div class="eyebrow" style="clip: rect(0px, 38px, 10px, 0px);"></div>
                        </div>
                        <div class="righteye">
                            <div class="eyeball"></div>
                            <div class="eyebrow" style="clip: rect(0px, 38px, 10px, 0px);"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hat" style="transform: rotate(1.4deg);"></div>
            <div class="bubble" style="opacity: 0.917647;">
                <a href="/">返回首页?</a>
            </div>
        </div>
        <p>{{ !empty($exception->getMessage()) ? $exception->getMessage() : 'PAGE NOT FOUND' }}</p>
    </div>
    <script type="text/javascript" src="/404/404.js"></script>
    </body>
</html>