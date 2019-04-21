@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <style>
        td {
            line-height: 32px;
        }

        a:not([href]):not([tabindex]) {
            text-decoration: underline;
        !important;
            cursor: pointer;
        }

        #exampleSplitDropdown1 a {
            text-decoration: none;
        !important;
        }

        .required {
            color: red;
        }
    </style>
@stop
@section('page-title', '权限管理')
@section('content')
    {{--大的modal--}}
    <div class="modal fade" id="myLgModal" aria-labelledby="myLgModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <form class="modal-content">

            </form>
        </div>
    </div>

    {{--小的modal--}}
    <div class="modal fade" id="mySimpleModal" aria-labelledby="mySimpleModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <form class="modal-content">

            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div>
                <header class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block"></h3>
                    <button type="button" class="btn btn-sm btn-outline btn-primary"
                            style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px"
                            data-target="#mySimpleModal" data-toggle="modal"
                            onclick="showSimpleModal('{{ route('backstage.permissions.create')}}')">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加权限组
                    </button>
                </header>
                <div id="permission_content">
                    @foreach($permissions as $permission)
                        <div class="panel-group permission_content_{{$permission->hashId}}" id="exampleAccordionDefault" aria-multiselectable="true"
                             role="tablist">
                            <div class="panel">
                                <div class="panel-heading" id="exampleHeadingDefault{{$permission->id}}"
                                     role="tab">
                                    <a class="panel-title collapsed" data-toggle="collapse"
                                       href="#exampleCollapseDefault{{$permission->id}}"
                                       data-parent="#exampleAccordionDefault"
                                       aria-expanded="false" aria-controls="exampleCollapseDefaultOne">
                                        <div id="children_title_{{$permission->hashId}}">
                                            权限组名: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $permission->title }}
                                        </div>

                                    </a>
                                </div>
                                <div class="panel-collapse collapse"
                                     id="exampleCollapseDefault{{$permission->id}}"
                                     aria-labelledby="exampleHeadingDefaultOne" role="tabpanel" style="">
                                    <div class="panel-body">

                                        <table class="table table-hover  toggle-circle"
                                               data-paging="false">
                                            <thead>
                                            <tr>
                                                <th>名称</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody id="children_{{$permission->hashId}}">
                                            <tr id="children_content_{{$permission->hashId}}">
                                                <td>组-{{$permission->title}}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary"
                                                            data-target="#mySimpleModal" data-toggle="modal"
                                                            onclick="showSimpleModal('{{ route('backstage.permissions.edit', ['permission' => $permission->hashId])}}')">
                                                        编辑
                                                    </button>
                                                    <button class="btn btn-sm btn-primary"
                                                            data-target="#mySimpleModal" data-toggle="modal"
                                                            onclick="showSimpleModal('{{ route('backstage.permissions.create', ['parent_id' => $permission->id])}}')">
                                                        添加子权限
                                                    </button>
                                                    @if($permission->children->isEmpty())
                                                        <button class="btn btn-sm btn-danger"
                                                                onclick="deletePermission('{{route('backstage.permissions.destroy', ['permission' => $permission->hashId])}}', '{{".permission_content_" . $permission->hashId}}', true)">删除</button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @foreach($permission->children as $child)
                                                <tr id="children_content_{{$child->hashId}}">
                                                    <td>{{$child->title}}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary"
                                                                data-target="#mySimpleModal" data-toggle="modal"
                                                                onclick="showSimpleModal('{{ route('backstage.permissions.edit', ['permission' => $child->hashId])}}')">
                                                            编辑
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                        onclick="deletePermission('{{route('backstage.permissions.destroy', ['permission' => $child->hashId])}}', '{{"#children_content_" . $child->hashId}}', false)">删除
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
        function deletePermission(fetchUrl, key) {
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

                           $(key).remove();
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









