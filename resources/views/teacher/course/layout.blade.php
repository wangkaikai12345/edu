<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', '猿代码')</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ '/vendor/bootstrap/dist/css/bootstrap.min.css' }}">
    <link rel="stylesheet" href="{{ '/vendor/bootstrap/dist/css/bootstrap-grid.min.css' }}">
    <link rel="stylesheet" href="{{ '/vendor/bootstrap/dist/css/bootstrap-reboot.min.css' }}">
    <link rel="stylesheet" href="{{ '/vendor/timepicker/css/bootstrap-datetimepicker.css' }}">
    <link rel="stylesheet" href="{{ mix('/css/theme.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/course/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/alert/alert.css') }}">
    @yield('style')
</head>
<body style="background:#fff;">
<div class="wrapper">
    <div class="xh_content_wrap">
        @yield('content')
    </div>
</div>
<script src="{{ mix('/js/front/app.js') }}"></script>
{{--<script src="/vendor/jquery/dist/jquery.min.js"></script>--}}
<script src="{{ '/vendor/popover/popover.min.js' }}"></script>
<script src="{{ '/vendor/bootstrap/dist/js/bootstrap.min.js' }}"></script>
<script src="/vendor/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/jquery.validate.min.js') }}"></script>
<script src="{{ mix('/js/theme.js') }}"></script>
<script src="{{ mix('/js/front/header/index.js') }}"></script>

@yield('script')

@include('frontend.review.layouts._helpers')
<script>

    $(function() {
        @if (Session::has('error'))
        edu.alert('danger', "{{ Session::get('error') }}");
        @endif

        @if (Session::has('success'))
        edu.alert('success', "{{ Session::get('success') }}");
        @endif

        @if (Session::has('danger'))
        edu.alert('danger', "{{ Session::get('danger') }}");
        @endif

        @if (count($errors) > 0)
        edu.alert('danger', "{{ $errors->all()[0] }}");
        @endif
    })

</script>
</body>
</html>