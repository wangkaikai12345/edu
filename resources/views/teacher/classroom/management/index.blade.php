@extends('teacher.classroom.classroom_layout')
@section('classroom_style')
    <link rel="stylesheet" href="{{ '/vendor/select2/dist/css/select2.min.css' }}">
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/student/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/classroom/management/index.css') }}">
@endsection
@section('classroom_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <form class="form-default">
            <div class="card teacher_style">
                <div class="card-body row_content">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="management_item">
                                    <div class="management_title">
                                        设置
                                    </div>
                                    <div class="management_item_content">
                                        <div class="management_item_content_title">
                                            简介
                                        </div>
                                        <div class="management_item_content_number">
                                            @if(empty($classroom->description))
                                            <span class="badge badge-lg badge-pill badge-danger text-uppercase">未设置</span>
                                            @else
                                            <span class="badge badge-lg badge-pill badge-success text-uppercase">已设置</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('manage.classroom.base', $classroom) }}">
                                            <div class="management_item_content_link">
                                                <i class="iconfont">&#xe8fa;</i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="management_item_content">
                                        <div class="management_item_content_title">
                                            图片
                                        </div>
                                        <div class="management_item_content_number">
                                            @if(empty($classroom->cover))
                                                <span class="badge badge-lg badge-pill badge-danger text-uppercase">未设置</span>
                                            @else
                                                <span class="badge badge-lg badge-pill badge-success text-uppercase">已设置</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('manage.classroom.base', $classroom) }}">
                                            <div class="management_item_content_link">
                                                <i class="iconfont">&#xe8fa;</i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="management_item_content">
                                        <div class="management_item_content_title">
                                            价格
                                        </div>
                                        <div class="management_item_content_number {{ empty($classroom->price) ? 'text-danger' : '' }}">
                                            {{ ftoy($classroom->price) }} 元
                                        </div>
                                        <a href="{{ route('manage.classroom.price', $classroom) }}">
                                            <div class="management_item_content_link">
                                                <i class="iconfont">&#xe8fa;</i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="management_item">
                                    <div class="management_title">
                                        内容
                                    </div>
                                    <div class="management_item_content">
                                        <div class="management_item_content_title">
                                            版本数
                                        </div>
                                        {{-- 如果数字为0请使用这个div --}}
                                        <div class="management_item_content_number {{ empty($plansCount) ? 'text-danger' : '' }}">
                                            {{ $plansCount }}
                                        </div>
                                        <a href="{{ route('manage.classroom.course.list', $classroom) }}">
                                            <div class="management_item_content_link">
                                                <i class="iconfont">&#xe8fa;</i>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="management_item_content">
                                        <div class="management_item_content_title">
                                            阶段数
                                        </div>
                                        <div class="management_item_content_number {{ empty($chaptersCount) ? 'text-danger' : '' }}">
                                            {{ $chaptersCount }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="management_item">
                                    <div class="management_title">
                                        学员
                                    </div>
                                    <div class="management_item_content">
                                        <div class="management_item_content_title">
                                            学员数
                                        </div>
                                        <div class="management_item_content_number {{ empty($memberCount) ? 'text-danger' : '' }}">
                                            {{ $memberCount }}
                                        </div>
                                        <a href="{{ route('manage.classroom.member.list', $classroom) }}">
                                            <div class="management_item_content_link">
                                                <i class="iconfont">&#xe8fa;</i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('classroom_script')
@endsection