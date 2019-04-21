@extends('frontend.default.layouts.app')
@section('title', '课程列表')
@section('style')
    <link href="{{ asset('dist/list/css/index.css') }}" rel="stylesheet">
    <style>
        .category{
            color : #666;
        }
    </style>
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="" href="/">首页</a>
                <span class="ml-2">/</span>
            </li>
            <li class="breadcrumb-item active">
                <a class="" href="{{ route('courses.index') }}">课程列表</a>
            </li>
            <li class="right-btn">
                <a href="{{ route('classrooms.index') }}" class="btn btn-sm btn-primary float-right">查看班级</a>
            </li>
        </ol>
    </nav>
    <div class="course-card">
        <div class="card">
            <div class="card-body pt-0 pb-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item row all">
                        <a class="title float-left col-xl-2" style="color: #666666;" href="{{ route('courses.index') }}">查看所有分类</a>
                    </li>
                    @foreach($categories as $category)
                        <li class="list-group-item row">
                            <div class="title float-left col-xl-2">{{ $category['name'] }}</div>
                            <ul class="content_ls float-left col-xl-10">
                                @foreach($category->children as $cate)
                                    <li class="{{ old('category_id') == $cate['id'] ? 'active' : '' }}">
                                        <a href="{{ route('courses.index', ['category_id' => $cate['id']]) }}" class="category">{{ $cate['name'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach

                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <ul class="nav nav-tabs md-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ old('sort') == 'created_at,desc' ? 'active' : '' }}" href="{{ route('courses.index', ['sort' => 'created_at,desc']) }}">最新</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ old('sort') == 'students_count,desc' ? 'active' : '' }}" href="{{ route('courses.index', ['sort' => 'students_count,desc']) }}">最热</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ old('sort') == 'recommended_seq,desc' ? 'active' : '' }}" href="{{ route('courses.index', ['sort' => 'recommended_seq,desc']) }}">推荐</a>
                    </li>
                    <li class="free-course">
                        {{--<div class="form-check">--}}
                            {{--<input type="checkbox" class="form-check-input" id="materialUnchecked">--}}
                            {{--<label class="form-check-label" for="materialUnchecked">免费课程</label>--}}
                        {{--</div>--}}
                    </li>
                </ul>
            </div>
        </div>
        <div class="box-item">
            <div class="container">
                <div class="box-content">
                    <div class="row">
                        @foreach($courses as $course)
                            <div class="col-lg-1-5 col-md-3 col-xs-4 col-6 mb-3 pl-2 pr-2">

                                <div class="card card-ecommerce">
                                    <!--Card image-->
                                    <div class="view overlay">
                                        <img src="{{ render_cover($course['cover'], 'course') }}"
                                             class="card-img-top" alt="">
                                        <a href="{{ route('courses.show', $course) }}">
                                            <div class="mask waves-effect waves-light rgba-white-slight"></div>
                                        </a>
                                    </div>
                                    <!--Card content-->
                                    <div class="card-body card-body-cascade" style="padding: 0.5rem 0.8rem;">
                                        <!--Title-->
                                        <div class="card-title" style="font-size: 14px;">
                                    <span class="float-left">
                                       {{ $course['title'] }}
                                    </span>

                                        </div>
                                        <!--Text-->
                                        <div class="card-footer px-1">

                                    <span class="float-left" style="font-size: 14px;">
                                        <i class="fa fa-user grey-text mr-1"></i>
                                        {{ $course['students_count'] }}
                                        <i class="fa fa-eye grey-text ml-3 mr-1"></i>
                                        {{ $course['hit_count'] }}
                                    </span>
                                            <span class="float-right text-danger">
                                        ￥{{ $course['max_course_price']== 0 ? '免费' : ( $course['min_course_price'] == $course['max_course_price'] ? ftoy($course['min_course_price']) :ftoy($course['min_course_price']).'  -  '. ftoy($course['max_course_price']))}}
                                    </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>
                    <div class="row justify-content-center">
                        <nav aria-label="Page navigation example">
                            {!! $courses->render() !!}
                        </nav>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('dist/list/js/index.js') }}"></script>
@endsection
