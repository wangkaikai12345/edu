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
@section('page-title', '教师管理')
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
                                    <a class="nav-link  active show"
                                       href="{{ route('backstage.users.teacher.index') }}">教师管理
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link  " href="{{ route('backstage.users.teacher.recommend.index') }}">推荐教师
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <header class="panel-heading">
                                                <h3 class="panel-title">教师搜索:</h3>
                                            </header>
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <form action="{{ route('backstage.users.teacher.index') }}"
                                                      method="GET">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="username"
                                                                   placeholder="昵称"
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
                            onclick="showLgModal('{{ route('backstage.users.teacher.create')}}')">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加教师
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
                            <th data-name="created_at" data-breakpoints="xs sm">推荐老师</th>
                            <th data-name="last_logined_at" data-breakpoints="xs sm">登录时间</th>
                            <th data-name="last_logined_at">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->username }}</td>
                                <td>{{ $teacher->phone }}</td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ $teacher->is_recommended ? '是' : '否' }}</td>
                                <td>{{ $teacher->last_logined_at }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn  btn-sm btn-primary"
                                            data-target="#mySimpleModal" data-toggle="modal"
                                            onclick="showSimpleModal('{{ route('backstage.users.teacher.recommend.show', ['user' => $teacher]) }}')">
                                        <font style="vertical-align: inherit;"><font
                                                    style="vertical-align: inherit;">推荐教师</font></font>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-body">
                    {{ $teachers->links() }}
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
    </script>
@stop








