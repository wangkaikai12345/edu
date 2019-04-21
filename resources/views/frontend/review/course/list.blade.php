@extends('frontend.review.layouts.app')

@section('title', '课程列表')

@section('keywords')
    @parent
    <meta name="keywords" content="免费课程" />
@endsection

@section('description')
    @parent
    <meta name="description" content="免费的 web 技术教程。" />
@endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/list/index.css') }}">
@endsection

@section('content')
    <div class="zh_course zh_list">
        <div class="container">
            <div class="row">
                <div class="col-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pl-0">
                            <li class="breadcrumb-item"><a href="/">首页</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('courses.index') }}">课程列表</a></li>
                        </ol>
                    </nav>
                </div>

                @if (config('app.model') == 'classroom')
                    <div class="col-2">
                        <a href="{{ route('classrooms.index') }}" class="btn btn-sm btn-primary btn-circle float-right classroom">查看班级</a>
                    </div>
                @endif
            </div>
            <div class="course-card">
                <div class="card label_card">
                    <div class="card-body pt-0 pb-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item row m-0 all">
                                <div class="title float-left col-xl-4" style="font-size: 18px;width: 140px;"><a style="color: #666666;" href="{{ route('courses.index') }}">查看所有分类</a></div>
                                {{--<ul class="content_ls float-left col-xl-10 chips-placeholder">--}}
                                    {{--<li>--}}
                                        {{--<div class="chip lighten-4 mb-0">--}}
                                            {{--微服务--}}
                                            {{--<i class="iconfont">&#xe6f2;</i>--}}
                                        {{--</div>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            </li>
                            @foreach($categories as $category)
                                <li class="list-group-item row m-0">
                                    <div class="title float-left col-xl-2 {{ old('category_first_level_id') == $category['id'] ? 'active' : '' }}"
                                         href="{{ route('courses.index', ['category_first_level_id' => $category['id']]) }}"
                                    >{{ $category['name'] }}</div>
                                    <ul class="content_ls float-left col-xl-10">
                                        @foreach($category->children as $cate)
                                            <li class="{{ old('category_id') == $cate['id'] ? 'active' : '' }}">
                                                <a href="{{ route('courses.index', ['category_id' => $cate['id']]) }}">{{ $cate['name'] }}</a>
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
                                <a class="nav-link {{ old('sort') == 'created_at,desc' || old('sort') == '' ? 'active' : '' }}" href="{{ route('courses.index', ['sort' => 'created_at,desc']) }}">最新</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ old('sort') == 'students_count,desc' ? 'active' : '' }}" href="{{ route('courses.index', ['sort' => 'students_count,desc']) }}">最热</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ old('sort') == 'recommended_seq,desc' ? 'active' : '' }}" href="{{ route('courses.index', ['sort' => 'recommended_seq,desc']) }}">推荐</a>
                            </li>
                            <li class="free-course">
                                <div class="form-check">

                                    <input type="checkbox" class="form-check-input" id="free" {{ if_query('max_course_price', '0') ? 'checked' : '' }}
                                           data-url="{{ route('courses.index', ['max_course_price' => 0]) }}" data-index="{{ route('courses.index') }}">
                                    <label class="form-check-label" for="materialUnchecked">免费课程</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                @if ($courses->count())
                    <div class="box-item">
                        <div class="box-content">
                            <div class="item_content row">
                                @foreach($courses as $course)
                                    <div class="col-xl-3 col-md-6 col-sm-6">
                                        <a href="{{ route('courses.show', $course) }}">
                                            <div class="item">
                                                <div class="cover">
                                                    <img src="{{ render_cover($course['cover'], 'course') }}" alt="">
                                                    <div class="price">
                                                        {{ $course->default_plan ? ($course->default_plan->is_free ? '免费' :($course->default_plan->coin_price ? $course->default_plan->coin_price .'虚拟币' : '￥'.ftoy($course->default_plan->price))):'暂无' }}
                                                    </div>
                                                </div>
                                                <h2 class="course_title">
                                                    {{ $course['title'] }}
                                                </h2>
                                                <div class="star">
                                                    @for ($i = 0; $i < 6; $i++)
                                                        @if ($i <= intval($course['rating']))
                                                            <i class="iconfont">
                                                                &#xe601;
                                                            </i>
                                                        @else
                                                            <i class="iconfont">
                                                                &#xe60d;
                                                            </i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="peoples">
                                                    <span>
                                                        {{ $course['subtitle']  ?? '暂无描述' }}
                                                    </span>
                                                </div>
                                                <div class="icon_bg">
                                                    <span>荐</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <nav aria-label="Page navigation example">
                            {!! $courses->render() !!}
                        </nav>
                    </div>
                @else
                    <span class="no_data">
                        暂无数据
                    </span>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ mix('/js/front/list/index.js') }}"></script>
    <script>
        $('#free').click(function(){

            if ($(this).prop('checked')) {
                location.href = $(this).data('url')
            } else {
                location.href = $(this).data('index')
            }
        })
    </script>
@endsection