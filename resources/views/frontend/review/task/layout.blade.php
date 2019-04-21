<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', '任务学习')</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="{{ mix('/css/theme.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/task/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/alert/alert.css') }}">
    @yield('style')
    <script src="/vendor/ueditor/ueditor.config.js"></script>
    <script src="/vendor/ueditor/ueditor.all.js"></script>
    <script src="{{ mix('/js/front/app.js') }}"></script>
</head>
<body>
<div class="wrapper">
    @include('frontend.review.task.header')
    <div class="zh_content_wrap video_page">
        {{--引导--}}
        {{--@include('frontend.review.task.guide')--}}
        {{--弹题--}}
        {{--@if (!is_show_mobile_page())--}}
            {{--@include('frontend.review.task.bulletProblem')--}}
        {{--@endif--}}

        {{-- 班级学习 --}}
        {{--@if (!empty(request('cid')) && !empty($member->classroom_id) && request('cid') == $member->classroom_id)--}}
            {{--@switch($task->type)--}}

                {{--测试--}}
                {{--@case(\App\Enums\TaskType::TEST)--}}
                {{--@if ($task->target_type == 'paper')--}}
                    {{--@includeIf('frontend.review.task.test.index')--}}
                {{--@else--}}
                    {{--@includeIf('frontend.review.task.'.$task->target_type)--}}
                {{--@endif--}}
                {{--@break--}}

                {{--开篇--}}
                {{--@case(\App\Enums\TaskType::INTRODUCE)--}}

                {{--@if ($task->target_type == 'text')--}}
                    {{--@includeIf('frontend.review.task.opening')--}}
                {{--@else--}}
                    {{--@includeIf('frontend.review.task.'.$task->target_type)--}}
                {{--@endif--}}

                {{--@break--}}

                {{--任务--}}
                {{--@case(\App\Enums\TaskType::TASK)--}}

                {{--@includeIf('frontend.review.task.guide')--}}

                {{--@if ($task->target_type == 'homework')--}}
                {{--@include('frontend.review.task.slideNav')--}}
                {{--@include('frontend.review.task.homework.directory')--}}
                {{--@include('frontend.review.task.homework.index')--}}
                {{--@include('frontend.review.task.note')--}}
                {{--@include('frontend.review.task.question')--}}
                {{--@else--}}
                {{--@include('frontend.review.task.slideNav')--}}
                {{--@include('frontend.review.task.directory')--}}
                {{--@include('frontend.review.task.note')--}}
                {{--@include('frontend.review.task.question')--}}
                {{--@endif--}}
                {{--<div id="content">--}}
                    {{--@includeIf('frontend.review.task.'.$task->target_type)--}}
                {{--</div>--}}

                {{--@if ($task->target_type == 'paper')--}}
                    {{--@includeIf('frontend.review.task.test.index')--}}
                {{--@endif--}}

                {{--@break--}}
                {{--作业--}}
                {{--@case(\App\Enums\TaskType::HOMEWORK)--}}

                {{--@if ($task->target_type == 'homework')--}}
                    {{--@include('frontend.review.task.slideNav')--}}
                    {{--@include('frontend.review.task.directory')--}}
                    {{--@include('frontend.review.task.homework.index')--}}
                    {{--@include('frontend.review.task.note')--}}
                    {{--@include('frontend.review.task.question')--}}
                {{--@else--}}
                    {{--<div id="content">--}}
                        {{--@includeIf('frontend.review.task.'.$task->target_type)--}}
                    {{--</div>--}}
                {{--@endif--}}
                {{--@break--}}

                {{--考试--}}
                {{--@case(\App\Enums\TaskType::EXAM)--}}
                {{--@if ($task->target_type == 'paper')--}}
                {{--@includeIf('frontend.review.task.test.index')--}}
                {{--@else--}}
                {{--@includeIf('frontend.review.task.'.$task->target_type)--}}
                {{--@endif--}}
                {{--@break--}}

                {{--扩展--}}
                {{--@case(\App\Enums\TaskType::EXTRA)--}}
                {{--<link rel="stylesheet" href="{{ mix('/css/front/task/extra_bar.css') }}">--}}
                {{--@include('frontend.review.task.slideNav')--}}
                {{--@include('frontend.review.task.directory')--}}
                {{--@include('frontend.review.task.note')--}}
                {{--@include('frontend.review.task.question')--}}

                {{--@if (request('summary') == 'summary' && typeResult($chapter->id, \App\Enums\TaskType::HOMEWORK, auth('web')->id()) == 100)--}}
                    {{--@include('frontend.review.task.extra.conclusion')--}}
                {{--@else--}}
                    {{--<div id="content">--}}
                        {{--@includeIf('frontend.review.task.'.$task->target_type)--}}
                    {{--</div>--}}
                {{--@endif--}}

                {{--@break--}}

                {{--@default--}}

            {{--@endswitch--}}
        {{--@else--}}

            @include('frontend.review.task.slideNav')
            @include('frontend.review.task.directory')
            @include('frontend.review.task.note')
            {{--@include('frontend.review.task.question')--}}

            <div id="content">
                @includeIf('frontend.review.task.'.$task->target_type)
            </div>

        {{--@endif--}}


        {{--@if (!is_show_mobile_page())--}}
            {{--@include('frontend.review.task.play_topic')--}}
        {{--@endif--}}

    </div>

</div>
<script src="{{ '/vendor/popover/popover.min.js' }}"></script>
<script src="/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('vendor/jquery.validate.min.js') }}"></script>
<script src="{{ mix('/js/theme.js') }}"></script>
@include('frontend.review.layouts._helpers')
<script src=" {{ mix('/js/front/task/index.js') }} "></script>
@yield('script')

<script>
    var guide = window.localStorage.getItem('guide');
    if (guide) {

    } else {
        $('#guide').css('display', 'block');
    }

</script>

</body>
</html>