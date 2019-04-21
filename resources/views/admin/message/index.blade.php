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
@section('page-title', '私信管理')
@section('content')
    <div class="panel">
        <div class="panel-body container-fluid" style="padding-bottom: 0px">
            <div class="row">
                <div class="col-xl-12">
                    <div class="panel">
                        <header class="panel-heading">
                            <h3 class="panel-title">私信搜索:</h3>
                        </header>
                        <div class="panel-body" style="padding:1px 30px;">
                            <form action="{{ route('backstage.messages.manage.index') }}" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <input type="text" class="form-control" name="sender:username"
                                               placeholder="发信人"
                                               autocomplete="off" value="{{ request('sender:username') }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input type="text" class="form-control" name="recipient:username"
                                               placeholder="收信人"
                                               autocomplete="off" value="{{ request('recipient:username') }}">
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
    <div class="row">
        <div class="col-xl-12">
            <div class="panel">
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
                            <th>发信人</th>
                            <th>收信人</th>
                            <th>内容</th>
                            <th>发送时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr>
                                <td  style="padding-left: 5px">
                                <span class="checkbox-custom checkbox-primary">
                                  <input class="selectable-item" type="checkbox" name="message_uuids[]"
                                         id="row-{{$message->uuid}}"
                                         value="{{$message->uuid}}">
                                  <label for="row-{{$message->uuid}}"></label>
                            </span>
                                </td>
                                <td>{{ $message->sender->username }}</td>
                                <td>{{ $message->recipient->username }}</td>
                                <td>{{ substr($message->body, 0, 30) }}</td>
                                <td>{{ $message->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($messages->total())
                    <div class="row">
                        <div class="col-md-12" style="padding-left: 45px;margin-top: -20px">
                            <button class="btn btn-default" onclick="deleteAll()">批量删除</button>
                        </div>
                    </div>
                @endif
                <div class="pagination-body">
                    {{ $messages->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script src="/backstage/global/js/Plugin/asselectable.js"></script>
    <script src="/backstage/global/js/Plugin/selectable.js"></script>
    <script>
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
                        url: "{{ route('backstage.messages.destroy') }}",
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}", message_uuids: value},
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









