@extends('frontend.review.layouts.app')
@section('title')
    我的学习-我的课程
@endsection
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/learn/course.css') }}">
@endsection
@section('content')
    <div class="container" style="min-height: 48%;">
        <div class="row padding-content">
            @include('frontend.review.learn.navBar')
            <div class="czh_learn_course col-xl-9">
                <div class="course_head">
                    <p>我的课程</p>
                </div>
                <div class="course_content">
                    <div class="course_content_nav">
                        <ul class="nav" id="myClassicTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link  waves-light {{ active_class(!if_query('is_finished', '1') && !if_query('favourite', '1')) }}"
                                   href="{{ route('users.courses') }}"
                                >学习中</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link waves-light {{ active_class(if_query('is_finished', '1')) }}"
                                   href="{{ route('users.courses', ['is_finished'=> true]) }}">已学完</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link waves-light {{ active_class(if_query('favourite', '1')) }}"
                                   href="{{ route('users.courses', ['favourite'=> true]) }}">收藏</a>
                            </li>
                        </ul>
                    </div>
                    <div class="courseCollect row">

                        @if (count($courses))
                            @foreach($courses as $course)
                                <div class="col-md-4">
                                    @if ($course['model'])
                                        <div class="courseItem col-md-12">
                                            <a href="{{ route('courses.show', $course->course) }}" class="courseImg">
                                                <img src="{{ render_cover($course['model']['cover'], 'course') }}"
                                                     alt="">
                                            </a>
                                            <div class="courseTitle">
                                                <p>{{ $course['model']['title'] }}</p>
                                            </div>
                                            <div class="coursePrice">
                                                价格：<span class="priceRed"> {{ ftoy($course['model']['min_course_price']) }}
                                                    元 +</span>
                                            </div>
                                            <div class="operationBtn" style="margin-top: 10px;">
                                                <a class=""
                                                   href="{{ route('courses.show', $course['model']) }}">查看课程</a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="courseItem col-md-12">
                                            <a href="{{ route('courses.show', $course->course) }}" class="courseImg">
                                                <img src="{{ render_cover($course['course']['cover'], 'course') }}"
                                                     alt="">
                                                <div class="course_status">{{ \App\Enums\SerializeMode::getDescription($course->plan->serialize_mode) }}</div>
                                            </a>
                                            <div class="courseTitle">
                                                <p>{{ $course['course']['title'].'-'.$course['plan']['title'] }}</p>
                                            </div>
                                            <div class="progress-content">
                                                <div class="progress-wrapper">
                                                    <div class="progress" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar"
                                                             style="width: {{ $course['plan']['tasks_count'] ? ytof($course['learned_count']/$course['plan']['tasks_count']): 0 }}%;"
                                                             aria-valuenow="0"
                                                             aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="scheduleNum">
                                                    <p>
                                                        {{ $course['plan']['tasks_count'] ? ytof($course['learned_count']/$course['plan']['tasks_count']): 0 }}
                                                        %
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="operationBtn">
                                                <a href="{{ route('plans.intro', [$course['course'], $course['plan']]) }}">{{ request('is_finished') ? '查看课程' : '继续学习' }}</a>
                                            </div>
                                        </div>
                                    @endif
                                    {{--@if(is_int(($loop->index+1) / 3) && $loop->index+1 < count($courses))--}}
                                    {{--<hr style="margin:0 auto;width:100%;">--}}
                                    {{--@endif--}}
                                </div>
                            @endforeach
                        @else

                            <p class="notDataP">暂无数据</p>

                        @endif
                    </div>

                    @if(count($courses) > config('theme.my_course_num'))
                        <nav class="course_list" aria-label="Page navigation example">
                            {!! $courses->render() !!}
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection