<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>教师控制台</title>
    <link rel="stylesheet" href="{{ mix('/css/front/myTeaching/teachingCourses.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="/vendor/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ mix('/css/front/alert/alert.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('/css/theme.css') }}">
</head>
<body>
@include ('teacher.header', ['nav_name' => 'teach_course'])

<div class="czh_teaching_content">
    <div class="container">
        @include('teacher.createCourse-modal')
        <div class="row teachingCourseHeader">
            <div class="header_left col-sm-9 col-10">
                <form action="">
                    {{--<div class="courseTypeInp">--}}
                    {{--<select class="form-control" data-toggle="select" title="Simple select" data-live-search="true"--}}
                    {{--data-live-search-placeholder="Search ...">--}}
                    {{--<option>课程类型</option>--}}
                    {{--<option>正式课</option>--}}
                    {{--<option>直播课</option>--}}
                    {{--</select>--}}
                    {{--</div>--}}

                    <div class="courseNameInp form-group">
                        <p class="courseNameInp_p">课程名称</p>
                        <input type="text" name="title" class="form-control courseNameInp_inp" value="{{ request()->title }}">
                    </div>

                    {{--<div class="courseCreatorInp form-group">--}}
                    {{--<p class="courseCreatorInp_p">创建者</p>--}}
                    {{--<input type="text" class="form-control courseCreatorInp_inp">--}}
                    {{--</div>--}}

                    <div class="courseSearch">
                        <button class="btn courseSearchBtn">搜索</button>
                    </div>
                </form>
            </div>
            <div class="header_right col-sm-3 col-2">
                <button class="btn createCourseBtn" data-toggle="modal" data-target="#createCourse">＋ 创建课程</button>
            </div>
        </div>
        <div class="row coursesContent">
            @if(!$planTeachers->isEmpty())
                @foreach($planTeachers as $planTeacher)
                    <div class="col-xl-3 col-md-4 col-sm-6 col-12 courseDataBody">
                        <div class="courseData">
                            <div class="courseImg">
                                <img src="{{ render_cover($planTeacher->course->cover, 'course') }}" alt="">
                            </div>
                            <div class="titStaPic">
                                <span class="courseTitle">{{$planTeacher->plan->title}}</span>
                                @if($planTeacher->plan->status == 'draft')
                                    <span class="courseStatus c_release badge badge-pill badge-warning text-uppercase">未发布</span>
                                @elseif($planTeacher->plan->status == 'published')
                                    <span class="courseStatus c_unpublished badge badge-pill badge-warning text-uppercase">已发布</span>
                                @else
                                    <span class="courseStatus badge badge-pill badge-warning text-uppercase">已关闭</span>
                                @endif

                                <span class="courseCreator">{{ $planTeacher->course->title }}</span>

                                {{--@if ($planTeacher->plan->is_free)--}}
                                    {{--<span class="coursePrice"><span>免费</span></span>--}}
                                {{--@else--}}
                                    {{--@if($planTeacher->plan->coin_price)--}}
                                        {{--<span class="coursePrice">虚拟币：<span>{{ $planTeacher->plan->coin_price }}</span></span>--}}
                                    {{--@else--}}
                                        {{--<span class="coursePrice">价格：<span>{{ ftoy($planTeacher->plan->price).' 元' }}</span></span>--}}
                                    {{--@endif--}}
                                {{--@endif--}}

                                {{--<span class="courseStudent">学员：<span>{{ $planTeacher->plan->students_count }}</span></span>--}}
                            </div>
                            <div class="courseFooter">
                                @if($planTeacher->course->user_id == auth('web')->id() )
                                    <a href="{{ route('manage.plans.index', $planTeacher->course) }}"><button class="btn courseManage" style="margin-left: 45px">管理课程</button></a>
                                @endif

                                @if($planTeacher->user_id == auth('web')->id())
                                    <a href="{{ route('manage.plans.show', [$planTeacher->course, $planTeacher->plan]) }}"><button class="btn courseManage">管理版本</button></a>
                                @endif
                                {{--<div class="dropdown">--}}
                                {{--<button class="btn" type="button" data-toggle="dropdown" aria-haspopup="true"--}}
                                {{--aria-expanded="false">--}}
                                {{--<i class="iconfont">&#xe62b;</i>--}}
                                {{--</button>--}}
                                {{--<div class="dropdown-menu">--}}
                                {{--<a class="dropdown-item" href="#">版本管理</a>--}}
                                {{--<a class="dropdown-item" href="#">文件管理</a>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>

                @endforeach
            @else
                <div style="width:100%;text-align:center;margin-top:10%;color:#bbb">没有课程 !</div>
            @endif

        </div>

        <nav class="pageNumber" aria-label="Page navigation example">
            {{ $planTeachers->links('vendor.pagination.default', request()->all()) }}
        </nav>
    </div>
</div>
<script src="{{ mix('/js/front/app.js') }}"></script>
{{--<script src="/vendor/jquery/dist/jquery.min.js"></script>--}}
<script src="/vendor/select2/dist/js/select2.min.js"></script>
<script src="/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('vendor/jquery.validate.min.js') }}"></script>
<script src="{{ '/js/theme.js' }}"></script>
<script src="{{ mix('/js/front/header/index.js') }}"></script>
<script type="text/javascript">
    $('.typeItem').click(function () {
        $(this).siblings('.active').removeClass('active');
        $(this).addClass('active');
    });
</script>
@include('frontend.review.layouts._helpers')
</body>
</html>