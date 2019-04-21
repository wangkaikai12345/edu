@extends('teacher.classroom.classroom_layout')
@section('classroom_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/student/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/classroom/course/index.css') }}">
@endsection
@section('classroom_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <!-- Attach a new card -->
        <form class="form-default">
            <div class="card teacher_style">
                <div class="card-body row_content">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-lg-12 row mt-0">
                                <div class="col-lg-8 col-md-5 col-sm-4">
                                    <h6>课程管理</h6>
                                </div>
                                <div class="col-lg-4 p-0 pt-2">
                                    <button id="add-course-btn" type="button" class="btn btn-primary add-item"
                                            data-toggle="modal"
                                            data-target=".add-course-modal-lg"
                                            style="margin-left:210px;width:96px !important;"
                                            data-url="{{ route('manage.classroom.course.create', $classroom) }}">+ 添加课程
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="course_hr">
                    <div class="bd-example">
                        <div class="row">
                            <input type="hidden" id="sort-url"
                                   value="{{ route('manage.classroom.course.sort', $classroom) }}">
                            <ul class="col-xl-12 p-0 mr-0 ml-0 course_content">
                                @foreach($classroomCourses as $classroomCourse)
                                    <li class="col-xl-12 p-0 course_item" data-id="{{ $classroomCourse->id }}">
                                        <div class="drag_icon">
                                            <i class="iconfont">&#xe64b;</i>
                                        </div>
                                        <div class="course_img">
                                            <img src="{{ render_cover($classroomCourse->plan->cover, 'course') }}" alt="">
                                        </div>
                                        <div class="course_user_name">
                                            <div class="course_name">
                                                版本名称（{{ $classroomCourse->plan->title }}）{{ $classroomCourse->seq }}
                                            </div>
                                            <div class="course_teacher">
                                                <img src="/imgs/avatar.png" alt="">
                                                <div class="teacher_name">
                                                    {{ $classroomCourse->plan->user->username }}
                                                </div>
                                                <div class="teacher_course_name">
                                                    所属课程: {{ $classroomCourse->course->title }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="course_operation">
                                            <div class="course_operation_content">
                                                <div class="course_price text-danger">
                                                    @if(empty($classroomCourse->plan->price))
                                                        免费
                                                    @else
                                                        {{ $classroomCourse->plan->price }} 元
                                                    @endif
                                                </div>
                                                @if ($classroomCourse->is_synced)
                                                    <a href="javascript:;" class="course_price text-warning cancel-sync" data-url="{{ route('manage.classroom.course.sync', $classroomCourse) }}">
                                                        取消同步
                                                    </a>
                                                @else
                                                    <div class="course_price text-success">
                                                        不再同步
                                                    </div>
                                                @endif
                                                <a href="javascript:;" style="color: #aaa" class="course_delete"
                                                   data-url="{{ route('manage.classroom.course.delete', $classroomCourse) }}">
                                                    <i class="iconfont">&#xe6f2;</i>
                                                </a>

                                            </div>
                                        </div>
                                        <div class="course_desc">
                                            <div class="course_item_desc">
                                                <span>{{ $classroomCourse->plan->members->count() }}</span> 学员
                                            </div>
                                            <div class="course_item_desc">
                                                <span>{{ $classroomCourse->plan->chapters->where('parent_id', 0)->count() }}</span> 阶段
                                            </div>
                                            <div class="course_item_desc">
                                                有效期：<span>永久</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="col-xl-12 p-0 ml-0 course_message">
                                <div class="message_content">
                                    若班级课程被移除，则该课程内所有学习数据将被清空。
                                </div>
                            </div>
                            <div class="col-xl-12 p-0 ml-0 course_price_content">
                                <div class="price_content float-right">共计：<span>0</span>元</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade add-course-modal-lg" id="add-course-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="add-course-modal-content">

            </div>
        </div>
    </div>
@endsection
@section('classroom_script')
    <script src="{{ '/vendor/jquery-ui/jquery-ui.min.js' }}"></script>
    <script>
        $(function () {
            $(document).on('click', '.course_delete', function () {
                if (confirm("您确定要移除此课程吗?")) {
                    // 执行删除
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'delete',
                        success: function (res) {
                            if (res.status == 200) {
                                edu.alert('success', res.message);
                                window.location.reload();
//                                $(that).parent().parent().parent().remove();
                            } else {
                                edu.alert('danger', res.message);
                            }
                        }

                    });
                }
                return false;
            });

            /**
             * 取消课程同步
             */
            $(document).on('click', '.cancel-sync', function () {
                if (confirm("您确定要取消课程同步吗?")) {
                    // 执行删除
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'patch',
                        success: function (res) {
                            if (res.status == 200) {
                                edu.alert('success', res.message);
                                window.location.reload();
//                                $(that).parent().parent().parent().remove();
                            } else {
                                edu.alert('danger', res.message);
                            }
                        }

                    });
                }
                return false;
            });



            /**
             * 拖动排序
             */
            $('.course_content').sortable({
                update: function (event, ui) {
                    var ids = [];
                    $(event.target).children().each(function (k, v) {
                        ids.push($(this).data('id'))
                    });
                    var url = $('#sort-url').val();
                    console.log(ids);
                    $.ajax({
                        url: url,
                        data: {ids: ids},
                        type: 'patch',
                        success: function (res) {
                            if (res.status) {
                                edu.alert('success', '排序成功');
                                window.location.reload();
                            } else {
                                edu.alert('danger', res.message);
                            }

                        }
                    });

                    console.log(ids);
                }
            });

            /**
             * 弹出选择题目的模态框
             */
            $('#add-course-btn').on({
                click: function (e) {
                    var url = $(this).data('url');

                    getQuestions(url);

                    $('#add-course-modal').modal('toggle');
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
            $(document).on('click', '#search-course-btn', function () {
                var title = $('#search-course-title').val();
                getQuestions($('#add-course-btn').data('url'), {title: title});
            });

            /**
             * 为全部课程绑定事件
             */
            $(document).on('click', '#all-course-btn', function () {
                getQuestions($('#add-course-btn').data('url'));
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
                        $('#add-course-modal-content').html(res);
                        edu.alert('success', '获取版本列表成功!');
                    }
                });
            }
        });
    </script>
@endsection