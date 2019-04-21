@extends('teacher.exam.exam_layout')
@section('exam_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/course/plan/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/subject_preview.css') }}">
@endsection
@section('exam_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0" style="border-radius: 0.375rem;box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);">
        <!-- Attach a new card -->
        <form class="form-default">
            <div class="card">
                <div class="card-body row_content" style="min-height:500px">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-lg-8">
                                <h6>试卷预览</h6>
                            </div>
                            <div class="col-lg-4 text-lg-right">
                                <a href="{{ route('manage.paper.index') }}" class="btn">
                                            <span class="btn-inner--icon">
                                                <i class="iconfont">&#xe644;</i>
                                            </span>
                                    返回试卷列表
                                </a>
                            </div>
                        </div>
                        <hr class="course_hr">
                    </div>
                    <div class="bd-example">
                        <div class="container">
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 justify-content-center">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent justify-content-left">
                                                    <label class="control-label col-md-2 text-right mr-3 p-0">试卷名称</label>
                                                    <input type="text" name="title" id="title"
                                                           class="form-control col-lg-8" value="{{ $paper->title }}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-right mr-3 p-0">标签</label>
                                                    <div class="code-example m-0 col-md-8 p-0">
                                                        @foreach($paper->tags as $tag)
                                                            <span class="badge badge-success">{{ $tag->name }}</span>
                                                        @endforeach

                                                        {{--<div class="bootstrap-tagsinput">--}}
                                                            {{--@foreach($paper->tags as $tag)--}}
                                                                {{--<span class="tag badge badge-primary">{{ $tag->name }}--}}
                                                                            {{--<span data-role="remove"></span>--}}
                                                                        {{--</span>--}}
                                                            {{--@endforeach--}}
                                                            {{--<span class="tag badge badge-primary" style="opacity: 0;">&nbsp;--}}
                                                                        {{--<span data-role="remove"></span>--}}
                                                                    {{--</span>--}}
                                                        {{--</div>--}}
                                                        {{--<input type="text" class="form-control"--}}
                                                               {{--value="Bucharest, Cluj, Iasi, Timisoara, Piatra Neamt"--}}
                                                               {{--data-toggle="tags" style="display: none;">--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach($paper->qs as $question)
                                <div class="row">
                                    <div class="modal-body justify-content-center">
                                        <div class="row mt-3 m-0 ml-8 justify-content-center">
                                            <div class="col-md-9 p-0">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent justify-content-left">
                                                        <label class="control-label col-md-2 text-right mr-3 ml-2 p-0 paper_title">固定题目
                                                            题目标题</label>
                                                        <div class="col-lg-7 paper_content p-0">
                                                            {!! $question->question->title !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="modal-body justify-content-center">
                                        <div class="row mt-3 m-0 ml-8 justify-content-center">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent justify-content-left">
                                                        <label class="control-label col-md-2 text-right mr-3 p-0">分值</label>
                                                        <input type="text" name="title" id="title"
                                                               class="form-control col-lg-8" placeholder="请输入试卷名称"
                                                               readonly value="{{ $question->score }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 justify-content-center">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent justify-content-left">
                                                    <label class="control-label col-md-2 text-right mr-3 p-0">建议考试时间</label>
                                                    <input type="text" name="title" id="title"
                                                           class="form-control col-lg-8"
                                                           value="{{ $paper->expect_time }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 justify-content-center">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent justify-content-left">
                                                    <label class="control-label col-md-2 text-right mr-3 p-0">备注</label>
                                                    <div type="text" id="chapter-goals-p" class="form-control col-md-8"
                                                         readonly>{!! $paper->extra !!}</div>
                                                </div>
                                            </div>
                                        </div>
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
@section('exam_script')
    <script src="/vendor/ueditor/ueditor.config.js"></script>
    <script src="/vendor/ueditor/ueditor.all.js"></script>
    <script>
        window.onload = function () {
            var ue = UE.getEditor('editor', {
                readonly: true,
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });
        };
    </script>
@endsection