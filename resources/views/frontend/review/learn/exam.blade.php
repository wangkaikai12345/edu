@extends('frontend.review.layouts.app')
@section('title')
    我的学习-我的考试
@endsection
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/article/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/course.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/topic.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/homework.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/exam.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row padding-content">
            @include('frontend.review.learn.navBar')
            <div class="xh_topic col-xl-9 col-md-12">
                <div class="course_head">
                    <p>我的考试</p>
                </div>
                <div class="course_content">
                    {{--                    <div class="course_content_nav">--}}
                    {{--                        <ul class="nav" id="myClassicTab" role="tablist">--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a class="nav-link waves-light active show"--}}
                    {{--                                   id="profile-tab-classic" data-toggle="tab"--}}
                    {{--                                   href="#exam-record"--}}
                    {{--                                   role="tab" aria-controls="exam-record"--}}
                    {{--                                   aria-selected="true">考试记录</a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a class="nav-link waves-light" id="follow-tab-classic"--}}
                    {{--                                   data-toggle="tab" href="#collection-subject" role="tab"--}}
                    {{--                                   aria-controls="collection-subject" aria-selected="false">收藏的题目</a>--}}
                    {{--                            </li>--}}
                    {{--                        </ul>--}}
                    {{--                    </div>--}}
                    @if(count($paper_results))
                        <div class="zh_course">
                            <div class="container">
                                <div class="course-tabs">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card card_shadow">
                                                <div class="card-body card-body-cascade p-0">
                                                    <div class="row">
                                                        <div class="col-xl-12 p-1">
                                                            <!-- Classic tabs -->
                                                            <div class="classic-tabs">
                                                                <div class="tab-content rounded-bottom"
                                                                     id="myClassicTabContent">
                                                                    <div class="tab-pane fade huati active show"
                                                                         id="huati-classic"
                                                                         role="tabpanel"
                                                                         aria-labelledby="huati-tab-classic">
                                                                        <div class="col-md-12">
                                                                            <div class="huati_wrap">

                                                                                <div class="huati_list">
                                                                                    <!-------  考试记录 -------->
                                                                                    @foreach($paper_results as $result)
                                                                                        <div class="huati_item ">
                                                                                            <div class="huati_info"
                                                                                                 style="width: 50%;">
                                                                                                <div class="homework_title">
                                                                                                    {{ $result->paper_title }}
                                                                                                </div>
                                                                                                <div class="huati_user">
                                                                                                    <span class="user_name">
                                                                                                    来自
                                                                                                        <span>《{{ $result->task->title }}》</span>
                                                                                                    </span>
                                                                                                    <span class="huati_time">
                                                                                                        {{ $result->created_at }}
                                                                                                    </span>
                                                                                                </div>
                                                                                            </div>
                                                                                            @if($result->is_mark)
                                                                                                <div class="exam-btn float-right">
                                                                                                    <a href="{{ route('users.exams.details',$result) }}">
                                                                                                        <button class="btn btn-primary"
                                                                                                                style="margin-top: 22px">
                                                                                                            查看结果
                                                                                                        </button>
                                                                                                    </a>
                                                                                                </div>
                                                                                            @endif
                                                                                            <div class="watch_num second"
                                                                                                 style="margin-top: 13px;margin-right: 36px;">
                                                                                                <div class="watch_item">
                                                                                                    @if($result->is_mark)
                                                                                                        <span>
                                                                                                        得分
                                                                                                        <span>
                                                                                                            {{ $result->answer_score }}
                                                                                                        </span>
                                                                                                    </span>
                                                                                                    @else
                                                                                                        <span class="text-warning"
                                                                                                              style="float: right">
                                                                                                        正在批阅
                                                                                                    </span>
                                                                                                    @endif
                                                                                                </div>
                                                                                                <div class="watch_item">
                                                                                                    @if($result->is_mark)
                                                                                                        <span>
                                                                                                            做对
                                                                                                            <span>{{ $result->right_count }}</span>
                                                                                                            题
                                                                                                        </span>
                                                                                                        /
                                                                                                        <span>
                                                                                                            共
                                                                                                            <span>{{ $result->paper->questions->count() }}</span>
                                                                                                            题
                                                                                                        </span>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                <!-------  收藏的题目 -------->
                                                                                    {{--<div class="huati_item ">--}}
                                                                                    {{--<div class="huati_info">--}}
                                                                                    {{--<div class="huati_title">--}}
                                                                                    {{--<span>--}}
                                                                                    {{--这是题目名--}}
                                                                                    {{--</span>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="huati_user">--}}
                                                                                    {{--<span class="user_name second">--}}
                                                                                    {{--来自--}}
                                                                                    {{--<span class="span_text">《全网唯一的微信公共平台开发》</span>--}}
                                                                                    {{--</span>--}}
                                                                                    {{--<span class="huati_time">--}}
                                                                                    {{--2018-08-16 14:53:17--}}
                                                                                    {{--</span>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="watch_num">--}}
                                                                                    {{--<div class="watch_item">--}}
                                                                                    {{--<button class="btn btn-primary cancel-collection">--}}
                                                                                    {{--取消收藏--}}
                                                                                    {{--</button>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="huati_item ">--}}
                                                                                    {{--<div class="huati_info">--}}
                                                                                    {{--<div class="huati_title">--}}
                                                                                    {{--<span>--}}
                                                                                    {{--这是题目名--}}
                                                                                    {{--</span>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="huati_user">--}}
                                                                                    {{--<span class="user_name">--}}
                                                                                    {{--来自--}}
                                                                                    {{--<span class="span_text">《全网唯一的微信公共平台开发》</span>--}}
                                                                                    {{--</span>--}}
                                                                                    {{--<span class="huati_time">--}}
                                                                                    {{--2018-08-16 14:53:17--}}
                                                                                    {{--</span>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--<div class="watch_num">--}}
                                                                                    {{--<div class="watch_item">--}}
                                                                                    {{--<button class="btn btn-primary cancel-collection">--}}
                                                                                    {{--取消收藏--}}
                                                                                    {{--</button>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                </div>
                                                                            </div>
                                                                        {{--<span class="no_data">--}}
                                                                        {{--还没有人评价...--}}
                                                                        {{--</span>--}}
                                                                        <!-- Fourth news -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Classic tabs -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="topic_no_content row">
                            <div class="col-md-12">
                                <div class="no_content_text">
                                    还没有做过任何考试
                                </div>
                            </div>
                        </div>
                    @endif
                    <nav class="course_list" aria-label="Page navigation example">
                        {!! $paper_results->render() !!}
                    </nav>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection