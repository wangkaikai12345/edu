@extends('admin.layouts.app')
@section('page-title', '在线用户')
@section('style')
    <style>
        .card {
            margin-bottom: 0px;
        }
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
                                    <a class="nav-link active show" href="{{ route('backstage.active.users') }}">在线用户
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
                                            <div class="card border border-success">
                                                <div class="card-block">
                                                    <p class="card-text">共查询到 {{ $logs->total() }}
                                                        位符合条件的在线用户（{{ $subMinutes }}分钟以内有活动)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <div class="">
                                                <table class="table table-bordered table-hover t toggle-circle"
                                                       data-paging="false">
                                                    <thead>
                                                    <tr>
                                                        <th data-name="username">用户名</th>
                                                        <th data-name="phone" data-breakpoints="xs sm">设备</th>
                                                        <th data-name="email" data-breakpoints="xs sm">客户端</th>
                                                        <th data-name="created_at" data-breakpoints="xs sm">操作系统</th>
                                                        <th data-name="last_logined_at" data-breakpoints="xs sm">IP
                                                        </th>
                                                        <th data-name="last_logined_at">最近登录时间</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($logs as $log)
                                                        <tr>
                                                            <td>{{ $log->user->username }}</td>
                                                            <td>{{ $log->device }}</td>
                                                            <td>{{ $log->browser }}</td>
                                                            <td>{{ $log->platform }}</td>
                                                            <td>{{ $log->ip }}</td>
                                                            <td>{{ $log->user->last_logined_at }}</td>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop










