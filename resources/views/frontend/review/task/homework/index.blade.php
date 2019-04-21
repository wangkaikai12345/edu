{{--@extends('frontend.review.classroom.layout')--}}
{{--@extends('frontend.review.task.layout')--}}
{{--@section('style')--}}
    <link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/modal.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/submit-modal.css') }}">
{{--@endsection--}}
@include('frontend.review.task.homework.modal')
@include('frontend.review.task.homework.submit_modal')
{{--@section('content')--}}
    <div class="xh">
        <div class="container">
            <div class="xh_content">
                <div class="xh_first_content">
                    {{--<div class="number_content">1</div>--}}
                    <div class="homework_title">{{ $task->target->title }}</div>
                </div>
                <div class="xh_second_content">
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            作业要求：
                        </div>
                        <div class="homework_item_desc">
                            {!! $task->target->about !!}
                        </div>
                    </div>
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            解题提示：
                        </div>
                        <div class="homework_item_desc">
                            {!! $task->target->hint !!}
                        </div>
                    </div>
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            预计时间：
                        </div>
                        <div class="homework_item_desc">
                            {{ $task->length/60 }} 分钟
                        </div>
                    </div>
                    @if ($task->target->package)
                        <div class="homework_item">
                            <div class="homework_item_dian"></div>
                            <div class="homework_item_title data_pack">
                                资料包：
                            </div>
                            <div class="homework_item_desc">
                                <a href="javascript:;" class="pack">
                                    {{ render_cover($task->target->package,'') }}
                                    <a href="{{ render_cover($task->target->package,'') }}"><i class="iconfont">&#xe626;</i></a>
                                    {{-- 如果下载了资料图使用这个图片 --}}
                                    {{--<i class="iconfont">&#xe61d;</i>--}}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($task->target->video)
                        <div class="homework_item">
                            <div class="homework_item_dian"></div>
                            <div class="homework_item_title data_pack">
                                讲解视频：
                            </div>
                            <div class="homework_item_desc">
                                <video src="{{ render_cover($task->target->video, '') }}"
                                       controls="controls"
                                ></video>
                            </div>
                        </div>
                    @endif

                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            批改标准：
                        </div>
                        @foreach($task->target->grades_content as $value)
                            <div class="homework_item_desc">
                                <span class="pack">{{ $loop->iteration }}. </span> {{ $value }}
                            </div>
                        @endforeach
                    </div>
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            成绩排名：
                        </div>
                        <div class="homework_item_desc ranking">

                            @if ($task->target->getThird($task)->get()->count())
                                @foreach($task->target->getThird($task)->get() as $value)
                                    <div class="ranking_item">
                                        <div class="student_img">
                                            <img src="{{ render_cover($value->user->avatar,'avatar') }}" alt="">
                                        </div>
                                        <div class="ranking_right_content">
                                            <div class="ranking_student_name">
                                                {{ $value->user->username }}
                                            </div>
                                            <div class="ranking_student_score">{{ $value->result }}</div>
                                            <div class="ranking_student_date">3-17 13:49</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                暂无排名
                            @endif
                        </div>
                    </div>
                </div>
                <div class="right_operation">

                    <div class="submit_btn_content">
                        <a class="submit_button btn btn-primary" data-toggle="modal" data-target=".submit-modal-danger">提交作业</a>
                    </div>

                    @if($task->target->homeworkPosts()->task($task)->where('locked', 'open')->first())
                        <div class="right_content">
                            <div class="homework_title">
                                作业成绩
                            </div>
                            <hr class="m-0 division_xian">
                            <div class="submit_tips">
                                {{--<i class="iconfont not-submit">--}}
                                {{--&#xe618;--}}
                                {{--</i>--}}
                                {{--尚未提交--}}

                                @if($task->target->homeworkPosts()->where('locked', 'open')->task($task)->first()->status == 'reading')
                                    <i class="iconfont pending-approval">
                                    &#xe619;
                                    </i>
                                    待审批
                                    @else
                                    {{--如果有成绩使用 --}}
                                    <div class="score_content">
                                        {{ $task->target->homeworkPosts()->where('locked', 'open')->task($task)->first()->result }}
                                    </div>
                                        {{--<a href="{{ route('tasks.show', ) }}" --}}
                                        <a href="{{ renderTaskRoute([$chapter, 'type' => 'f-extra'], $member) }}"
                                           id="show-detail" class="btn btn-primary view-details">继续闯关</a>
                                    @endif

                            </div>
                        </div>

                    @else

                        <div class="right_content">
                            <div class="homework_title">
                                作业成绩
                            </div>
                            <hr class="m-0 division_xian">
                            <div class="submit_tips">
                                <i class="iconfont not-submit">
                                &#xe618;
                                </i>
                                尚未提交
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
{{--@endsection--}}

<script>
//    $('#show-detail').click(function(){
//
//    })
</script>