<!doctype html>
<html lang="zh-CN">
@inject('index', 'App\Handlers\IndexHandler')
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', $index->site()['title']??'') - {{ $index->site()['sub_title'] }}</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    {{-- 关键词 --}}
    @section('keywords')
    <meta name="keywords" content="{{ $index->site()['keywords']?? '' }}"/>
    @show
    {{-- 描述 --}}
    @section('description')
    <meta name="description" content="{{ $index->site()['description']??'' }}"/>
    @show
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ http_format($index->site()['ico']) }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ mix('/css/theme.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/alert/alert.css') }}">
    @yield('style')
</head>
<body>
<div class="wrapper">
    @include('frontend.review.layouts.header')
    @yield('content')
    @include('frontend.review.layouts.footer')
</div>
<script src="{{ mix('/js/front/app.js') }}"></script>
<script src="{{ '/vendor/popover/popover.min.js' }}"></script>
<script src="{{ '/vendor/bootstrap/dist/js/bootstrap.min.js' }}"></script>
<script src="{{ '/js/theme.js' }}"></script>
<script src="{{ asset('vendor/jquery.validate.min.js') }}"></script>
<script src="{{ mix('/js/front/header/index.js') }}"></script>
<script>
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
</script>
@include('frontend.review.layouts._helpers')
@include('modal-remote')
@yield('script')
</body>
</html>