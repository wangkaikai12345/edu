@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <style>
        .table a {
            text-decoration: none;
        }

        .required {
            color: red;
        }

        td {
            line-height: 36px
        }

        .panel > .table-bordered, .panel > .table-responsive > .table-bordered {
            border: 1px solid #e4eaec;
        !important;
        }
    </style>
@stop
@section('page-title', '用户管理')
@section('content')


    {{--小的modal--}}
    <div class="modal fade" id="mySimpleModal" aria-labelledby="mySimpleModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <form class="modal-content">

            </form>
        </div>
    </div>

    {{--大的modal--}}
    <div class="modal fade" id="myLgModal" aria-labelledby="myLgModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <form class="modal-content">

            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body container-fluid" style="padding-bottom: 0px">
            <div class="row row-lg">
                <div class="col-xl-12">
                    <!-- Example Tabs -->
                    <div class="example-wrap">
                        <div class="nav-tabs-horizontal" data-plugin="tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link " href="{{ route('backstage.courses.index') }}">课程管理
                                    </a>
                                </li>
                                <li class="nav-item  active show" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.courses.recommend.index') }}">课程推荐
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <header class="panel-heading">
                                                <h3 class="panel-title">课程搜索:</h3>
                                            </header>
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <form action="{{ route('backstage.courses.recommend.index') }}"
                                                      method="GET">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="title"
                                                                   placeholder="课程标题"
                                                                   autocomplete="off" value="{{ request('title') }}">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="category:name"
                                                                   placeholder="创建人"
                                                                   autocomplete="off"
                                                                   value="{{ request('category:name') }}">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="roles:name">
                                                                <option value="0">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            课程状态
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                @foreach($status as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                            @if(request('status') == $key) selected @endif>
                                                                        <font style="vertical-align: inherit;">
                                                                            <font style="vertical-align: inherit;">
                                                                                {{ $value }}
                                                                            </font>
                                                                        </font>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <button type="submit" class="btn btn-primary"><font
                                                                    style="vertical-align: inherit;">
                                                                <font style="vertical-align: inherit;">搜索
                                                                </font></font></button>
                                                    </div>
                                                </form>
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
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block"></h3>
                    {{--<a href="{{ route('manage.users.teach_course') }}" class="btn btn-sm btn-outline btn-primary"--}}
                            {{--style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px">--}}
                        {{--<i class="icon wb-plus" aria-hidden="true"></i> 添加课程--}}
                    {{--</a>--}}
                </header>
                <div class="panel-body">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false">
                        <thead>
                        <tr>
                            <th>标题</th>
                            <th>价格</th>
                            <th>版本数</th>
                            <th>课程状态</th>
                            <th>学员数</th>
                            <th>创建者</th>
                            <th>连载状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($courses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->default_plan->price / 100 }}元</td>
                                <td>{{ $course->plans_count }}</td>
                                <td>{{ $status[$course->status] }}</td>
                                <td>{{ $course->students_count }}</td>
                                <td>{{ $course->user->username }}</td>
                                <td>{{ $course->serialize_mode == 'none' ? '非连载' : '连载中' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('manage.courses.edit', ['course' => $course->hashId]) }}" class="btn btn-default btn-outline">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                    查看课程
                                                </font>
                                            </font>
                                        </a>
                                        <a class="btn btn-default dropdown-toggle btn-outline"
                                           id="exampleSplitDropdown1" data-toggle="dropdown"
                                           aria-expanded="false"></a>
                                        <div class="dropdown-menu" aria-labelledby="exampleSplitDropdown1" role="menu"
                                             x-placement="bottom-start"
                                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(57px, 39px, 0px);">
                                            <a class="dropdown-item"
                                               href="javaScript:"
                                               role="menuitem"
                                               onclick="unRecommend('{{ route('backstage.courses.recommend', ['course' => $course->hashId]) }}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        取消推荐
                                                    </font>
                                                </font>
                                            </a>

                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               onclick="publishedCourse('{{ route('backstage.courses.publish', ['course' => $course->hashId]) }}','{{ $course->hashId }}', 'published')"
                                               @if($course->status == 'published') style="display: none"
                                               @endif id="public_{{$course->hashId}}">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        发布课程
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               onclick="publishedCourse('{{ route('backstage.courses.publish', ['course' => $course->hashId]) }}', '{{ $course->hashId }}','closed')"
                                               @if($course->status == 'closed' || $course->status == 'draft') style="display: none"
                                               @endif id="close_{{$course->hashId}}">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        取消发布
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               onclick="deleteCourse('{{ route('backstage.courses.destroy', ['course' => $course->hashId ])}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        删除课程
                                                    </font>
                                                </font>
                                            </a>
                                            {{--<a class="dropdown-item" href="javascript:void(0)" role="menuitem"--}}
                                            {{--data-target="#myLgModal" data-toggle="modal"--}}
                                            {{--onclick="showLgModal('{{ route('backstage.users.edit', ['user' => $user->hashId ])}}')">--}}
                                            {{--<font style="vertical-align: inherit;">--}}
                                            {{--<font style="vertical-align: inherit;">--}}
                                            {{--编辑用户--}}
                                            {{--</font>--}}
                                            {{--</font>--}}
                                            {{--</a>--}}
                                            {{--@if(auth('web')->user()->hasRole(App\Enums\UserType::SUPER_ADMIN))--}}
                                            {{--<a class="dropdown-item" href="javascript:void(0)" role="menuitem"--}}
                                            {{--data-target="#mySimpleModal" data-toggle="modal"--}}
                                            {{--onclick="showSimpleModal('{{ route('backstage.users.roles.show', ['user' => $user->hashId ])}}')"--}}
                                            {{-->--}}
                                            {{--<font style="vertical-align: inherit;">--}}
                                            {{--<font style="vertical-align: inherit;">--}}
                                            {{--编辑用户组--}}
                                            {{--</font>--}}
                                            {{--</font>--}}
                                            {{--</a>--}}
                                            {{--@endif--}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-body">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        // 展示modal
        function showSimpleModal(url) {
            $("#mySimpleModal .modal-content").load(url);
        }

        // 展示modal
        function showLgModal(url) {
            $("#myLgModal .modal-content").load(url);
        }

        $("body").on("hidden.bs.modal", function () {
            // 这个#showModal是模态框的id
            $(this).removeData("bs.modal");
            $(this).find(".modal-content").children().remove();
        });

        // 用户状态操作
        function publishedCourse(fetchUrl, hashId, status) {
            // 提示信息
            let message = null;
            let type = 'block';
            // 类型转换
            if (status === 'published') {
                message = '确认发布课程?'
            } else {
                message = '确认取消发布?'
            }

            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm(message, function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}", status: status},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});

                            window.location.reload();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }

        // 用户状态操作
        function unRecommend(fetchUrl) {

            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定取消推荐?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'PATCH',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}", is_recommended: 0},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});

                            window.location.reload();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }

        // 用户状态操作
        function deleteCourse(fetchUrl) {

            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定删除课程？", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});

                            window.location.reload();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }
    </script>
@stop








