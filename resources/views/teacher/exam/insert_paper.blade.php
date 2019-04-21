@extends('teacher.exam.exam_layout')
@section('exam_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ '/vendor/select2/dist/css/select2.min.css' }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/insert_subject.css') }}">
@endsection
@section('exam_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <!-- Attach a new card -->
        <form class="form-default" id="paper-create-form" action="{{ route('manage.paper.store') }}"
              method="post">
            {{ csrf_field() }}
            <div class="card">
                <div class="card-body row_content" style="min-height:500px">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-lg-8">
                                <h6>添加试卷</h6>
                            </div>
                        </div>
                        <hr class="course_hr">
                    </div>
                    <div class="bd-example">
                        <div class="container">
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent justify-content-left">
                                                    <label class="control-label col-md-2 text-right mr-1 pt-2">试卷名称</label>
                                                    <input type="text" name="title" id="title"
                                                           class="form-control col-lg-8" placeholder="请输入试卷名称">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent justify-content-left">
                                                    <label class="control-label col-md-2 text-right mr-1 pt-2">用时（分钟）</label>
                                                    <input type="number" name="expect_time" id="expect_time"
                                                           class="form-control col-lg-8" placeholder="预计用时">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 20px;">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-right mr-1 pt-2">标签</label>
                                                    <div class="col-md-8 p-0">
                                                        <select class="form-control" name="tags[]"
                                                                data-toggle="select"
                                                                title="Option groups"
                                                                data-live-search="true"
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
                            <div class="row add_question_content">
                                <div class="modal-body justify-content-center">
                                    <div class="row m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-center mr-1 p-0"></label>
                                                    <button class="btn btn-primary add-subject"
                                                             data-toggle="modal"
                                                            data-target=".bd-example-modal-lg"
                                                            data-url="{{ route('manage.question.list.json') }}">
                                                        + 添加题目
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row question_count">
                                <div class="modal-body justify-content-center">
                                    <div class="row m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-center mr-1 p-0"></label>
                                                    <div class="question_length mt-2">
                                                        <input type="hidden" name="questions_count"
                                                               id="questions_count_id" value="0">
                                                        已添加<span class="question_length_content">0</span>道题
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="show-selected-question">

                            </div>
                            {{--<div class="row">--}}
                                {{--<div class="col-12 p-0">--}}
                                    {{--<div class="modal-body justify-content-center">--}}
                                        {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                                            {{--<div class="col-md-10">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<div class="input-group input-group-transparent">--}}
                                                        {{--<label class="control-label col-md-2 text-center mr-1 p-0">题目标题</label>--}}
                                                        {{--<input type="text" name="title" id="title"--}}
                                                               {{--class="form-control col-lg-8" placeholder="题目名称">--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-12 p-0">--}}
                                    {{--<div class="modal-body justify-content-center">--}}
                                        {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                                            {{--<div class="col-md-10">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<div class="input-group input-group-transparent">--}}
                                                        {{--<label class="control-label col-md-2 text-center mr-1 p-0">分值</label>--}}
                                                        {{--<input type="text" name="title" id="title"--}}
                                                               {{--class="form-control col-lg-6" placeholder="题目分值">--}}
                                                        {{--<button class="btn btn-primary remove-question">删除</button>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-right mr-1 pt-2">总分</label>
                                                    <input type="text" name="total_score" id="total_score"
                                                           class="form-control col-lg-5"
                                                           placeholder="请输入试卷总分">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-right mr-1 pt-2">及格分</label>
                                                    <input type="text" name="pass_score" id="title"
                                                           class="form-control col-lg-5"
                                                           placeholder="请输入试卷及格分">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-right mr-1 pt-2">备注</label>
                                                    <script id="editor" name="extra" type="text/plain"
                                                            class="col-md-8 p-0"></script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-2 text-center mr-1 p-0"></label>
                                                    <button type="submit"
                                                            class="btn btn-primary btn-submit">
                                                        提交
                                                    </button>
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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" id="add-question-modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">选择题目</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="show-question-page">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">选择完毕</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade preview-question-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">题目预览</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="show-question-div">

                </div>
            </div>
        </div>
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

            var labels = {!! $labels !!};

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

            /**
             * 弹出选择题目的模态框
             */
            $('.add-subject').on({
                click: function (e) {
                    var url = $(this).data('url');

                    getQuestions(url);

                    $('#add-question-modal').modal('toggle');
                    return false;
                }
            });

            /**
             * 为分页中的a标签绑定点击事件
             */
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                getQuestions($(this).attr('href'));
            });

            /**
             * 为搜索绑定事件
             */
            $(document).on('click', '#search-question-btn', function () {
                var $form = $('#search-question-form');
                getQuestions($form.attr('action'), $form.serialize());
            });

            /**
             * 为预览题目添加
             */
            $(document).on('click', '.show-question-info', function () {
                //获取HTMl, 放入模态框中
                var $html = $('#show-question-info-' + $(this).data('id'));
                $('#show-question-div').html($html);
            });

            /**
             * 获取题目
             */
            function getQuestions(url, data = {}) {
                // 请求后台题目列表
                $.ajax({
                    url: url,
                    type: 'get',
                    data: data,
                    dataType: 'text',
                    success: function (res) {
                        $('#show-question-page').html(res);
                        edu.alert('success', '获取题目成功!');
                    }
                });
            }

            /**
             * 提交创建题目时候的表单验证
             */
                // 指定表单
            var form = $('#paper-create-form');

            FormValidator.init(form, {
                rules: {
                    title: {
                        required: true,
                        maxlength: 20
                    },
                    expect_time: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    total_score: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    pass_score: {
                        required: true,
                        digits: true,
                        min: 1,
                    },
                },
                messages: {
                    title: {
                        required: "标题不能为空！",
                        maxlength: '标题长度不能超过20'
                    },
                    expect_time: {
                        required: "预计考试时间不能为空！",
                        digits: '预计考试时间必须是整数',
                        min: '预计考试时间必须大于1'
                    },
                    total_score: {
                        required: "总分不能为空！",
                        digits: '总分必须是整数',
                        min: '总分必须大于1'
                    },
                    pass_score: {
                        required: "及格分数不能为空！",
                        digits: '及格分数必须是整数',
                        min: '及格分数必须大于1'
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
                    }

                });

                return false;
            });


        };
    </script>
@endsection