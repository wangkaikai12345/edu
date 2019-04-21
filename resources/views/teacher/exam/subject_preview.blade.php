@extends('teacher.exam.exam_layout')
@section('exam_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/course/plan/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/subject_preview.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/insert_subject.css') }}">
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
                                        <h6>题目预览</h6>
                                    </div>
                                    <div class="col-lg-4 text-lg-right">
                                        <a href="{{ route('manage.question.index') }}" class="btn">
                                            <span class="btn-inner--icon">
                                                <i class="iconfont">&#xe644;</i>
                                            </span>
                                            返回题目列表
                                        </a>
                                    </div>
                                </div>
                                <hr class="course_hr">
                            </div>
                            <div class="bd-example">
                                <div class="row">
                                    <div class="modal-body justify-content-center">
                                        <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent">
                                                        <label class="control-label col-md-1 text-center mr-1 p-0">题干</label>
                                                        <script id="editor" name="summary" type="text/plain"
                                                                class="col-md-10">{!! $question->title !!}

                                                        </script>
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
                                                        <label class="control-label col-md-1 text-center mr-1 p-0">标签</label>
                                                        <div class="code-example m-0">
                                                            @foreach($question->tags as $tag)
                                                                <span class="badge badge-success">{{ $tag->name }}</span>
                                                            @endforeach

                                                            {{--<div class="bootstrap-tagsinput">--}}
                                                                {{--@foreach($question->tags as $tag)--}}
                                                                    {{--<span class="tag badge badge-primary">{{ $tag->name }}--}}
                                                                            {{--<span data-role="remove"></span>--}}
                                                                        {{--</span>--}}
                                                                {{--@endforeach--}}
                                                                {{--<span class="tag badge badge-primary"--}}
                                                                      {{--style="opacity: 0;">&nbsp;--}}
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
                                <div class="row">
                                    <div class="modal-body justify-content-center">
                                        <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent">
                                                        <label class="control-label col-md-2 text-right mt-1">题目类型</label>
                                                        <div class="col-md-8 col-xl-9 col-lg-8 row ml-0 p-0 mt-1">
                                                            <div class="col-2 pl-0">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" name="serialize_mode"
                                                                           class="custom-control-input"
                                                                           id="customRadio1" value="none"
                                                                           disabled="" {{ ($question->type == \App\Enums\QuestionType::SINGLE) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                           for="customRadio1">单选题</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 pl-0">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" name="serialize_mode"
                                                                           class="custom-control-input"
                                                                           id="customRadio2" value="serialized"
                                                                           disabled="" {{ ($question->type == \App\Enums\QuestionType::MULTIPLE) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                           for="customRadio2">多选题</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 pl-0">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" name="serialize_mode"
                                                                           class="custom-control-input"
                                                                           id="customRadio3" value="finished"
                                                                           disabled="" {{ ($question->type == \App\Enums\QuestionType::ANSWER) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                           for="customRadio3">主观题</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($question->type != \App\Enums\QuestionType::ANSWER)
                                    @php
                                        $ops = ['A', 'B', 'C', 'D', 'E', 'F', 'H'];
                                    @endphp
                                    @foreach($question->options as $key => $option)
                                        <div class="row radio_question_type">
                                            <div class="modal-body justify-content-center">
                                                <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <div class="input-group input-group-transparent">
                                                                <label class="control-label col-md-1 text-center mr-1 p-0">选项{{ $ops[$key] }}</label>
                                                                <script id="editor_{{ $key }}" name="summary"
                                                                        type="text/plain"
                                                                        class="col-md-10 p-0">{!! $option !!}</script>
                                                            </div>
                                                        </div>
                                                        <div class="row question_type_style">
                                                            <div class="custom-control custom-radio col-9">
                                                                @if($question->type == \App\Enums\QuestionType::SINGLE)
                                                                    <input type="radio" name="serialize_mode"
                                                                           class="custom-control-input"
                                                                           id="customRadio6"
                                                                           value="none" {{ in_array($key, $question->answers) ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="checkbox" name="serialize_mode[]"
                                                                           class="custom-control-input"
                                                                           id="customRadio6"
                                                                           value="none" {{ in_array($key, $question->answers) ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label"
                                                                       for="customRadio6">正确选项</label>
                                                            </div>
                                                            {{--<a href="javascript:;" class="float-right" id="del-radio">--}}
                                                            {{--<i class="iconfont">--}}
                                                            {{--&#xe62e;--}}
                                                            {{--</i>--}}
                                                            {{--删除--}}
                                                            {{--</a>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="row">
                                    <div class="modal-body justify-content-center">
                                        <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent">
                                                        <label class="control-label col-md-2 text-right mt-1">题目难度</label>
                                                        <div class="star">
                                                            @for($i = 0; $i < 5; $i++)
                                                                @if($i < $question->rate)
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="modal-body justify-content-center">
                                        <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent">
                                                        <label class="control-label col-md-2 text-right mt-1">答案解析</label>
                                                        <textarea class="form-control col-md-8"
                                                                  placeholder="This is a fixed height textarea" rows="3"
                                                                  resize="none"
                                                                  readonly="">{{ $question->explain }}</textarea>
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
        </div>
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

            @if($question->type != \App\Enums\QuestionType::ANSWER)
            @foreach($question->options as $key => $option)
            UE.getEditor('editor_' + {{ $key }}, {
                readonly: 'true',
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });
            @endforeach
            @endif
        };
    </script>
@endsection