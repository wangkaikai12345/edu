@extends('teacher.exam.exam_layout')
@section('exam_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/topic_list.css') }}">
@endsection
@section('exam_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <!-- Attach a new card -->
        <form class="form-default">
            <div class="card">
                <div class="card-body row_content" style="min-height:500px">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-lg-8">
                                <h6>试卷列表</h6>
                            </div>
                            {{--<div class="col-lg-4 text-lg-right">--}}
                            {{--<button type="button" class="btn btn-primary add-plan mr-4" data-toggle="modal"--}}
                            {{--data-target=".bd-example-modal-lg">+ 创建教学版本--}}
                            {{--</button>--}}
                            {{--</div>--}}
                        </div>
                        <hr class="course_hr">
                    </div>
                    <div class="bd-example">
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group input-group-transparent">
                                        <label class="control-label col-lg-4 text-left pr-0">标题关键字</label>
                                        <input type="text" name="title" value="{{ request()->title }}" id="title"
                                               class="form-control col-lg-8" placeholder="关键字">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 p-0 row second">
                                <div class="col-lg-6 p-0">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <label class="control-label col-lg-2 text-left mr-3">出题人</label>
                                            <input type="text" name="username" id="username"
                                                   value="{{ request()->username }}" class="form-control col-lg-8"
                                                   placeholder="出题人">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 pl-0">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <input type="text" name="tag" class="form-control col-lg-11"
                                                   value="{{ request()->tag }}" placeholder="标签">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 pl-0">
                                <button type="submit" class="btn btn-primary search float-left">搜索</button>
                            </div>
                        </div>
                        <div class="table_content p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>试卷名称</th>
                                    <th>建议时间</th>
                                    <th>题目总分</th>
                                    <th>题数</th>
                                    <th>标签</th>
                                    <th>出题人</th>
                                    <th>备注</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($papers as $paper)
                                    <tr>
                                        <td>
                                            <div style="width:100px;">
                                                {{$paper->title}}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:60px;">
                                                {{ ceil($paper->expect_time / 60) }} 分钟
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:60px;">
                                                {{ $paper->total_score }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:30px;">
                                                {{ $paper->questions_count }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:200px;">
                                                @foreach($paper->tags as $tag)
                                                    <span class="badge badge-success">{{ $tag->name }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:70px;">
                                                {{ $paper->user->username }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:50px;">
                                                {!! $paper->extra !!}
                                            </div>
                                        </td>
                                        <td>
                                            <a class="float-left" href="{{ route('manage.paper.show', $paper) }}">预览</a>
                                            {{--<ul class="float-left nav user-nav">--}}
                                            {{--<li class="user-avatar-li nav-hover float-left">--}}
                                            {{--<div class="dropdown">--}}
                                            {{--<a class="more" data-toggle="dropdown"--}}
                                            {{--style="border:0;" aria-expanded="false">--}}
                                            {{--更多--}}
                                            {{--</a>--}}
                                            {{--<div class="dropdown-menu">--}}
                                            {{--<a class="dropdown-item"--}}
                                            {{--href="{{ route('teacher.exam.update.subject') }}"--}}
                                            {{--target="_blank">--}}
                                            {{--<i class="iconfont">&#xe60f;</i>--}}
                                            {{--<span>编辑</span>--}}
                                            {{--</a>--}}
                                            {{--<a class="dropdown-item" href="javascript:;">--}}
                                            {{--<i class="iconfont">&#xe62e;</i>--}}
                                            {{--<span>删除</span>--}}
                                            {{--</a>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</li>--}}
                                            {{--</ul>--}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty">
                                        <td colspan="20">
                                            暂无数据
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <nav class="pageNumber" aria-label="Page navigation example" style="margin:0 auto;">
                    {{ $papers->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </nav>
            </div>
        </form>
    </div>
@endsection