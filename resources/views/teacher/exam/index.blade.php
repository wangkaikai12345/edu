@extends('teacher.exam.exam_layout')
@section('exam_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/topic_list.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
@endsection
@section('exam_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <!-- Attach a new card -->
        <form class="form-default">
            <div class="card">
                <div class="card-body row_content" style="min-height:500px">
                    <div class="row_div">
                        <div class="row pr-4">
                            <div class="col-lg-8">
                                <h6>题目列表</h6>
                            </div>
                        </div>
                        <hr class="course_hr">
                    </div>
                    <div class="bd-example">
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group input-group-transparent">
                                        <label class="control-label col-lg-4 text-right pr-1">标题</label>
                                        <input type="text" name="title" id="title" value="{{ request()->title }}"
                                               class="form-control col-lg-8" placeholder="请输入标题">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 p-0 row second">
                                <div class="col-lg-6 p-0">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <label class="control-label col-lg-2 text-left mr-3">出题人</label>
                                            <input type="text" name="username" id="title"
                                                   value="{{ request()->username }}" class="form-control col-lg-8"
                                                   placeholder="出题人姓名">
                                        </div>
                                    </div>
                                </div>

                                {{--<div class="form-group search_tag">--}}
                                    {{--<select id="label" name="tag" type="text"--}}
                                            {{--class="form-control col-md-12 col-lg-9 col-xl-9">--}}
                                        {{--<option value="">请输入标签</option>--}}
                                        {{--@foreach($labels as $label)--}}
                                            {{--<option value="{{ $label->text }}" {{ request()->tag == $label->text ? 'selected' : '' }}>{{ $label->text }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}

                                <div class="col-lg-6 pl-0">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <input type="text" name="tag" id="title" class="form-control col-lg-11"
                                                   value="{{ request()->tag }}" placeholder="标签">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-1 pl-0">
                                <button type="submit" class="btn btn-primary search float-left">搜索</button>
{{--                                <a href="{{ route('manage.question.index') }}" class="btn btn-primary search float-left">全部</a>--}}
                            </div>
                        </div>
                        <div class="table_content p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col" width="350">题目</th>
                                    <th scope="col">类型</th>
                                    <th scope="col" width="200">标签</th>
                                    <th scope="col">出题人</th>
                                    <th scope="col">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($questions as $question)
                                    <tr>
                                        <th scope="colgroup">
                                            {!! $question->title !!}
                                        </th>
                                        <td>{{ \App\Enums\QuestionType::getDescription($question->type) }}</td>
                                        <td>
                                            @foreach($question->tags as $tag)
                                                <span class="badge badge-success">{{ $tag->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $question->user->username }}</td>
                                        <td>
                                            <a class="float-left" href="{{ route('manage.question.show', $question) }}">预览</a>
                                            {{--<a class="float-left"--}}
                                            {{--href="{{ route('teacher.exam.subject.preview') }}">编辑</a>--}}
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
                    {{ $questions->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </nav>
            </div>
        </form>
    </div>

    <script src="/vendor/select2/dist/js/select2.min.js"></script>
    <script>
        var labels = {};
        $("#label").select2({
            maximumSelectionSize: 20,
            placeholder: '请输入标签名称',
            createSearchChoice: function (term, data) {
                if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                    return {id: term, text: term};
                }
            },
            multiple: false,
            data: labels,
        });
    </script>
@endsection