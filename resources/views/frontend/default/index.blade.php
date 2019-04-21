@extends('frontend.default.layouts.app')

@section('title', '猿代码')

@section('style')
    <link href="{{ asset('dist/index/css/index.css') }}" rel="stylesheet">
@endsection

@section('content')

    @inject('index', 'App\Handlers\IndexHandler')

    <div class="banner">
        <div class="container">
            <div class="navigation">
                <div class="navigation-left">
                    <div class="navigation-content">
                        <ul id="navigation">
                            @foreach($index->course_category() as $item)
                            <li>
                                <a href="#">
                                    <span>{{ $item['name'] }}</span>
                                    <i class="sideNaveleftRow"></i>
                                </a>
                                <div class="subjectLists-childrenBox">
                                    <div class="mircCourse clearfix">

                                        {{--<a href="/course/free/" target="_blank" class="moreCourse">--}}
                                            {{--更多<i class="fas fa-chevron-right"></i></a>--}}
                                        <ul class="lists clearfix">
                                            @empty(!$item['children'])
                                                @foreach($item['children'] as $child)
                                                    <li>
                                                        <a href="{{ route('courses.index', ['category_id' => $child['id'] ]) }}">{{ $child['name'] }}</a>
                                                    </li>
                                                @endforeach
                                            @endempty
                                        </ul>
                                    </div>
                                    <ul class="professCourse clearfix">
                                        @empty(!$item['courses'])
                                            @foreach($item['courses'] as $course)
                                                <li class="clearfix">
                                                    <a href="{{ route('courses.show', $course['id']) }}" target="_blank">
                                                        <img class="imgLeft"
                                                             src="{{ render_cover($course['cover'], 'course') }}"
                                                             alt="">
                                                        <div class="detailRight">
                                                            <div class="part_one">{{ $course['title'] }}</div>
                                                            <div class="part_two">
                                                                <span><i class="iconfont icon-yonghu"></i>{{ $course['students_count'] }}人学习</span>
                                                            </div>
                                                            <div class="part_three free">{{ $course['max_course_price']== 0 ? '免费' : ftoy($course['min_course_price']).' 元 - '. ftoy($course['max_course_price']).' 元'}}</div>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endempty
                                    </ul>
                                </div>
                            </li>
                                @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="banner-wrap">
                <div class="banner-right">
                    <div class="swiper-container banner-swiper-container">
                        <div class="swiper-wrapper swiper-no-swiping">
                            @foreach($index->slide() as $value)
                            <div class="swiper-slide">
                                {{--{{ dd($index->slide()) }}--}}
                                <a href="{{ $value['link'] }}" target="_blank">
                                    <img data-src="{{ render_cover($value['image'], 'course') }}" alt=""
                                         class="swiper-lazy">
                                </a>
                                <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                            </div>
                            @endforeach
                        </div>
                        <!-- swiper pagination -->
                        <div class="swiper-pagination"></div>
                        <!-- swiper controls -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (count($index->course('hit_count')))
        <div class="box-item hot">
        <div class="container">
            <h2 class="module-title text-center pink-text">
                <span class="border-left">

                </span>
                <span class="title-content">
                    热&nbsp;门&nbsp;课&nbsp;程
                </span>
                <span class="border-right">

                </span>
            </h2>
            <div class="box-content hot">
                <div class="row">
                    {{--{{ dd($index->course('hit_count')) }}--}}
                    @foreach($index->course('hit_count') as $course)
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
                                    <span class="float-right text-danger" style="font-size: 14px;">
                                        ￥{{ $course['max_course_price']== 0 ? '免费' : ( $course['min_course_price'] == $course['max_course_price'] ? ftoy($course['min_course_price']) :ftoy($course['min_course_price']).'  -  '. ftoy($course['max_course_price']))}}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
                <div class="row">
                    <a type="button"
                       class="btn btn-sm btn-outline-primary waves-effect ml-auto mr-auto btn-rounded"
                       href="{{ route('courses.index', ['sort' => 'students_count,desc']) }}"
                    >查看更多</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (count($index->course('created_at')))
    <div class="box-item new">
        <div class="container">
            <h2 class="module-title text-center pink-text">
                <span class="border-left">

                </span>
                <span class="title-content">
                    最&nbsp;新&nbsp;课&nbsp;程
                </span>
                <span class="border-right">

                </span>
            </h2>
            <div class="box-content hot">
                <div class="row">
                    @foreach($index->course('created_at') as $course)
                        <div class="col-lg-1-5 col-md-3 col-xs-4 col-6 mb-3 pl-2 pr-2">

                            <div class="card card-ecommerce">
                                <!--Card image-->
                                <div class="view overlay">
                                    <img src="{{ render_cover($course['cover'], 'course') }}"
                                         class="card-img-top" alt="">
                                    <a href="{{ route('courses.show', $course['id']) }}">
                                        <div class="mask waves-effect waves-light rgba-white-slight"></div>
                                    </a>
                                </div>
                                <!--Card content-->
                                <div class="card-body card-body-cascade" style="padding: 0.5rem 0.8rem;">
                                    <!--Title-->
                                    <div class="card-title" style="font-size: 14px;">
                                        <span class="float-left">{{ $course['title'] }}</span>
                                    </div>
                                    <!--Text-->
                                    <div class="card-footer px-1">

                                    <span class="float-left" style="font-size: 14px;">
                                        <i class="fa fa-user grey-text mr-1"></i>
                                        {{ $course['students_count'] }}
                                        <i class="fa fa-eye grey-text ml-3 mr-1"></i>
                                        {{ $course['hit_count'] }}
                                    </span>
                                        <span class="float-right text-danger" style="font-size: 14px;">
                                            ￥{{ $course['max_course_price']== 0 ? '免费' : ( $course['min_course_price'] == $course['max_course_price'] ? ftoy($course['min_course_price']) :ftoy($course['min_course_price']).'  -  '. ftoy($course['max_course_price']))}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <a type="button"
                       class="btn btn-sm btn-outline-primary waves-effect ml-auto mr-auto btn-rounded"
                       href="{{ route('courses.index', ['sort' => 'created_at,desc']) }}"
                    >查看更多</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (count($index->classroom()))
    <div class="box-item new">
        <div class="container">
            <h2 class="module-title text-center pink-text">
                <span class="border-left">

                </span>
                <span class="title-content">
                    推&nbsp;荐&nbsp;班&nbsp;级
                </span>
                <span class="border-right">

                </span>
            </h2>
            <div class="box-content hot">
                <div class="row">

                </div>
                <div class="row">
                    <button type="button" class="btn btn-sm btn-outline-primary waves-effect ml-auto mr-auto btn-rounded">查看更多</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (count($index->course('rating')))
    <div class="box-item recommend">
        <div class="container">
            <h2 class="module-title text-center pink-text">
                <span class="border-left">
                </span>
                <span class="title-content">
                    推&nbsp;荐&nbsp;课&nbsp;程
                </span>
                <span class="border-right">

                </span>
            </h2>
            <div class="box-content hot">
                <div class="row">
                    @foreach($index->course('rating') as $course)
                        <div class="col-lg-1-5 col-md-3 col-xs-4 col-6 mb-3 pl-2 pr-2">

                            <div class="card card-ecommerce">
                                <!--Card image-->
                                <div class="view overlay">
                                    <img src="{{ render_cover($course['cover'], 'course') }}"
                                         class="card-img-top" alt="">
                                    <a href="{{ route('courses.show', $course['id']) }}">
                                        <div class="mask waves-effect waves-light rgba-white-slight"></div>
                                    </a>
                                </div>
                                <!--Card content-->
                                <div class="card-body card-body-cascade" style="padding: 0.5rem 0.8rem;">
                                    <!--Title-->
                                    <div class="card-title" style="font-size: 14px;">
                                    <span class="float-left">{{ $course['title'] }}</span>

                                    </div>
                                    <!--Text-->
                                    <div class="card-footer px-1">

                                    <span class="float-left" style="font-size: 14px;">
                                        <i class="fa fa-user grey-text mr-1"></i>
                                        {{ $course['students_count'] }}
                                        <i class="fa fa-eye grey-text ml-3 mr-1"></i>
                                        {{ $course['hit_count'] }}
                                    </span>
                                    <span class="float-right text-danger" style="font-size: 14px;">
                                        ￥{{ $course['max_course_price']== 0 ? '免费' : ( $course['min_course_price'] == $course['max_course_price'] ? ftoy($course['min_course_price']) :ftoy($course['min_course_price']).'  -  '. ftoy($course['max_course_price']))}}
                                    </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <a type="button"
                       class="btn btn-sm btn-outline-primary waves-effect ml-auto mr-auto btn-rounded"
                       href="{{ route('courses.index', ['sort' => 'recommended_seq,desc']) }}"
                    >查看更多</a>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('dist/index/js/index.js') }}"></script>
@endsection


