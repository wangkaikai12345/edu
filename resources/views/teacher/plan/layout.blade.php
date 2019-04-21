<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>版本管理</title>
    <link rel="stylesheet" href="{{ mix('/css/teacher/plan/index.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ mix('/css/theme.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/alert/alert.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('style')
</head>
<body style="background:#fff;">
@inject('index', 'App\Handlers\IndexHandler')

@include('teacher.header', ['nav_name' => 'teach_course'])
<div class="czh_plan_content">
    @include('teacher.plan.header')
    <div class="container">
        <div class="row padding-content">
            @include('teacher.plan.navBar')
            @yield('content')
        </div>
    </div>
    @include('frontend.review.layouts.footer')
</div>
{{--<script src='{{ "/vendor/jquery/dist/jquery.min.js" }}'></script>--}}
<script src="{{ '/vendor/popover/popover.min.js' }}"></script>
<script src="{{ '/vendor/select2/dist/js/select2.min.js' }}"></script>
<script src="{{ '/vendor/bootstrap/dist/js/bootstrap.bundle.min.js' }}"></script>
<script src="{{ '/vendor/bootstrap/dist/js/bootstrap.min.js' }}"></script>
<script src="{{ asset('vendor/jquery.validate.min.js') }}"></script>
<script src="{{ mix('/js/front/app.js') }}"></script>
<script src="{{ mix('/js/theme.js') }}"></script>
<script src="{{ mix('/js/front/header/index.js') }}"></script>
@yield('script')
@include('frontend.review.layouts._helpers')
@include('modal-remote')
</body>
</html>
