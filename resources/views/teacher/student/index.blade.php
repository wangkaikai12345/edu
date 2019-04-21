@extends('teacher.plan.plan_layout')
@section('plan_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/student/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/topic_list_layout.css') }}">
@endsection
@section('plan_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <!-- Attach a new card -->
        <form class="form-default">
            <div class="card teacher_style">
                <div class="card-body row_content">
                    <div class="row_div">
                        <div class="row pr-4">
                            <div class="col-lg-8">
                                <h6>学员管理</h6>
                            </div>
                            <div class="col-lg-4 text-lg-right pt-2 pr-4">
                                <button type="button" class="btn btn-primary add-plan add-item" data-toggle="modal"
                                        data-target=".add-student-modal-lg">+ 添加学员
                                </button>
                            </div>
                            {{--<button type="button" class="btn btn-primary add-plan add-item">导出学员--}}
                            {{--</button>--}}
                        </div>
                    </div>
                    <hr class="course_hr student_course_hr">
                    <div class="bd-example">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            {{--<li class="nav-item">--}}
                            {{--<a class="nav-link active" id="home-tab" data-toggle="tab" href="#formal-student" role="tab"--}}
                            {{--aria-controls="home" aria-selected="true">正式学员</a>--}}
                            {{--</li>--}}
                            {{--<li class="nav-item">--}}
                            {{--<a class="nav-link" id="profile-tab" data-toggle="tab" href="#accession-record" role="tab"--}}
                            {{--aria-controls="profile" aria-selected="false">加入记录</a>--}}
                            {{--</li>--}}
                            {{--<li class="nav-item">--}}
                            {{--<a class="nav-link" id="contact-tab" data-toggle="tab" href="#exit-record" role="tab"--}}
                            {{--aria-controls="contact" aria-selected="false">退出记录</a>--}}
                            {{--</li>--}}
                        </ul>
                        <div class="row" style="padding-top:15px;padding-bottom:15px;">
                            <div class="col-lg-5 p-0 row second mr-0 ml-4">
                                <div class="col-lg-12 p-0 m-0 margin-content">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <input type="text" name="keyword" id="title" class="form-control col-lg-11"
                                                   placeholder="请输入用户名/邮箱/手机号" value="{{ request()->keyword }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-5 col-sm-3 search_content">
                                <button type="submit" class="btn btn-primary search float-left">搜索</button>
                            </div>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            {{-- 正式学员 --}}
                            <div class="tab-pane fade show active" id="formal-student" role="tabpanel"
                                 aria-labelledby="home-tab">
                                <div class="table_content">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col" width="400">学员</th>
                                            <th scope="col" width="250">正式学员</th>
                                            <th scope="col">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($members as $member)
                                            <tr>
                                                <td>
                                                    <div class="user_avatar teacher_avatar mx-auto">
                                                        <img src="/imgs/avatar.png" alt="">
                                                    </div>

                                                    <div class="user_content">
                                                        <div class="user_name">
                                                            {{ $member->user->username }}
                                                        </div>
                                                        <div class="join_time">
                                                            加入时间：<span>{{ $member->created_at }}</span>
                                                        </div>

                                                        <div class="validity_time">
                                                            有效期至：2019-7-04 00:00 (111天)
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="line-height:60px;">
                                                    {{ $member->learned_count ? (ytof($member->learned_count / $plan->tasks_count)) : 0 }}
                                                    %
                                                </td>
                                                <td style="line-height:50px;">
                                                    <ul class="nav nav-pills">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" href="javascript:;"
                                                               data-toggle="modal" data-target="#modal"
                                                               data-url="{{ route('manage.plans.member.message', $member) }}">发私信</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="javascript:;" data-toggle="modal"
                                                               data-target="#modal"
                                                               data-url="{{ route('manage.plans.member.userinfo', $member) }}">查看资料</a>
                                                        </li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown"
                                                               href="#"
                                                               role="button" aria-haspopup="true"
                                                               aria-expanded="false">更多</a>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="javascript:;"
                                                                   data-toggle="modal" data-target="#modal"
                                                                   data-url="{{ route('manage.plans.member.remark', $member) }}">备注</a>
                                                                {{--<a class="dropdown-item" href="#">关注</a>--}}
                                                                {{--<a class="dropdown-item" href="#">增加有效期</a>--}}
                                                                {{--<div class="dropdown-divider"></div>--}}
                                                                <a class="dropdown-item delete-member" href="javascript:;"
                                                                   data-url="{{ route('manage.plans.member.destroy', $member) }}">移除</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            {{-- tr暂无数据 --}}
                                            <tr class="empty">
                                                <td colspan="20">暂无学员记录...</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- 加入记录 --}}
                            <div class="tab-pane fade" id="accession-record" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="table_content">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col" width="250">学员名称</th>
                                            <th>加入日期</th>
                                            <th>加入类型</th>
                                            <th>加入原因</th>
                                            <th>是否付费</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="user_avatar teacher_avatar mx-auto">
                                                    <img src="/imgs/avatar.png" alt="">
                                                </div>
                                                <div class="user_name">
                                                    学员名字学员名字学员名字学员名字学员名字学员名字学员名字
                                                </div>
                                            </td>
                                            <td>
                                                2019-02-27
                                            </td>
                                            <td>
                                                这是啥
                                            </td>
                                            <td>
                                                这个我也不知道
                                            </td>
                                            <td>
                                                是
                                            </td>
                                        </tr>
                                        {{-- tr暂无数据 --}}
                                        {{--<tr class="empty">--}}
                                        {{--<td colspan="20">无学员记录...</td>--}}
                                        {{--</tr>--}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- 退出记录 --}}
                            <div class="tab-pane fade" id="exit-record" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="table_content">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col" width="170">学员名称</th>
                                            <th>退出日期</th>
                                            <th>退出类型</th>
                                            <th>退出原因</th>
                                            <th>是否退款</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="user_avatar teacher_avatar mx-auto">
                                                    <img src="/imgs/avatar.png" alt="">
                                                </div>
                                                学员名字学员名字学员
                                            </td>
                                            <td>
                                                2019-02-27
                                            </td>
                                            <td>
                                                这是啥
                                            </td>
                                            <td>
                                                这个我也不知道
                                            </td>
                                            <td>
                                                是
                                            </td>
                                        </tr>
                                        {{-- tr暂无数据 --}}
                                        {{--<tr class="empty">--}}
                                        {{--<td colspan="20">无学员记录...</td>--}}
                                        {{--</tr>--}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav class="pageNumber" aria-label="Page navigation example" style="margin:0 auto;">
                        {{ $members->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </form>
    </div>

    {{-- 添加学员模态框 --}}
    <div class="modal fade add-student-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">添加学员</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('manage.plans.member.store', [$course, $plan]) }}"
                          id="plan-add-form">
                        <div class="row mt-4 m-0 ml-8 input-content justify-content-center">
                            <div class="col-9 mb-3">
                                <div class="form-group">
                                    <div class="input-group input-group-transparent">
                                        <label class="control-label col-md-2 col-lg-2 text-right p-0">学员</label>
                                        <input type="text" name="keyword"
                                               class="form-control col-md-9 col-lg-9" placeholder="手机/邮箱/用户名">
                                    </div>
                                    <div class="input_topic">
                                        只能添加系统中已注册的用户
                                    </div>
                                </div>
                            </div>
                            {{--<div class="col-9 mb-3">--}}
                            {{--<div class="form-group">--}}
                            {{--<div class="input-group input-group-transparent">--}}
                            {{--<label class="control-label col-md-2 col-lg-2 text-right p-0">购买价格</label>--}}
                            {{--<input type="text" name="price"--}}
                            {{--class="form-control col-md-5 col-lg-5" value="0">--}}
                            {{--元--}}
                            {{--</div>--}}
                            {{--<div class="input_topic">--}}
                            {{--本课程的价格为<span>0.00</span>元--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-9 mb-3">--}}
                            {{--<div class="form-group">--}}
                            {{--<div class="input-group input-group-transparent">--}}
                            {{--<label class="control-label col-md-2 col-lg-2 text-right p-0">备注</label>--}}
                            {{--<input type="text" name="remark"--}}
                            {{--class="form-control col-md-9 col-lg-9" value="">--}}
                            {{--</div>--}}
                            {{--<div class="input_topic">--}}
                            {{--选填--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary primary-btn" id="plan-add-btn">创建</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            /**
             * 添加学员
             */
            var form = $('#plan-add-form');
            FormValidator.init(form, {
                rules: {
                    keyword: {
                        required: true,
                        maxlength: 20
                    },
                },
                messages: {
                    keyword: {
                        required: "关键字不能为空！",
                        maxlength: '关键字长度不能超过20'
                    },
                },
            }, function () {
                // 请求ajax, 进行教师排序
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function (res) {
                        if (res.status == 200) {
                            edu.alert('success', res.message);
                            window.location.reload();
                        } else {
                            edu.alert('danger', res.message);
                        }
                    }
                });

                return false;
            });

            /**
             * 删除成员
             */
            $(document).on('click', '.delete-member', function () {
                var url = $(this).data('url');
                edu.confirm({
                    type: 'danger',
                    dataType: 'html',
                    message: '<img src>',
                    title: '确定移除这个成员吗',
                    callback: function (props) {
                        if (props.type === 'success') {
                            $.ajax({
                                url: url,
                                type: 'delete',
                                success: function (res) {
                                    if (res.status == 200) {
                                        edu.alert('success', res.message);
                                        window.location.reload();
                                    } else {
                                        edu.alert('danger', res.message);
                                    }
                                }
                            });
                        }
                    }
                });
            })

        };
    </script>
@endsection