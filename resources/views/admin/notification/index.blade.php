@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.css') }}">

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
@section('page-title', '站内通知管理')
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
                            onclick="showLgModal('{{route('backstage.notifications.create')}}')">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加站内通知
                    </button>
                </header>
                <div class="panel-body ">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
                        <thead>
                        <tr>
                            <th class="w-50" style="padding-left: 5px">
                            <span class="checkbox-custom checkbox-primary">
                              <input class="selectable-all" type="checkbox">
                              <label></label>
                            </span>
                            </th>
                            <th>标题</th>
                            <th>发布时间</th>

                            <th>接收人</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($notifications as $notification)
                            <tr>
                                <td  style="padding-left: 5px">
                                <span class="checkbox-custom checkbox-primary">
                                  <input class="selectable-item" type="checkbox" name="notification_ids[]"
                                         id="row-{{$notification->id}}"
                                         value="{{$notification->id}}">
                                  <label for="row-{{$notification->d}}"></label>
                            </span>
                                </td>
                                <td>
                                    <div class="" data-content="{!! $notification->data['content'] !!}"
                                         data-trigger="hover"
                                         data-toggle="popover" tabindex="0" title="">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">
                                                {{$notification->data['title']}}
                                            </font>
                                        </font>
                                    </div>
                                </td>
                                <td>{{ $notification->created_at }}</td>
                                <td>{{ $notification->notifiable->username }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger"
                                            onclick="deleteNotification('{{  route('backstage.notifications.destroy', ['notification_ids[]' =>$notification->id])}}')">
                                        删除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($notifications->total())
                    <div class="row">
                        <div class="col-md-12" style="padding-left: 45px;margin-top: -20px">
                            <button class="btn btn-default" onclick="deleteAll()">批量删除</button>
                        </div>
                    </div>
                @endif
                <div class="pagination-body">
                    {{ $notifications->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>


@stop

@section('script')
    <script src="/backstage/global/js/Plugin/asselectable.js"></script>
    <script src="/backstage/global/js/Plugin/selectable.js"></script>
    <script src="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('/backstage/global/js/Plugin/bootstrap-select.js') }}"></script>
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
        function deleteNotification(fetchUrl) {
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

        function deleteAll() {
            // 获取selects
            const selects = $("#messageTable .selectable-item");
            // 建立数组
            let value = new Array();
            // 写入
            for (let i = 0; i < selects.length; i++) {
                if (selects[i].checked)
                    value.push(selects[i].value);
            }

            if (value.length === 0) {
                notie.alert({'type': 3, 'text': '至少选择一项进行操作', 'time': 1.5});
                return false;
            }

            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确定删除?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: "{{ route('backstage.notifications.destroy') }}",
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}", notification_ids: value},
                        success: function (response) {
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








