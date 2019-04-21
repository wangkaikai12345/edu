@extends('teacher.plan.plan_layout')
@section('plan_content')
    <link rel="stylesheet" href="{{ mix('/css/teacher/plan/choice-question.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/plan/preview-question.css') }}">

    @include('teacher.plan.modal.create-question')

    <style>

    </style>
    <link rel="stylesheet" href="{{ mix('/css/front/task/video/index.css') }}">

    {{--@include('teacher.plan.modal.view-details')--}}
    <div class="col-xl-9 col-md-9">
        <div class="czh_task_content col-xl-12 col-md-12 col-12">
            <div class="operation_header row">
                <p class="col-md-10">{{ $task->title }} -- 视频评论</p>
                <div class="back_btn col-md-2">
                    <a href="{{ route('manage.plans.show', [$course, $plan]) }}"><i class="iconfont">&#xe644;</i> 返回</a>
                </div>
            </div>
            <div class="basics_information">
                <div class="bullet_problem">
                    <div class="b_video">
                        <div id="dplayer" data-url="{{ render_task_source($task->target->media_uri) }}"></div>
                    </div>
                </div>
            </div>
        </div>

        @if ($notes->count())
            @foreach($notes as $note)
                <div class="czh_task_content col-xl-12 col-md-12" style="margin-top: 30px">
                    <div class="operation_header row">
                        <p class="col-md-12">用户 {{ $note->user->username }} 在视频播放 {{ timeChange($note->collection) }} 的评论：</p>
                    </div>
                    <div class="basics_information">
                      {!! $note->content !!}
                    </div>
                </div>
                <br>
            @endforeach
                <nav class="pageNumber" aria-label="Page navigation example">
                    {{ $notes->links('vendor.pagination.default') }}
                </nav>
        @endif
    </div>


@endsection
@section('script')
    <script src=" {{ mix('/js/front/task/video/q_video.js') }} "></script>
    <script type="text/javascript">

    </script>
@endsection

