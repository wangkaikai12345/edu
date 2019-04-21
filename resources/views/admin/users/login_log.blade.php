@extends('admin.layouts.app')
@section('page-title', '登录日志')
@section('style')
    <style>
        td {
            line-height: 36px
        }
        .panel>.table-bordered, .panel>.table-responsive>.table-bordered {
            border:1px solid #e4eaec;!important;
        }
    </style>
@stop
@section('content')
    <div class="panel">
        <div class="panel-body container-fluid" style="padding-bottom: 0px">
            <div class="row row-lg">
                <div class="col-xl-12">
                    <!-- Example Tabs -->
                    <div class="example-wrap">
                        <div class="nav-tabs-horizontal" data-plugin="tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.users.index') }}">用户管理
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.active.users') }}">在线用户
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active show"
                                       href="{{ route('backstage.users.login_log') }}">登录日志</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="panel-body container-fluid" style="padding:1px 30px;">
                                    <form action="{{ route('backstage.users.login_log') }}" method="GET">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <input type="text" class="form-control" name="username"
                                                       placeholder="昵称"
                                                       autocomplete="off" value="{{ request('username') }}">
                                            </div>
                                            <div class="form-group col-md-5">
                                                <div class="example" style="margin-top: 0px">
                                                    <div class="input-daterange" data-plugin="datepicker"
                                                         data-orientation="bottom"
                                                         data-language="cn"
                                                         data-format="yyyy/mm/dd"
                                                    >
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control" name="start"
                                                                   placeholder="开始时间" value="{{ request('start') }}">
                                                        </div>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">to</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="end"
                                                                   placeholder="结束时间" value="{{ request('end') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="reset" class="btn btn-default"><font
                                                        style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        重置
                                                    </font></font></button>
                                            <button type="submit" class="btn btn-primary"><font
                                                        style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">搜索
                                                    </font></font>
                                            </button>
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
    <div class="row">
        <div class="col-xl-12">
            <div class="panel">
                <div class="panel-body ">
                    <table class="table table-bordered table-hover   toggle-circle"
                           data-paging="false">
                        <thead>
                        <tr>
                            <th data-name="username">用户名</th>
                            <th data-name="phone" data-breakpoints="xs sm">地区</th>
                            <th data-name="created_at" data-breakpoints="xs sm">注册时间</th>
                            <th data-name="created_at" data-breakpoints="xs sm">注册IP</th>
                            <th data-name="last_logined_at" data-breakpoints="xs sm">最近登录时间</th>
                            <th data-name="last_logined_at" class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                            {{ $log->user->username ?? '暂无信息' }}
                                        </font></font>
                                </td>
                                <td>
                                    <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                            {{ $log->area ?? '暂无信息' }}
                                        </font></font>
                                </td>
                                <td>
                                    <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                            {{ $log->user->created_at ?? '暂无信息' }}
                                        </font></font>
                                </td>
                                <td>
                                    <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                            {{ $log->user->registered_ip ?? '暂无信息'}}
                                        </font></font>
                                </td>
                                <td>
                                    <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                            {{ $log->request_time ?? '暂无信息'}}
                                        </font></font>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline btn-default" data-target="#myLgModal"
                                            data-toggle="modal"
                                            onclick="showLgModal('{{ route('backstage.users.logs', ['user' => $log->user->hashId ?? 0])}}')"
                                    >查看详情
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-body">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    {{--大的modal--}}
    <div class="modal fade" id="myLgModal" aria-labelledby="myLgModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <div class="modal-content" id="userLogs">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="mySimpleModal">登录日志详情</h4>
                </div>
                <div class="modal-body ">
                    <table class="table table-bordered table-hover   toggle-circle"
                           data-paging="false">
                        <thead>
                        <tr>
                            <th>用户名</th>
                            <th>登录时间</th>
                            <th>登录IP</th>
                            <th>登录地点</th>
                        </tr>
                        </thead>
                        <tbody class="table-tbody-content">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script src="/backstage/global/vendor/bootstrap-datepicker/my-bootstrap-datepicker.js"></script>
    <script>
        // 展示modal
        function showLgModal(url) {
            $("#myLgModal .modal-content .table-tbody-content").load(url);
        }
    </script>
@stop









