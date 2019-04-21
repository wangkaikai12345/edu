@extends('frontend.review.layouts.app')
@section('title')
    我的学习-我的班级
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
                    <p>我的班级</p>
                </div>
                <div class="course_content">
                    <div class="courseCollect row">
                        @if (count($members))
                            @foreach($members as $member)
                                <div class="col-md-4">
                                    <div class="courseItem col-md-12">
                                        <a href="{{ route('classrooms.show', $member->classroom) }}" class="courseImg">
                                            <img src="{{ render_cover($member->classroom->cover, 'classroom') }}"
                                                 alt="">
                                        </a>
                                        <div class="courseTitle">
                                            <p>{{ $member->classroom->title }}</p>
                                        </div>
                                        <div class="coursePrice">
                                            价格：<span class="priceRed"> {{ ftoy($member->classroom->price) }}
                                                元 +</span>
                                        </div>
                                        <div class="operationBtn" style="margin-top: 10px;">
                                            <a class=""
                                               href="{{ route('classrooms.show', $member->classroom) }}">进入班级</a>
                                        </div>
                                    </div>
                                    {{--@if(is_int(($loop->index+1) / 3) && $loop->index+1 < count($courses))--}}
                                    {{--<hr style="margin:0 auto;width:100%;">--}}
                                    {{--@endif--}}
                                </div>
                            @endforeach
                        @else

                            <p class="notDataP">暂无数据</p>

                        @endif
                    </div>

                    @if(count($members) > config('theme.my_course_num'))
                        <nav class="course_list" aria-label="Page navigation example">
                            {!! $members->render() !!}
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection