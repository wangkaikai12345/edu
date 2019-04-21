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
                                <h6>试卷批阅</h6>
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
                            <div class="col-lg-5">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <label class="control-label col-lg-3 text-left pr-0">试卷标题</label>
                                            <input type="text" name="title" id="title" class="form-control col-lg-8"
                                                   placeholder="关键字" value="{{ request()->title }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 p-0 row second">
                                <div class="col-lg-11 p-0">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <label class="control-label col-lg-2 text-left pl-0 pr-0">学员</label>
                                            <input type="text" name="username" id="title" class="form-control col-lg-9"
                                                   placeholder="学员" value="{{ request()->username }}">
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
                                    <th scope="col" width="130">试卷名称</th>
                                    <th scope="col" width="90">所属任务</th>
                                    <th scope="col">考试开始时间</th>
                                    <th scope="col" width="60">用时</th>
                                    <th scope="col" width="60">总分</th>
                                    <th scope="col" width="60">及格</th>
                                    <th scope="col" width="100">答题学员</th>
                                    <th scope="col" width="100">状态</th>
                                    <th scope="col" width="100">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($paperResults as $paperResult)
                                <tr>
                                    <th scope="colgroup">
                                        {{ $paperResult->paper_title }}
                                    </th>
                                    <td>{{ $paperResult->task->title }}</td>
                                    <td>{{ $paperResult->start_at }}</td>
                                    <td>{{ ceil($paperResult->time / 60) }} 分钟</td>
                                    <td>{{ $paperResult->paper->total_score }}</td>
                                    <td>{{ $paperResult->paper->pass_score }}</td>
                                    <td>{{ $paperResult->user->username }}</td>
                                    <td>
                                        @if($paperResult->is_mark == 0)
                                            <span class="text-danger">未批阅</span>
                                        @else
                                            <span class="text-success">已批阅</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($paperResult->is_mark == 0)
                                            <a class="float-left not-mark"
                                               href="{{ route('manage.paper.result.show', $paperResult) }}">阅卷</a>
                                        @else
                                            <span class="float-left not-mark disabled" style="background-color: #ccc">已阅</span>
                                        @endif

                                    </td>
                                </tr>
                                @empty
                                    {{-------------------- 暂无数据的显示 --------------------}}
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
                    {{ $paperResults->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </nav>
            </div>
        </form>
    </div>
@endsection