@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
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
@section('page-title', '公告管理')
@section('content')

    {{--大的modal--}}
    <div class="modal fade" id="myLgModal" aria-labelledby="myLgModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <form class="modal-content form-horizontal">
            </form>
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
                            onclick="showLgModal('{{route('backstage.notices.create')}}')">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加公告
                    </button>
                </header>
                <div class="panel-body ">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
                        <thead>
                        <tr>
                            <th>公告内容</th>
                            <th>发布时间</th>
                            <th>发布人</th>
                            <th>开始时间~结束时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($notices as $notice)
                            <tr>
                                <td>
                                    <div class="" data-content="{!! $notice->content !!}" data-trigger="hover"
                                         data-toggle="popover" tabindex="0" title="">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">
                                                {!! substr($notice->content, 0, 20)!!}
                                            </font>
                                        </font>
                                    </div>
                                </td>
                                <td>{{ $notice->created_at }}</td>
                                <td>{{ $notice->user->username ?? null }}</td>
                                <td>{{ $notice->started_at }}&nbsp;~&nbsp;{{ $notice->ended_at }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                            data-target="#myLgModal" data-toggle="modal"
                                            onclick="showLgModal('{{route('backstage.notices.edit', ['notice' =>$notice->id])}}')"
                                    >编辑
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            onclick="deleteNotice('{{  route('backstage.notices.destroy', ['notice' =>$notice->id])}}')">
                                        删除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-body">
                    {{ $notices->appends(request()->all())->links('vendor.pagination.bootstrap-4')  }}
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
        function deleteNotice(fetchUrl) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确认删除?", function () {
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








