@extends('teacher.exam.exam_layout')
@section('exam_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/course/plan/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/subject_preview.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
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
                                <h6>题目编辑</h6>
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
                                                    <label class="control-label col-md-1 text-center mr-1 p-0">学科</label>
                                                    <select class="form-control col-md-6"
                                                            id="exampleFormControlSelect1">
                                                        <option value='' disabled selected
                                                                style='display:none;'>请选择学科
                                                        </option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
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
                                                    <div class="col-md-10 p-0">
                                                        <select class="form-control" data-toggle="select"
                                                                title="Option groups" data-live-search="true"
                                                                data-live-search-placeholder="Search ..."
                                                                multiple>
                                                            <optgroup label="Components">
                                                                <option selected>Alerts</option>
                                                                <option>Badges</option>
                                                                <option>Buttons</option>
                                                                <option>Cards</option>
                                                            </optgroup>
                                                            <optgroup label="Utilities">
                                                                <option>Borders</option>
                                                                <option>Colors</option>
                                                                <option>Typography</option>
                                                            </optgroup>
                                                        </select>
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
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-1 text-center mr-1 p-0">题干</label>
                                                    <script id="editor" name="summary" type="text/plain"
                                                            class="col-md-10">123123
                                                    </script>
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
                                                    <label class="control-label col-md-2 text-right mt-1">题目类型</label>
                                                    <div class="col-md-8 col-xl-9 col-lg-8 row ml-0 p-0 mt-1">
                                                        <div class="col-2 pl-0">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="serialize_mode"
                                                                       class="custom-control-input radio_type"
                                                                       id="customRadio-1" value="none"
                                                                       readonly="" checked disabled>
                                                                <label class="custom-control-label"
                                                                       for="customRadio-1">单选题</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 pl-0">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="serialize_mode"
                                                                       class="custom-control-input checkbox_type"
                                                                       id="customRadio-2" value="serialized"
                                                                       disabled>
                                                                <label class="custom-control-label"
                                                                       for="customRadio-2">多选题</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 pl-0">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="serialize_mode"
                                                                       class="custom-control-input subjective_type"
                                                                       id="customRadio-3" value="finished"
                                                                       disabled>
                                                                <label class="custom-control-label"
                                                                       for="customRadio-3">主观题</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row radio_question_type">
                                <div class="modal-body justify-content-center">
                                    <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="input-group input-group-transparent">
                                                    <label class="control-label col-md-1 text-center mr-1 p-0">选项A</label>
                                                    <script id="editor_1" name="summary"
                                                            type="text/plain"
                                                            class="col-md-10 p-0">1231231
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="row question_type_style">
                                                <div class="custom-control custom-radio col-9">
                                                    <input type="radio" name="serialize_mode"
                                                           class="custom-control-input"
                                                           id="customRadio6" value="none">
                                                    <label class="custom-control-label"
                                                           for="customRadio6">正确选项</label>
                                                </div>
                                                <a href="javascript:;" class="float-right" id="del-radio">
                                                    <i class="iconfont">
                                                        &#xe62e;
                                                    </i>
                                                    删除
                                                </a>
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
                                                    <label class="control-label col-md-2 text-right mt-1">题目难度</label>
                                                    <div id="star" data-score="3"></div>
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
                                                              placeholder="This is a fixed height textarea"
                                                              rows="3" resize="none"></textarea>
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
                readonly: 'true',
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });

            var ue = UE.getEditor('editor_1', {
                readonly: 'true',
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
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
        };
    </script>
@endsection