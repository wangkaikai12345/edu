@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="/backstage/assets/css/site.min.css">

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

        .pagination {
            display: inline-flex;
        !important;
        }

        .pagination-body {
            text-align: center;
        }
    </style>
@stop
@section('page-title', '笔记管理')
@section('content')


    <div class="row">
        <div class="col-xl-12">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block"></h3>
                </header>
                <div class="panel-body ">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
                        <thead>
                        <tr>
                            <th class="text-center">笔记内容</th>
                            <th class="text-center">作者</th>
                            <th class="text-center">创建时间</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($notes as $note)
                            <tr>
                                <td class="text-center">
                                    <div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true"
                                         role="tablist" style="margin-bottom: 0px">
                                        <div class="panel">
                                            <div class="panel-heading" id="exampleHeadingDefault{{$note->id}}" role="tab">
                                                <a class="panel-title collapsed" data-toggle="collapse"
                                                   href="#exampleCollapseDefault{{$note->id}}" data-parent="#exampleAccordionDefault"
                                                   aria-expanded="false" aria-controls="exampleCollapseDefault{{$note->id}}">
                                                   {{ $note->course->title }}
                                                </a>
                                            </div>
                                            <div class="panel-collapse collapse" id="exampleCollapseDefault{{$note->id}}"
                                                 aria-labelledby="exampleHeadingDefault{{$note->id}}" role="tabpanel" style="">
                                                <div class="panel-body">
                                                   {!! $note->content !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center" style="line-height: 65px;">{{ $note->user->username ?? null}}</td>
                                <td class="text-center" style="line-height: 65px;">{{ $note->created_at }}</td>
                                <td class="text-center" style="line-height: 65px;">
                                    <button class="btn btn-sm btn-danger"
                                            onclick="deleteTopic('{{ route('backstage.notes.destroy', ['note' => $note->id]) }}')">
                                        删除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-body">
                    {{ $notes->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="/backstage/assets/js/Site.js"></script>
    <script>
        // 用户状态操作
        function deleteTopic(fetchUrl) {
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








