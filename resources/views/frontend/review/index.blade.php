@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/index.css') }}">
@endsection

{{-- 获取后台数据 --}}
@inject('index', 'App\Handlers\IndexHandler')

@section('content')
    <div class="zh_banner">
        <div class="banner">
            <div class="container">
                <div class="navigation">
                    <div class="navigation-left">
                        <div class="navigation-content">
                            <ul id="navigation">
                                @foreach($index->course_category() as $category)
                                    @break($loop->index == 7)
                                    <li>
                                        <a href="{{ route('courses.index', ['category_first_level_id' => $category['id'] ]) }}" target="_blank" rel="noopener noreferrer">
                                            <span class="parent_title">{{ $category['name'] }}</span>
                                            <span class="sub_title">
                                                {{ $category['children'][0]['name'] }}
                                            </span>
                                            <span class="sub_title">
                                                {{ $category['children'][1]['name'] }}
                                            </span>
                                            <i class="iconfont">
                                                &#xe628;
                                            </i>
                                        </a>
                                        <div class="children_box">
                                            <span class="all">
                                                全部
                                            </span>
                                            <ul class="children_list">
                                                 @foreach($category['children'] as $child)
                                                    <li class="children_item">
                                                        <a href="{{ route('courses.index', ['category_id' => $child['id'] ]) }}" target="_blank">
                                                            {{ $child['name'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="a_d_cover">
                                                @foreach ($category['courses'] as $course)
                                                    <div class="a_d_item">
                                                        <a href="{{ route('courses.show', $course) }}">
                                                            <div class="item_cover">
                                                                <img src="{{ render_cover($course['cover'],'course') }}" alt="">
                                                                <div class="price">
                                                                    {{ $course['max_course_price']== 0 ? '免费' : '￥'.ftoy($course['min_course_price']).'元'}}
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <span class="item_title">
                                                        {{ $course['title'] }}
                                                    </span>
                                                    <div class="star">
                                                        @for ($i = 0; $i < 5; $i++)
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
                                                </div>
                                                @endforeach
                                            </div>
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
    </div>
    <div class="zh_content">

        @if($index->course('rating')->count())
            <div class="content_item recommend">
                <div class="item_title">
                    <h2>
                        课程推荐
                    </h2>
                    <span class="title_description">
                    COURSE RECOMMENDATION
                </span>
                    <div class="title_line"></div>
                </div>
                <div class="container p-0">
                <div class="item_content row">
                    @foreach($index->course('rating') as $course)
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
                                        @for ($i = 0; $i < 5; $i++)
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
                                        <i class="iconfont ml-0">
                                            &#xe630;
                                        </i>
                                        <span>{{ $course['hit_count'] }}</span>
                                        <i class="iconfont">
                                            &#xe631;
                                        </i>
                                        <span>
                                        {{ $course['students_count'] }}
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

                <a href="{{ route('courses.index', ['sort' => 'recommended_seq,desc']) }}" class="btn btn-sm btn-primary btn-circle more_course">更多课程</a>
            </div>
        @endif

        @if(config('app.model') == 'classroom')
                @if($index->classroom()->count())
                    <div class="content_item recommend">
                        <div class="item_title">
                            <h2>
                                热门班级
                            </h2>
                            <span class="title_description">
                                POPULAR CLASSROOM
                            </span>
                            <div class="title_line"></div>
                        </div>
                        <div class="container p-0">
                            <div class="item_content row">
                                @foreach($index->classroom() as $classroom)
                                    <div class="col-xl-3 col-md-6 col-sm-6">
                                        <a href="{{ route('classrooms.show', $classroom) }}">
                                            <div class="item">
                                                <div class="cover">
                                                    <img src="{{ render_cover($classroom['cover'], 'classroom') }}" alt="">
                                                    <div class="price">
    {{ $classroom->coin_price ? $classroom->coin_price. '虚拟币':  ($classroom->price ?  ftoy($classroom->price) .'元': '免费') }}
                                                    </div>
                                                </div>
                                                <h2 class="course_title">
                                                    {{ $classroom['title'] }}
                                                </h2>

                                                <div class="star">
                                                   {{ $classroom->subtitle }}
                                                </div>

                                                <div class="peoples">
                                                    <i class="iconfont ml-0">
                                                        &#xe630;
                                                    </i>
                                                    <span>{{ $classroom['courses_count'] }}</span>
                                                    <i class="iconfont">
                                                        &#xe631;
                                                    </i>
                                                    <span>
                                                        {{ $classroom['members_count'] }}
                                                    </span>
                                                </div>
                                                <div class="icon_bg">
                                                    <span>热</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <a href="{{ route('classrooms.index') }}" class="btn btn-sm btn-primary btn-circle more_course">更多班级</a>
                    </div>
                @endif
        @endif

        @if($index->course('hit_count')->count())
            <div class="content_item hot">
            <div class="item_title">
                <h2>
                    热门课程
                </h2>
                <span class="title_description">
                    POPULAR  COURSE
                </span>
                <div class="title_line"></div>
            </div>
            <div class="container p-0">
                <div class="item_content row">

                    @foreach($index->course('hit_count') as $course)
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
                                        @for ($i = 0; $i < 5; $i++)
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
                                        <i class="iconfont ml-0">
                                            &#xe630;
                                        </i>
                                        <span>{{ $course['hit_count'] }}</span>
                                        <i class="iconfont">
                                            &#xe631;
                                        </i>
                                        <span>
                                        {{ $course['students_count'] }}
                                    </span>
                                    </div>
                                    <div class="icon_bg">
                                        <span>热</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
            @if($index->course('hit_count')->count())
                <a href="{{ route('courses.index', ['sort' => 'students_count,desc']) }}" class="btn btn-sm btn-primary btn-circle more_course">更多课程</a>
            @endif
        </div>
        @endif

        @if($index->course('created_at')->count())
            <div class="content_item new">
            <div class="item_title">
                <h2>
                    最新课程
                </h2>
                <span class="title_description">
                    NEW  COURSE
                </span>
                <div class="title_line"></div>
            </div>
            <div class="container p-0">
                <div class="item_content row">

                    @foreach($index->course('created_at') as $course)
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
                                        @for ($i = 0; $i < 5; $i++)
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
                                        <i class="iconfont ml-0">
                                            &#xe630;
                                        </i>
                                        <span>{{ $course['hit_count'] }}</span>
                                        <i class="iconfont">
                                            &#xe631;
                                        </i>
                                        <span>
                                        {{ $course['students_count'] }}
                                    </span>
                                    </div>
                                    <div class="icon_bg">
                                        <span>新</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
            @if($index->course('hit_count')->count())
                <a href="{{ route('courses.index', ['sort' => 'created_at,desc']) }}" class="btn btn-sm btn-primary btn-circle more_course">更多课程</a>
            @endif
        </div>
        @endif

        @if($index->teacher()->count())
            <div class="content_item teacher">
                <div class="item_title">
                    <h2>
                        推荐教师
                    </h2>
                    <span class="title_description">
                    RECOMMENDED  TEACHER
                </span>
                    <div class="title_line"></div>
                </div>
                <div class="container p-0">
                    <div class="item_content row">
                        @foreach($index->teacher() as $teacher)
                            <div class="col-xl-3 col-md-6 col-sm-6">
                                <div class="item text-center">
                                    <img src="{{ render_cover($teacher['avatar'], 'avatar') }}" alt="" class="user_avatar">
                                    <span class="teacher_name">
                                        {{ $teacher['username'] }}
                                    </span>
                                    <span class="teacher_job">
                                        特级职业：教师
                                    </span>
                                    <a href="{{ route('users.show', $teacher) }}" class="btn btn-sm btn-primary btn-circle">
                                        个人介绍
                                    </a>
                                    <div class="line"></div>
                                    <p class="teacher_qm">
                                        {{ $teacher['signature'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
        </div>
        @endif
    </div>
@endsection

@section('script')
    <script src="{{ mix('/js/front/index.js') }}"></script>
@endsection