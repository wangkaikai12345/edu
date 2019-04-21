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
@section('page-title', '班级管理')
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
                                    <a class="nav-link  active show" href="{{ route('backstage.classrooms.index') }}">班级管理
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.classrooms.recommend.index') }}">班级推荐
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <header class="panel-heading">
                                                <h3 class="panel-title">班级搜索:</h3>
                                            </header>
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <form action="{{ route('backstage.classrooms.index') }}" method="GET">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="title"
                                                                   placeholder="班级标题"
                                                                   autocomplete="off" value="{{ request('title') }}">
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
                            style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加班级
                    </button>
                </header>
                <div class="panel-body">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false">
                        <thead>
                        <tr>
                            <th>标题</th>
                            <th>价格</th>
                            <th>课程数</th>
                            <th>班级状态</th>
                            <th>学员数</th>
                            <th>创建者</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($classrooms as $classroom)
                            <tr>
                                <td>{{ $classroom->title }}</td>
                                <td>{{ $classroom->price / 100 }}元</td>
                                <td>{{ $classroom->courses_count }}</td>
                                <td>{{ $status[$classroom->status] }}</td>
                                <td>{{ $classroom->members_count }}</td>
                                <td>{{ $classroom->user->username }}</td>
                                <td>{{ $classroom->created_at }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-outline">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                    查看班级
                                                </font>
                                            </font>
                                        </button>
                                        <a class="btn btn-default dropdown-toggle btn-outline"
                                           id="exampleSplitDropdown1" data-toggle="dropdown"
                                           aria-expanded="false"></a>
                                        <div class="dropdown-menu" aria-labelledby="exampleSplitDropdown1" role="menu"
                                             x-placement="bottom-start"
                                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(57px, 39px, 0px);">
                                            <a class="dropdown-item"
                                               href="javaScript:"
                                               data-target="#mySimpleModal" data-toggle="modal"
                                               role="menuitem"
                                               onclick="showSimpleModal('{{ route('backstage.classrooms.recommend.show', ['classroom' => $classroom->hashId ])}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        推荐班级
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               onclick="publishedCourse('{{ route('backstage.classrooms.publish', ['classroom' => $classroom->hashId]) }}','{{ $classroom->hashId }}', 'published')"
                                               @if($classroom->status == 'published') style="display: none"
                                               @endif id="public_{{$classroom->hashId}}">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        发布班级
                                                    </font>
                                                </font>
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               onclick="publishedCourse('{{ route('backstage.classrooms.publish', ['classroom' => $classroom->hashId]) }}', '{{ $classroom->hashId }}','closed')"
                                               @if($classroom->status == 'closed' || $classroom->status == 'draft') style="display: none"
                                               @endif id="close_{{$classroom->hashId}}">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        取消发布
                                                    </font>
                                                </font>
                                            </a>
                                            @if($classroom->status !== 'published')
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               onclick="deleteCourse('{{ route('backstage.classrooms.destroy', ['classroom' => $classroom->hashId ])}}')">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        删除班级
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
                    {{ $classrooms->links() }}
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
                message = '确认发布班级?'
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
        function deleteCourse(fetchUrl) {

            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定删除班级？", function () {
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








