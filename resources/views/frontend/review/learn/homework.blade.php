@extends('frontend.review.layouts.app')
@section('title')
    我的学习-我的作业
@endsection
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/article/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/course.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/topic.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/homework.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row padding-content">
            @include('frontend.review.learn.navBar')
            <div class="xh_topic col-xl-9 col-md-12">
                <div class="course_head">
                    <p>我的作业</p>
                </div>
                <div class="course_content">
                    <div class="course_content_nav">
                        <ul class="nav" id="myClassicTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link waves-light {{ active_class(if_query('keyword', 'readed')) }}"
                                   href="{{ route('users.jobs', ['keyword' => 'readed']) }}">已批阅</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link waves-light {{ active_class(if_query('keyword', 'reading')) }}"
                                   href="{{ route('users.jobs', ['keyword' => 'reading']) }}">
                                    批阅中
                                </a>
                            </li>
                        </ul>
                    </div>
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
                                                                                <!-------  已批阅 -------->
                                                                                @if ($homeworks->count())
                                                                                    @foreach($homeworks as $homework)
                                                                                        @if($homework->status == 'readed')
                                                                                            <a href="javascript:;" data-toggle="modal"
                                                                                               data-target="#modal"
                                                                                               data-url="{{ route('users.jobs.homework.info', $homework) }}">
                                                                                                <div class="huati_item ">
                                                                                                    <div class="huati_info"
                                                                                                         style="width:50%">
                                                                                                        <div class="homework_title">
                                                                                                            作业：
                                                                                                            <span>{{ $homework->title }}</span>
                                                                                                        </div>
                                                                                                        <div class="huati_user">
                                                                                                            {{--<span class="user_name">--}}
                                                                                                            {{--来自--}}
                                                                                                            {{--<span>《全网唯一的微信公共平台开发》</span>--}}
                                                                                                            {{--</span>--}}
                                                                                                            <span class="huati_time">
                                                                                                        {{ $homework->created_at }}
                                                                                                    </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="watch_num second"
                                                                                                         style="margin-top: 10px;">
                                                                                                        <div class="watch_item">
                                                                                                    <span>
                                                                                                        得分
                                                                                                        <span>
                                                                                                            {{ $homework->result }}
                                                                                                        </span>
                                                                                                    </span>
                                                                                                        </div>
                                                                                                        <div class="watch_item">
                                                                                                            <span>
                                                                                                                {{--做对--}}
                                                                                                                {{--<span>1</span>--}}
                                                                                                                {{--题--}}
                                                                                                            </span>
                                                                                                                    {{--/--}}
                                                                                                            <span>
                                                                                                                {{--共--}}
                                                                                                                {{--<span>1</span>--}}
                                                                                                                {{--题--}}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </a>
                                                                                        @else
                                                                                        <!-------  批阅中 -------->
                                                                                            <div class="huati_item ">
                                                                                                <div class="huati_info">
                                                                                                    <div class="homework_title">
                                                                                                        作业：
                                                                                                        <span>{{ $homework->title }}</span>
                                                                                                    </div>
                                                                                                    <div class="huati_user">
                                                                                                        {{--<span class="user_name">--}}
                                                                                                            {{--来自--}}
                                                                                                            {{--<span>《全网唯一的微信公共平台开发》</span>--}}
                                                                                                        {{--</span>--}}
                                                                                                        <span class="huati_time">
                                                                                                            {{ $homework->created_at }}
                                                                                                        </span>
                                                                                                    </div>
                                                                                                    <div class="watch_num">
                                                                                                        <div class="watch_item">
                                                                                                            <span class="text-warning reading-topic">
                                                                                                            正在批阅
                                                                                                            </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @else
                                                                                    <p class="an_notDataP">还没有做过任何作业</p>
                                                                                @endif
                                                                            </div>
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
                        {{--<div class="topic_no_content row">--}}
                        {{--<div class="col-md-12">--}}
                        {{--<div class="no_content_text">--}}
                        {{--还没有做过任何作业--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('script')
@endsection