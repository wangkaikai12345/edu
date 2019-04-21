@extends('teacher.classroom.classroom_layout')
@section('classroom_style')
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ mix('/css/teacher/classroom/teacher/index.css') }}">
@endsection
@section('classroom_content')

    <div class="czh_task_content col-xl-9 col-md-12 col-12">
        <div class="operation_header">
            <p>教师设置</p>
        </div>
        <hr class="course_hr">
        <div class="bd-example">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#teacher-setting" role="tab"
                       aria-controls="home" aria-selected="true">教师设置</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#headmaster-setting" role="tab"
                       aria-controls="profile" aria-selected="false">班主任设置</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#assistant-setting" role="tab"
                       aria-controls="contact" aria-selected="false">助教设置</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fad  show active" id="teacher-setting" role="tabpanel"
                     aria-labelledby="home-tab">
                    <div class="operation_content">
                        <div class="addTeacherTop">
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">设置教师</label>
                                <ul class="teacher_collect teacher_collect_teacher" data-sort-url="">
                                    @foreach($classroom->teachers as $teacher)
                                        <li class="teacherItem">
                                            <div class="moveIcon">
                                                <i class="iconfont">&#xe64b;</i>
                                            </div>
                                            <div class="teacherImg">
                                                <img src="{{ render_cover($teacher->avatar, 'avatar') ?? '/imgs/avatar.png' }}" alt="">
                                            </div>
                                            <p class="teacherRole teacherRole_teacher">{{ $teacher->username }}</p>
                                            {{--<div class="teacherStatus custom-control custom-checkbox">--}}
                                                {{--<input type="checkbox" class="custom-control-input" id="customCheck1">--}}
                                                {{--<label class="custom-control-label" for="customCheck1">显示</label>--}}
                                            {{--</div>--}}
                                            <div class="teacherRemove">
                                                <i class="iconfont" data-type="teacher" data-url="{{ route('manage.classroom.teacher.delete', [$classroom, $teacher]) }}">&#xe6f2;</i>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="col-10 p-0 select_wrap">
                                <select class="form-control col-10" id="teacher_name_teacher" name="teacher_name"
                                        data-toggle="select" title="Simple select" data-live-search="true"
                                        data-live-search-placeholder="Search ...">
                                    <option value="0">请选择教师</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-1 p-0 input-group-append">
                                <button class="btn btn-primary add_teacher_btn" data-type="teacher" data-url="{{ route('manage.classroom.teacher.store', $classroom) }}" type="button">添加</button>
                            </div>
                        </div>
                        <p class="add_remark">教师负责教学, 必须有教师角色才能被设置。</p>
                    </div>
                </div>

                <div class="tab-pane fade" id="headmaster-setting" role="tabpanel"
                     aria-labelledby="home-tab">
                    <div class="operation_content">
                        <div class="addTeacherTop">
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">已添班主任</label>
                                <ul class="teacher_collect teacher_collect_head" data-sort-url="">
                                    @foreach($classroom->heads as $head)
                                        <li class="teacherItem">
                                            <div class="moveIcon">
                                                <i class="iconfont">&#xe64b;</i>
                                            </div>
                                            <div class="teacherImg">
                                                <img src="{{ render_cover($head->avatar, 'avatar') ?? '/imgs/avatar.png' }}" alt="">
                                            </div>
                                            <p class="teacherRole teacherRole_head">{{ $head->username }}</p>
                                            {{--<div class="teacherStatus custom-control custom-checkbox">--}}
                                                {{--<input type="checkbox" class="custom-control-input" id="customCheck1">--}}
                                                {{--<label class="custom-control-label" for="customCheck1">显示</label>--}}
                                            {{--</div>--}}
                                            <div class="teacherRemove">
                                                <i class="iconfont" data-type="head" data-url="{{ route('manage.classroom.teacher.delete', [$classroom, $head]) }}">&#xe6f2;</i>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="col-10 p-0 select_wrap">
                                <select class="form-control col-md-12 col-lg-9 col-xl-9" id="teacher_name_head" name="teacher_name"
                                        data-toggle="select" title="Simple select" data-live-search="true"
                                        data-live-search-placeholder="Search ...">
                                    <option value="0">请选择班主任</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-1 p-0 input-group-append">
                                <button class="btn btn-primary add_teacher_btn" data-type="head" data-url="{{ route('manage.classroom.teacher.store', $classroom) }}" type="button">添加</button>
                            </div>
                        </div>
                        <p class="add_remark">班主任负责管理整个班级，班主任只有一位，并且必须要由老师来担任。</p>
                    </div>
                </div>

                <div class="tab-pane fade" id="assistant-setting" role="tabpanel"
                     aria-labelledby="home-tab">
                    <div class="operation_content">
                        <div class="addTeacherTop">
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">设置助教</label>
                                <ul class="teacher_collect teacher_collect_assistant" data-sort-url="">
                                    @foreach($classroom->assistants as $assistant)
                                        <li class="teacherItem">
                                            <div class="moveIcon">
                                                <i class="iconfont">&#xe64b;</i>
                                            </div>
                                            <div class="teacherImg">
                                                <img src="{{ render_cover($assistant->avatar, 'avatar') ?? '/imgs/avatar.png' }}" alt="">
                                            </div>
                                            <p class="teacherRole teacherRole_assistant">{{ $assistant->username }}</p>
                                            {{--<div class="teacherStatus custom-control custom-checkbox">--}}
                                                {{--<input type="checkbox" class="custom-control-input" id="customCheck1">--}}
                                                {{--<label class="custom-control-label" for="customCheck1">显示</label>--}}
                                            {{--</div>--}}
                                            <div class="teacherRemove">
                                                <i class="iconfont" data-type="assistant" data-url="{{ route('manage.classroom.teacher.delete', [$classroom, $assistant]) }}">&#xe6f2;</i>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="col-10 p-0 select_wrap">
                                <select class="form-control col-md-12 col-lg-9 col-xl-9" id="teacher_name_assistant" name="teacher_name"
                                        data-toggle="select" title="Simple select" data-live-search="true"
                                        data-live-search-placeholder="Search ...">
                                    <option value="0">请选择助教</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-1 p-0 input-group-append">
                                <button class="btn btn-primary add_teacher_btn" data-type="assistant" data-url="{{ route('manage.classroom.teacher.store', $classroom) }}" type="button">添加</button>
                            </div>
                        </div>
                        <p class="add_remark">助教负责学员作业考试等, 必须有教师角色才能被设置。</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
