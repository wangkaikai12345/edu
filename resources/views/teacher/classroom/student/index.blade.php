@extends('teacher.classroom.classroom_layout')
@section('classroom_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/student/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/topic_list_layout.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/classroom/student/index.css') }}">
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
                                    <h6>学员管理</h6>
                                </div>
                                <div class="col-lg-4 p-0 pt-1">
                                    <button type="button" class="btn btn-primary add-plan add-item" data-toggle="modal"
                                            data-target=".add-student-modal-lg"
                                            style="margin-left:210px;width:96px !important;">+ 添加学员
                                    </button>
                                </div>
                                {{--<button type="button" class="btn btn-primary add-plan add-item">导出学员--}}
                                {{--</button>--}}
                            </div>
                        </div>
                    </div>
                    <hr class="course_hr">
                    <div class="bd-example">
                        {{--<ul class="nav nav-tabs" id="myTab" role="tablist">--}}
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
                        {{--</ul>--}}
                        <div class="row">
                            <div class="col-lg-5 p-0 row second mr-0">
                                <div class="col-lg-12 p-0 m-0 margin-content">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <input type="text" name="title" id="title" class="form-control col-lg-11"
                                                   placeholder="请输入用户名/邮箱/手机号" value="{{ request()->title }}">
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
                                                            @if($classroom->expiry_mode == 'forever')
                                                                有效期至：永久有效
                                                            @elseif($classroom->expiry_mode == 'valid')
                                                                有效期至：{{ $member->created_at->addDays($classroom->expiry_days) }}
                                                                ({{ $classroom->expiry_days - $member->created_at->diffInDays(\Carbon\Carbon::now()) < 0 ? '已过期' : $classroom->expiry_days - $member->created_at->diffInDays(\Carbon\Carbon::now()) . '天' }}
                                                                )
                                                            @else
                                                                有效期至：{{ $classroom->expiry_ended_at }}
                                                                ({{$classroom->expiry_ended_at->diffInDays(\Carbon\Carbon::now()) < 0 ? '已过期' : $classroom->expiry_ended_at->diffInDays(\Carbon\Carbon::now()) . '天'}}
                                                                )
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="line-height:60px;">
                                                    {{ $member->learned_count ? (ytof($member->learned_count / $classroom->plan->tasks_count)) : 0 }}
                                                    %
                                                </td>
                                                <td style="line-height:50px;">
                                                    <ul class="nav nav-pills">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" href="javascript:;"
                                                               data-toggle="modal" data-target="#modal"
                                                               data-url="{{ route('manage.classroom.member.message', $member) }}">发私信</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="javascript:;" data-toggle="modal"
                                                               data-target="#modal"
                                                               data-url="{{ route('manage.classroom.member.userinfo', $member) }}">查看资料</a>
                                                        </li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown"
                                                               href="#"
                                                               role="button" aria-haspopup="true"
                                                               aria-expanded="false">更多</a>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="javascript:;"
                                                                   data-toggle="modal" data-target="#modal"
                                                                   data-url="{{ route('manage.classroom.member.remark', $member) }}">备注</a>
                                                                {{--<a class="dropdown-item" href="#">关注</a>--}}
                                                                <a class="dropdown-item" href="#">增加有效期</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item delete-member"
                                                                   href="javascript:;"
                                                                   data-url="{{ route('manage.classroom.member.destroy', $member) }}">移除</a>
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
                            {{--<div class="tab-pane fade" id="accession-record" role="tabpanel" aria-labelledby="profile-tab">--}}
                            {{--<div class="table_content">--}}
                            {{--<table class="table table-hover">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                            {{--<th scope="col" width="250">学员名称</th>--}}
                            {{--<th>加入日期</th>--}}
                            {{--<th>加入类型</th>--}}
                            {{--<th>加入原因</th>--}}
                            {{--<th>是否付费</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                            {{--<td>--}}
                            {{--<div class="user_avatar teacher_avatar mx-auto" data-toggle="popover"--}}
                            {{--data-content='<div class="popover_card">--}}
                            {{--<div class="teacher_header">--}}
                            {{--<img src="/imgs/avatar.png" alt="" class="teacher_avatar">--}}
                            {{--<div class="teacher_info">--}}
                            {{--<span class="teacher_name">小猪猪</span>--}}
                            {{--<span class="teacher_job">特邀讲师</span>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="teacher_fans">--}}
                            {{--<div class="fans_item">--}}
                            {{--<span>12</span>--}}
                            {{--<span class="fans_title">--}}
                            {{--在教--}}
                            {{--</span>--}}
                            {{--</div>--}}
                            {{--<div class="fans_item">--}}
                            {{--<span>232</span>--}}
                            {{--<span class="fans_title">--}}
                            {{--关注--}}
                            {{--</span>--}}
                            {{--</div>--}}
                            {{--<div class="fans_item mr-0">--}}
                            {{--<span>123</span>--}}
                            {{--<span class="fans_title">--}}
                            {{--粉丝--}}
                            {{--</span>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="teacher_controls">--}}
                            {{--<a href="#" class="btn btn-primary btn-sm btn-circle float-left follow">--}}
                            {{--关注--}}
                            {{--</a>--}}
                            {{--<a href="#" class="btn btn-primary btn-sm btn-circle float-right message">私信</a>--}}
                            {{--</div>--}}
                            {{--</div>' data-html="true" data-trigger="hover click"--}}
                            {{--data-placement="top">--}}
                            {{--<img src="/imgs/avatar.png" alt="">--}}
                            {{--</div>--}}
                            {{--<div class="user_name">--}}
                            {{--学员名字学员名字学员名字学员名字学员名字学员名字学员名字--}}
                            {{--</div>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--2019-02-27--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--这是啥--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--这个我也不知道--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--是--}}
                            {{--</td>--}}
                            {{--</tr>--}}
                            {{-- tr暂无数据 --}}
                            {{--<tr class="empty">--}}
                            {{--<td colspan="20">无学员记录...</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                            {{--</table>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{-- 退出记录 --}}
                            {{--<div class="tab-pane fade" id="exit-record" role="tabpanel" aria-labelledby="contact-tab">--}}
                            {{--<div class="table_content">--}}
                            {{--<table class="table table-hover">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                            {{--<th scope="col" width="170">学员名称</th>--}}
                            {{--<th>退出日期</th>--}}
                            {{--<th>退出类型</th>--}}
                            {{--<th>退出原因</th>--}}
                            {{--<th>是否退款</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                            {{--<td>--}}
                            {{--学员名称--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--2019-02-27--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--这是啥--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--这个我也不知道--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--是--}}
                            {{--</td>--}}
                            {{--</tr>--}}
                            {{-- tr暂无数据 --}}
                            {{--<tr class="empty">--}}
                            {{--<td colspan="20">无学员记录...</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                            {{--</table>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
                <nav class="pageNumber" aria-label="Page navigation example" style="margin:0 auto;">
                    {{ $members->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </nav>
            </div>
        </form>
    </div>

    {{-- 添加学员模态框 --}}
    <div class="modal fade add-student-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" id="classroom-member-form"
                      action="{{ route('manage.classroom.member.store', $classroom) }}">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">添加学员</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-4 m-0 ml-8 input-content justify-content-center">
                            <div class="col-9 mb-3">
                                <div class="form-group">
                                    <div class="input-group input-group-transparent">
                                        <label class="control-label col-md-2 col-lg-2 text-right p-0">学员</label>
                                        <input type="text" name="title"
                                               class="form-control col-md-9 col-lg-9" placeholder="姓名/邮箱/手机号">
                                    </div>
                                    <div class="input_topic">
                                        只能添加系统中已注册的用户
                                    </div>
                                </div>
                            </div>
                            <div class="col-9 mb-3">
                                <div class="form-group">
                                    <div class="input-group input-group-transparent">
                                        <label class="control-label col-md-2 col-lg-2 text-right p-0">购买价格</label>
                                        <input type="text" name="price"
                                               class="form-control col-md-5 col-lg-5" value="0">
                                        元
                                    </div>
                                    <div class="input_topic">
                                        本班级的价格为<span>{{ $classroom->price }}</span>元
                                    </div>
                                </div>
                            </div>
                            <div class="col-9 mb-3">
                                <div class="form-group">
                                    <div class="input-group input-group-transparent" style="line-height: 30px;">
                                        <label class="control-label col-md-2 col-lg-2 text-right p-0">学员类型</label>
                                        <div class="view_r_radio custom-control custom-radio mr-4" style="padding-top: 8px">
                                            <input type="radio" name="type" checked class="custom-control-input"
                                                   id="type1" value="{{ \App\Enums\StudentType::AUDITION }}">
                                            <label class="custom-control-label" for="type1">试听学员</label>
                                        </div>
                                        <div class="view_r_radio custom-control custom-radio mr-4" style="padding-top: 8px">
                                            <input type="radio" name="type" class="custom-control-input"
                                                   id="type2" value="{{ \App\Enums\StudentType::OFFICIAL }}">
                                            <label class="custom-control-label" for="type2">正式学员</label>
                                        </div>
                                        <div class="view_r_radio custom-control custom-radio mr-4" style="padding-top: 8px">
                                            <input type="radio" name="type" class="custom-control-input"
                                                   id="type3" value="{{ \App\Enums\StudentType::INSIDE }}">
                                            <label class="custom-control-label" for="type3">内部学员</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9 mb-3">
                                <div class="form-group">
                                    <div class="input-group input-group-transparent">
                                        <label class="control-label col-md-2 col-lg-2 text-right p-0">备注</label>
                                        <input type="text" name="remark"
                                               class="form-control col-md-9 col-lg-9" value="">
                                    </div>
                                    <div class="input_topic">
                                        选填
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary primary-btn" id="plan-add-btn">创建</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            /**
             * 添加加用户的表单验证
             */
                // 指定表单
            var form = $('#classroom-member-form');

            FormValidator.init(form, {
                rules: {
                    title: {
                        required: true,
                        maxlength: 20
                    },
                    price: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required: "标题不能为空！",
                        maxlength: '标题长度不能超过20'
                    },
                    price: {
                        required: "价格不能为空！",
                    },

                },
            }, function () {
                return true;
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
        });
    </script>
@endsection