@extends('teacher.exam.exam_layout')
@section('exam_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    {{--<link rel="stylesheet" href="{{ mix('/css/front/course/plan/index.css') }}">--}}
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/subject_preview.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/insert_subject.css') }}">
@endsection
@section('exam_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <!-- Attach a new card -->
        <form class="form-default" id="question-create-form" action="{{ route('manage.question.store') }}"
              method="post">
            {{ csrf_field() }}
            <div class="card">
                <div class="card-body row_content" style="min-height:500px">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-lg-8">
                                <h6>添加题目</h6>
                            </div>
                        </div>
                        <hr class="course_hr">
                    </div>
                    <div class="bd-example">
                        <div class="container">
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-1 text-right p-0">题目标题</label>
                                                    <script id="editor" name="title" type="text/plain"
                                                            class="col-md-10 pl-3"></script>
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
                                                    <label class="control-label col-md-1 text-right p-0">标签</label>
                                                    <div class="col-md-10 p-0 pl-3">
                                                        <select class="form-control" name="tags[]" data-toggle="select"
                                                                title="Option groups" data-live-search="true"
                                                                data-live-search-placeholder="Search ..."
                                                                multiple id="label">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row question_type">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-right mt-1 p-0">题目类型</label>
                                                    <div class="col-md-8 col-xl-9 col-lg-8 row ml-0 p-0 mt-1 pl-3">
                                                        <div class="col-2 pl-0">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="type"
                                                                       class="custom-control-input radio_type"
                                                                       id="type1"
                                                                       value="{{ \App\Enums\QuestionType::SINGLE }}">
                                                                <label class="custom-control-label"
                                                                       for="type1">单选题</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 pl-0">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="type"
                                                                       class="custom-control-input checkbox_type"
                                                                       id="type2"
                                                                       value="{{ \App\Enums\QuestionType::MULTIPLE }}">
                                                                <label class="custom-control-label"
                                                                       for="type2">多选题</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 pl-0">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="type" checked
                                                                       class="custom-control-input subjective_type"
                                                                       id="type3"
                                                                       value="{{ \App\Enums\QuestionType::ANSWER }}">
                                                                <label class="custom-control-label"
                                                                       for="type3">主观题</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <button class="btn btn-primary add-radio" type="button">+ 添加选项</button>
                            </div>

                            <div class="row">
                                <button class="btn btn-primary add-checkbox" type="button">+ 添加选项</button>
                            </div>

                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-right mt-1 p-0">题目难度</label>
                                                    <div id="star" data-score="" class="pl-3"></div>
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
                                                    <label class="control-label col-md-2 text-right mt-1 p-0">答案解析</label>
                                                    <textarea class="form-control col-md-8 ml-3" name="explain"
                                                              placeholder="请输入您的答案解析..."
                                                              rows="3" resize="none"></textarea>
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
                                                    <label class="control-label col-md-2 text-right mt-1"></label>
                                                    <button class="btn btn-primary btn-submit ml-3">提交</button>
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
    <script src="{{ '/vendor/raty/jquery.raty.min.js' }}"></script>
    <script src="{{ mix('/js/teacher/exam/insert_subject.js') }}"></script>
    <script>
        window.onload = function () {
            var ue = UE.getEditor('editor', {
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });

            /**
             * 标签相关的select2搜索+多选
             */
            var labels = @json($labels);

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
                multiple: true,
                data: labels,
            });

            $('#star').raty({
                starOff: '/imgs/star-off.svg',
                starOn: '/imgs/star-on.svg',
                size: 30,
                score: function () {
                    return $(this).attr('data-score');
                },
                click: function (score, evt) {
                    console.log('ID: ' + $(this).attr('id') + "\nscore: " + score + "\nevent: " + evt.type);
                }
            });

            /**
             * 提交创建题目时候的表单验证
             */
                // 指定表单
            var form = $('#question-create-form');

            FormValidator.init(form, {
                rules: {
                    title: {
                        required: true,
                        maxlength: 20
                    },
                },
                messages: {
                    title: {
                        required: "标题不能为空！",
                        maxlength: '标题长度不能超过20'
                    },
                },
            }, function () {
                $.ajax({
                    url: form.attr('action'),
                    type: 'post',
                    data: form.serialize(),
                    success: function (res) {
                        if (res.status == 200) {
                            edu.alert('success', res.message);
                            window.location.reload();
                        } else {
                            edu.alert('danger', res.message)
                        }
                    },

                });
                
                return false;
            });
        };
    </script>
@endsection