@section('classroom_script')
    <script src="{{ '/vendor/jquery-ui/jquery-ui.min.js' }}"></script>
    <script src="/vendor/select2/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        $('.add_teacher_btn').click(function () {
            var type = $(this).data('type');
            // 声明空变量、空数组
            var str = '';
            var arr = new Array();

            // 获取教师名
            var teacher_name = $("#teacher_name_" + type + " option:selected").text();
            // 获取教师id
            var teacher_id = $("#teacher_name_" + type + " option:selected").val();

            if (teacher_name == '' || teacher_id == 0) {
                edu.alert('danger', '请选择教师!');
                return false
            }

            $(".teacherRole_" + type).each(function () {  //循环
                // 获取html
                var p_val = $(this).text();
                arr.push(p_val);
            });

            // 判断教师是否已存在
            if ($.inArray(teacher_name, arr) > -1) {
                edu.alert('danger', '教师已存在!');
                return false;
            }

            // 请求ajax, 进行教师设置
            $.ajax({
                url: $(this).data('url'),
                type: 'post',
                data: {user_id: teacher_id, type: type},
                success: function (res) {
                    if (res.status == 200) {
                        // 封装HTML
                        str += '<li class="teacherItem">';
                        str += '<div class="moveIcon">';
                        str += '<i class="iconfont">&#xe64b;</i>';
                        str += '</div>';
                        str += '<div class="teacherImg">';
                        str += '<img src="' + res.data.avatar + '" alt="">';
                        str += '</div>';
                        str += '<p class="teacherRole">' + teacher_name + '</p>';
                        str += '<div class="teacherRemove">';
                        str += '<i class="iconfont" data-url="' + res.data.url + '">&#xe6f2;</i>';
                        str += '</div>';
                        str += '</li>';

                        // 插入HTML
                        $('.teacher_collect_' + type).append(str);

                        // 清除选中状态
                        $('#select2-teacher_name-container').html('');

                        edu.alert('success', res.message);
                    } else {
                        edu.alert('danger', res.message);
                    }
                }
            });

        });

        $(document).on('click', '.teacherRemove i', function () {
            if (confirm("您确定要移除此教师吗?")) {
                var that = this;
                // 请求ajax, 进行教师删除
                $.ajax({
                    url: $(this).data('url'),
                    type: 'delete',
                    data: {type: $(this).data('type')},
                    success: function (res) {
                        if (res.status == 200) {
                            $(that).parent().parent().remove();
                            edu.alert('success', res.message);
                        } else {
                            edu.alert('danger', res.message);
                        }
                    }
                });

            }
            return false;
        });

        // 拖动排序
        $('.teacher_collect').sortable({
            update: function (event, ui) {
                console.log('排序待定');
                return false;
                var ids = [];
                $(event.target).children().each(function (k, v) {
                    $(v).find('.seq-num').html(k + 1);
                    ids.push($(this).data('id'))
                });
                var url = $(event.target).data('sortUrl');

                // 请求ajax, 进行教师排序
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {ids: ids},
                    success: function (res) {
                        if (res.status == 200) {
                            edu.alert('success', res.message);
                        } else {
                            edu.alert('danger', res.message);
                        }
                    }
                });

            }
        });

    </script>
@endsection