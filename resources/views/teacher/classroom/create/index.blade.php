@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/teacher/classroom/create/index.css') }}">
@endsection
@section('header')
    @include('teacher.header', ['nav_name' => 'classroom'])
@endsection
@section('content')
    <div class="czh_teaching_content">
        <div class="container">
            @include('teacher.classroom.create.createClassroom-modal')
            <div class="row teachingCourseHeader">
                <div class="header_left col-sm-9 col-10">
                    <form action="">
                        {{--<div class="courseNameInp form-group">--}}
                            {{--<select class="form_content" style="width:100%;">--}}
                                {{--<option value="">分类</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        <div class="courseNameInp second form-group">
                            <p class="courseNameInp_p">班级名称</p>
                            <input type="text" name="title" value="{{ request()->title }}" class="form-control courseNameInp_inp">
                        </div>
                        <div class="courseNameInp second form-group">
                            <p class="courseNameInp_p">创建者</p>
                            <input type="text" name="username" value="{{ request()->username }}" class="form-control courseNameInp_inp">
                        </div>

                        <div class="courseSearch">
                            <button class="btn courseSearchBtn">搜索</button>
                        </div>
                    </form>
                </div>
                @if(auth('web')->user()->isAdmin())
                <div class="header_right col-sm-3 col-2">
                    <button class="btn createCourseBtn" data-toggle="modal" data-target="#createCourse">＋ 创建班级</button>
                </div>
                @endif
            </div>
            <div class="row coursesContent">
                @foreach($classrooms as $classroom)
                <div class="col-xl-3 col-md-4 col-sm-6 col-12 courseDataBody">
                    <div class="courseData">
                        <div class="courseImg">
                            <img src="{{ render_cover($classroom->cover, 'classroom') }}" alt="">
                        </div>
                        <div class="titStaPic">
                            <span class="courseTitle">{{ $classroom->title }}</span>
                            @if ($classroom->status == \App\Enums\Status::DRAFT)
                                <span class="courseStatus c_release badge badge-pill badge-warning text-uppercase">{{ \App\Enums\Status::getDescription($classroom->status) }}</span>
                            @elseif($classroom->status == \App\Enums\Status::PUBLISHED)
                            <span class="courseStatus c_unpublished badge badge-pill badge-warning text-uppercase">{{ \App\Enums\Status::getDescription($classroom->status) }}</span>
                            @else
                            <span class="courseStatus badge badge-pill badge-warning text-uppercase" style="color:red">{{ \App\Enums\Status::getDescription($classroom->status) }}</span>
                            @endif

                            <span class="courseCreator">共 {{ $classroom->courses_count }} 版本</span>
                            <span class="add_today">新增</span>
                            <span class="courseStudent">学员:<span> {{ $classroom->members_count }} </span></span>

                            {{--<span class="finish_task">任务完成学习:<span>123</span></span>--}}
                        </div>
                        <div class="courseFooter">
                            <a href="{{ route('manage.classroom.show', $classroom) }}" class="btn courseManage">
                                管理班级
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                {{-- 没有课程 --}}

                {{--<div style="width:100%;text-align:center;margin-top:10%;color:#bbb">没有课程 !</div>--}}

            </div>

            <nav class="pageNumber" aria-label="Page navigation example">
                {{ $classrooms->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
            </nav>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $('.typeItem').click(function () {
            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');
        });
    </script>
@endsection