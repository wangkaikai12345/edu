@extends('frontend.review.layouts.app')
@section('title')
    我的学习-我的话题
@endsection
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/article/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/course.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/topic.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row padding-content">
            @include('frontend.review.learn.navBar')
            <div class="xh_topic col-xl-9 col-md-12">
                <div class="course_head">
                    <p>我的话题</p>
                </div>
                <div class="course_content">
                    <div class="course_content_nav">
                        <ul class="nav" id="myClassicTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link waves-light active show"
                                   id="profile-tab-classic" data-toggle="tab"
                                   href="javascript:;"
                                   role="tab" aria-controls="course-question"
                                   aria-selected="true">课程话题</a>
                            </li>
                            {{--<li class="nav-item">--}}
                            {{--<a class="nav-link waves-light" id="follow-tab-classic"--}}
                            {{--data-toggle="tab" href="#class-question" role="tab"--}}
                            {{--aria-controls="class-question" aria-selected="false">班级问答</a>--}}
                            {{--</li>--}}
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
                                                                                @if ($topics->count())
                                                                                    @foreach($topics as $topic)
                                                                                        <div class="huati_item ">
                                                                                            <div class="huati_info">
                                                                                                <div class="huati_title">
                                                                                                    <i class="iconfont">
                                                                                                        &#xe659;
                                                                                                    </i>
                                                                                                    <span>
                                                                                                <a href="{{ route('plans.topic', [$topic->course, $topic->plan]) }}">{{ $topic->title}}</a>
                                                                                            </span>
                                                                                                </div>
                                                                                                <div class="huati_from">
                                                                                                    来自
                                                                                                    <span>
                                                                                              {{ $topic['course']['title'].'-'.$topic['plan']['title'] }}
                                                                                            </span>
                                                                                                </div>
                                                                                                <div style="clear: both;padding-right: 20px;margin-left: 40px;padding-top: 15px;color:#999;">{!! $topic->content !!}</div>

                                                                                                @if ($topic->latest_replier_id)
                                                                                                    <div class="huati_user">
                                                                                                <span class="user_name">
                                                                                                    最后回复：
                                                                                                    <span>{{ $topic->latest_replier->username }}</span>
                                                                                                </span>
                                                                                                        <span class="huati_time">
                                                                                                    {{ $topic->latest_replied_at }}
                                                                                                </span>
                                                                                                    </div>
                                                                                                    <div class="watch_num">
                                                                                                        <div class="watch_item">
                                                                                                            <i class="iconfont">
                                                                                                                &#xe62a;
                                                                                                            </i>
                                                                                                            <span>
                                                                            {{ $topic->hit_count }}
                                                                        </span>
                                                                                                        </div>
                                                                                                        <div class="watch_item">
                                                                                                            <i class="iconfont">
                                                                                                                &#xe64a;
                                                                                                            </i>
                                                                                                            <span>
                                                                            {{ $topic->replies_count }}
                                                                        </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>

                                                                                        </div>
                                                                                        @if(!$loop->last)
                                                                                            <hr style="margin-top:5px;margin-bottom: 5px;"/>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @else
                                                                                    <p class="an_notDataP">你还没有发表过话题</p>
                                                                                @endif
                                                                            </div>
                                                                        </div>

                                                                        <nav class="course_list"
                                                                             aria-label="Page navigation example">
                                                                            {!! $topics->render() !!}
                                                                        </nav>
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
                    {{--你还没有发表过话题--}}
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