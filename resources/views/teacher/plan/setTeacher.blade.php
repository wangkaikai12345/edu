@extends('teacher.plan.plan_layout')
@section('plan_style')
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ mix('/css/theme.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/plan/setTeacher.css') }}">
    <style>
        body {
            min-height: 100.001% !important;
            overflow-y: scroll;
        }
    </style>
@stop
@section('plan_content')
    <div class="czh_task_content col-xl-9 col-md-9 col-12">
        <div class="operation_header">
            <p>教师设置</p>
        </div>
        <div class="operation_content">
            <div class="addTeacherTop">
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">已添加教师</label>
                    <ul class="teacher_collect" data-sort-url="{{ route('manage.plans.teacher.sort') }}">
                        @foreach($plan->teachers->sortBy('seq') as $teacher)
                            <li class="teacherItem" data-id="{{ $teacher->id }}">
                                <div class="moveIcon">
                                    <i class="iconfont">&#xe64b;</i>
                                </div>
                                <div class="teacherImg">
                                    <img src="{{ render_cover($teacher->user->avatar, 'avatar') ?? '/imgs/avatar.png' }}"
                                         alt="">
                                </div>
                                <p class="teacherRole">{{ $teacher->user->username }}</p>
                                <div class="teacherStatus custom-control custom-checkbox">
                                    @if($teacher->is_show == 1)
                                    <input data-url="{{ route('manage.plans.teachers.show', $teacher) }}" type="checkbox" class="custom-control-input teacher_is_show" checked id="is_show{{$teacher->id}}">
                                    <label class="custom-control-label" for="is_show{{$teacher->id}}">显示</label>
                                    @else
                                    <input data-url="{{ route('manage.plans.teachers.show', $teacher) }}" type="checkbox" class="custom-control-input teacher_is_show" id="is_show{{$teacher->id}}">
                                    <label class="custom-control-label" for="is_show{{$teacher->id}}">不显示</label>
                                    @endif
                                </div>
                                @if ($plan->user_id != $teacher->user_id)
                                <div class="teacherRemove">
                                    <i class="iconfont"
                                       data-url="{{ route('manage.plans.teachers.delete', $teacher) }}">&#xe6f2;</i>
                                </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="input-group mb-3">
                {{--<input id="add_teacher_inp" type="text" class="form-control" aria-describedby="basic-addon2">--}}
                {{--<div class="input-group-append">--}}
                {{--<button id="add_teacher_btn"  class="btn btn-primary" type="button">添加</button>--}}
                <div class="col-10 p-0 select_wrap">
                    <select class="form-control col-md-12 col-lg-9 col-xl-9" id="teacher_name" name="teacher_name"
                            data-toggle="select" title="Simple select" data-live-search="true"
                            data-live-search-placeholder="Search ...">
                        <option value="0">请选择教师</option>
                      @foreach($teachers as $teacher)
                          <option value="{{ $teacher->id }}">{{ $teacher->username }}</option>
                      @endforeach
                    </select>
                </div>
                {{--<input id="add_teacher_inp" type="text" class="form-control" aria-describedby="basic-addon2">--}}
                <div class="col-1 p-0 input-group-append">
                    <button id="add_teacher_btn" data-url="{{ route('manage.plans.store_teachers', [$course, $plan]) }}" class="btn btn-primary" type="button">添加</button>
                </div>
            </div>
            <p class="add_remark">只能添加有教师权限的用户</p>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ '/vendor/jquery-ui/jquery-ui.min.js' }}"></script>
    <script src="/vendor/select2/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $('#add_teacher_btn').click(function () {
            // 声明空变量、空数组
            var str = '';
            var arr = new Array();

            // 获取教师名
            var teacher_name = $("#teacher_name option:selected").text();
            // 获取教师id
            var teacher_id = $("#teacher_name option:selected").val();

            if (teacher_name == '' || teacher_id == 0) {
                edu.alert('danger', '请选择教师!');
                return false
            }

            $(".teacherRole").each(function () {  //循环
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
                data: {user_id: teacher_id},
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
                        $('.teacher_collect').append(str);

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

        /**
         * 是否显示老师
         */
        $('.teacher_is_show').change(function () {
            if (!confirm('是否执行?')) {
                return false;
            }

            $.ajax({
                url: $(this).data('url'),
                type: 'patch',
                success: function (res) {
                    if (res.status == 200) {
                        edu.alert('success', res.message);
                        window.location.reload();
                    } else {
                        edu.alert('danger', res.message);
                    }
                }
            });
        })
    </script>
@endsection
