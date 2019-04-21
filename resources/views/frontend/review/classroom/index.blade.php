@extends('frontend.review.layouts.app')

@section('title', '班级列表')

@section('keywords')
    @parent
    <meta name="keywords" content="优质学习圈" />
@endsection

@section('description')
    @parent
    <meta name="description" content="相信技术的力量！相信团队的力量" />
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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('classrooms.index') }}">班级列表</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-2">
                <a href="{{ route('courses.index') }}" class="btn btn-sm btn-primary btn-circle float-right classroom">查看课程</a>
                </div>
            </div>
            <div class="course-card">
                <div class="card label_card">
                    <div class="card-body pt-0 pb-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item row m-0 all">
                                <div class="title float-left col-xl-4" style="font-size: 18px;width: 140px;"><a style="color: #666666;" href="{{ route('classrooms.index') }}">查看所有分类</a></div>
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
                                         href="{{ route('classrooms.index', ['category_first_level_id' => $category['id']]) }}"
                                    >{{ $category['name'] }}</div>
                                    <ul class="content_ls float-left col-xl-10">
                                        @foreach($category->children as $cate)
                                            <li class="{{ old('category_id') == $cate['id'] ? 'active' : '' }}">
                                                <a href="{{ route('classrooms.index', ['category_id' => $cate['id']]) }}">{{ $cate['name'] }}</a>
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
                                <a class="nav-link {{ old('sort') == 'created_at,desc' || old('sort') == '' ? 'active' : '' }}" href="{{ route('classrooms.index', ['sort' => 'created_at,desc']) }}">最新</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ old('sort') == 'members_count,desc' ? 'active' : '' }}" href="{{ route('classrooms.index', ['sort' => 'members_count,desc']) }}">最热</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ old('sort') == 'recommended_seq,desc' ? 'active' : '' }}" href="{{ route('classrooms.index', ['sort' => 'recommended_seq,desc']) }}">推荐</a>
                            </li>
                            <li class="free-course">

                            </li>
                        </ul>
                    </div>
                </div>

                @if ($classrooms->count())
                    <div class="box-item">
                        <div class="box-content">
                            <div class="item_content row">
                                @foreach($classrooms as $classroom)
                                    <div class="col-xl-3 col-md-6 col-sm-6">
                                        <a href="{{ route('classrooms.show', $classroom) }}">
                                            <div class="item">
                                                <div class="cover">
                                                    <img class="classroom_img" src="{{ render_cover($classroom['cover'], 'classroom') }}" alt="">
                                                    <div class="price">
                                                        {{ $classroom->coin_price ? $classroom->coin_price. '虚拟币':  ($classroom->price ?  ftoy($classroom->price) .'元': '免费') }}
                                                    </div>
                                                </div>
                                                <h2 class="course_title">
                                                    {{ $classroom['title'] }}
                                                </h2>
                                                <div class="classroom_subtitle">
                                                    {{ $classroom->subtitle ?? '暂无介绍' }}
                                                </div>
                                                <div class="classroom_peoples peoples">
                                                    <span>
                                                        学员：{{ $classroom->members_count }}
                                                    </span>
                                                </div>
                                                <div class="classroom_peoples peoples">
                                                    <span>
                                                        课程：{{ $classroom->courses_count }}
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <nav aria-label="Page navigation example">
                            {!! $classrooms->render() !!}
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
//        $('#free').click(function(){
//
//            if ($(this).prop('checked')) {
//                location.href = $(this).data('url')
//            } else {
//                location.href = $(this).data('index')
//            }
//        })
    </script>
@endsection