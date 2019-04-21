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
        .panel>.table-bordered, .panel>.table-responsive>.table-bordered {
            border:1px solid #e4eaec;!important;
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
                                    <a class="nav-link  active show" href="{{ route('backstage.users.index') }}">用户管理
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.active.users') }}">在线用户
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.users.login_log') }}">登录日志</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <header class="panel-heading">
                                                <h3 class="panel-title">用户搜索:</h3>
                                            </header>
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <form action="{{ route('backstage.users.index') }}" method="GET">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="username"
                                                                   placeholder="用户名"
                                                                   autocomplete="off" value="{{ request('username') }}">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="phone"
                                                                   placeholder="手机号"
                                                                   autocomplete="off" value="{{ request('phone') }}">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="email"
                                                                   placeholder="邮箱"
                                                                   autocomplete="off" value="{{ request('email') }}">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="roles:name">
                                                                <option value="0">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            用户角色
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                @foreach($roles as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                            @if(request('roles:name') == $key) selected @endif>
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
                    <button type="button" class="btn btn-sm btn-outline btn-primary"
                            style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px"
                            data-target="#myLgModal" data-toggle="modal"
                            onclick="showLgModal('{{ route('backstage.users.create')}}')">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加用户
                    </button>
                </header>
                <div class="panel-body ">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false">
                        <thead>
                        <tr>
                            <th data-name="username">用户名</th>
                            <th data-name="phone" data-breakpoints="xs sm">手机号</th>
                            <th data-name="email" data-breakpoints="xs sm">邮箱</th>
                            <th data-name="created_at" data-breakpoints="xs sm">注册时间</th>
                            <th data-name="last_logined_at" data-breakpoints="xs sm">登录时间</th>
                            <th data-name="last_logined_at">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->last_logined_at }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-outline"
                                                data-target="#myLgModal" data-toggle="modal"
                                                onclick="showLgModal('{{ route('backstage.users.show', ['user' => $user->hashId ])}}')">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                    查看用户
                                                </font>
                                            </font>
                                        </button>
                                        <button type="button" class="btn btn-default dropdown-toggle btn-outline"
                                                id="exampleSplitDropdown1" data-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <div class="dropdown-menu" aria-labelledby="exampleSplitDropdown1" role="menu"
                                             x-placement="bottom-start"
                                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(57px, 39px, 0px);">
                                            <a class="dropdown-item"
                                               href="javaScript:"
                                               data-target="#mySimpleModal" data-toggle="modal"
                                               role="menuitem"
                                               onclick="showSimpleModal('{{ route('backstage.users.reset.show', ['user' => $user->hashId ])}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        重置密码
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               onclick="blockUser('{{ route('backstage.users.block', ['user' => $user->hashId]) }}','{{ $user->hashId }}', '{{  $user->locked}}')"
                                               @if($user->locked == true  || $user->id == auth()->user()->id) style="display: none"
                                               @endif id="lockUser_{{$user->hashId}}">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        封禁用户
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               onclick="blockUser('{{ route('backstage.users.block', ['user' => $user->hashId]) }}', '{{ $user->hashId }}','{{  $user->locked}}')"
                                               @if($user->locked == false || $user->id == auth()->user()->id) style="display: none"
                                               @endif id="unlockUser_{{$user->hashId}}">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        解禁用户
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               data-target="#myLgModal" data-toggle="modal"
                                               onclick="showLgModal('{{ route('backstage.users.edit', ['user' => $user->hashId ])}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        编辑用户
                                                    </font>
                                                </font>
                                            </a>
                                            @if(auth('web')->user()->hasRole(App\Enums\UserType::SUPER_ADMIN))
                                                <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                                   data-target="#mySimpleModal" data-toggle="modal"
                                                   onclick="showSimpleModal('{{ route('backstage.users.roles.show', ['user' => $user->hashId ])}}')"
                                                >
                                                    <font style="vertical-align: inherit;">
                                                        <font style="vertical-align: inherit;">
                                                            编辑用户组
                                                        </font>
                                                    </font>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-body">
                    {{ $users->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
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
        function blockUser(fetchUrl, hashId, status) {
            // 提示信息
            let message = null;
            let type = 'block';
            // 类型转换
            if (Boolean(parseInt(status)) === false) {
                message = '确认封禁用户?'
                type = 'block'
            } else {
                message = '确认解禁用户?'
                type = 'unblock'
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
                        data: {"_token": "{{csrf_token()}}", type: type},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});

                            const lockUserID = '#lockUser_' + hashId;
                            const unLockUserID = '#unlockUser_' + hashId;
                            // 类型转换
                            if (Boolean(parseInt(status)) === false) {
                                $(lockUserID).hide();
                                $(unLockUserID).show();
                            } else {
                                $(lockUserID).show();
                                $(unLockUserID).hide();
                            }
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








