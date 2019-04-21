@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="/backstage/global/vendor/blueimp-file-upload/jquery.fileupload.css">
    <link rel="stylesheet" href="/backstage/global/vendor/dropify/dropify.css">
    <link rel="stylesheet" href="/backstage/global/vendor/cropper/cropper.css">
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
@section('page-title', '站点配置')
@section('content')
    {{--小的modal--}}
    <div class="modal fade" id="mySimpleModal" aria-labelledby="mySimpleModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content">

            </div>
        </div>
    </div>

    {{--大的modal--}}
    <div class="modal fade" id="myLgModal" aria-labelledby="myLgModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <div class="modal-content">

            </div>
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
                                    <a class="nav-link" href="{{ route('backstage.settings.index') }}">基本信息
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'email']) }}">邮件服务器设置
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'qiniu']) }}">存储设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'ali_pay']) }}">支付宝设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'wechat_pay']) }}">微信设置</a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'sms']) }}">短信设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'message']) }}">私信设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'login']) }}">登录设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link "
                                       href="{{ route('backstage.settings.show', ['namespace' => 'register']) }}">注册设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link "
                                       href="{{ route('backstage.settings.show', ['namespace' => 'avatar']) }}">头像设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.head') }}">顶部导航</a>
                                </li>
                                <li class="nav-item active show" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.footer') }}">底部导航</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <div>
                                                    <header class="panel-heading" style="padding-bottom: 20px">
                                                        <h3 class="panel-title" style="display: inline-block"></h3>
                                                        <button type="button" class="btn btn-sm btn-outline btn-primary"
                                                                style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px"
                                                                data-target="#mySimpleModal" data-toggle="modal"
                                                                onclick="showSimpleModal('{{ route('backstage.settings.footer.create')}}')">
                                                            <i class="icon wb-plus" aria-hidden="true"></i> 添加导航
                                                        </button>
                                                    </header>
                                                    <div id="permission_content">
                                                        @forelse($heads as $head)
                                                            <div class="panel-group permission_content_{{$head->hashId}}"
                                                                 id="exampleAccordionDefault"
                                                                 aria-multiselectable="true"
                                                                 role="tablist">
                                                                <div class="panel">
                                                                    <div class="panel-heading"
                                                                         id="exampleHeadingDefault{{$head->id}}"
                                                                         role="tab">
                                                                        <a class="panel-title collapsed"
                                                                           data-toggle="collapse"
                                                                           href="#exampleCollapseDefault{{$head->id}}"
                                                                           data-parent="#exampleAccordionDefault"
                                                                           aria-expanded="false"
                                                                           aria-controls="exampleCollapseDefaultOne">
                                                                            <div id="children_title_{{$head->hashId}}">
                                                                                导航组名:
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $head->name }}
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div class="panel-collapse collapse"
                                                                         id="exampleCollapseDefault{{$head->id}}"
                                                                         aria-labelledby="exampleHeadingDefaultOne"
                                                                         role="tabpanel" style="">
                                                                        <div class="panel-body" style="padding-top: 0px">

                                                                            <table class="table table-hover  toggle-circle"
                                                                                   data-paging="false">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>名称</th>
                                                                                    <th>操作</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody id="children_{{$head->hashId}}">
                                                                                <tr id="children_content_{{$head->hashId}}">
                                                                                    <td style="padding-left: 50px;padding-right: 130px">
                                                                                        组-{{$head->name}}</td>
                                                                                    <td>
                                                                                        <button class="btn btn-sm btn-primary"
                                                                                                data-target="#mySimpleModal"
                                                                                                data-toggle="modal"
                                                                                                onclick="showSimpleModal('{{ route('backstage.settings.navigation.edit', ['navigation' => $head->hashId])}}')">
                                                                                            编辑
                                                                                        </button>
                                                                                        <button class="btn btn-sm btn-primary"
                                                                                                data-target="#mySimpleModal"
                                                                                                data-toggle="modal"
                                                                                                onclick="showSimpleModal('{{ route('backstage.settings.navigation.create.child', ['navigation' => $head->hashId])}}')">
                                                                                            添加子导航
                                                                                        </button>
                                                                                        @if($head->children->isEmpty())
                                                                                            <button class="btn btn-sm btn-danger"
                                                                                                    onclick="deleteNav('{{route('backstage.settings.navigation.destroy', ['navigation' => $head->hashId])}}', '{{".permission_content_" . $head->hashId}}', true)">
                                                                                            删除
                                                                                            </button>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                @foreach($head->children as $child)
                                                                                    <tr id="children_content_{{$child->hashId}}">
                                                                                        <td style="padding-left: 70px">{{$child->name}}</td>
                                                                                        <td>
                                                                                            <button class="btn btn-sm btn-primary"
                                                                                                    data-target="#mySimpleModal"
                                                                                                    data-toggle="modal"
                                                                                                    onclick="showSimpleModal('{{ route('backstage.settings.navigation.edit', ['navigation' => $child->hashId])}}')">
                                                                                                编辑
                                                                                            </button>
                                                                                            <button class="btn btn-sm btn-danger"
                                                                                                    onclick="deleteNav('{{route('backstage.settings.navigation.destroy', ['navigation' => $child->hashId])}}', '{{"#children_content_" . $child->hashId}}', false)">
                                                                                            删除
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
                                                        @empty
                                                            <div style="text-align: center">无导航数据</div>
                                                        @endforelse
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
        </div>
    </div>

@stop

@section('script')
    @include('admin.layouts.validation')
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
    function deleteNav(fetchUrl, key) {
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

                       window.location.reload()
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










