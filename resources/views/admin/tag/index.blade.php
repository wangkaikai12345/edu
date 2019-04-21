@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <style>
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

        td {
            line-height: 36px
        }

        .panel > .table-bordered, .panel > .table-responsive > .table-bordered {
            border: 1px solid #e4eaec;
        !important;
        }

        .modal-table-content {
            border: none;
        }

        .modal-table-content {
            box-shadow: 0 2px 12px rgba(0, 0, 0, .2);
        }

        .modal-table-content {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid transparent;
            border-radius: .286rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .2);
            outline: 0;
        }
    </style>
@stop
@section('page-title', '标签分组管理')
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


    <div class="row">
        <div class="col-xl-12">
            <div class="">
                <header class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block"></h3>
                    <button type="button" class="btn btn-sm btn-outline btn-primary"
                            style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px"
                            data-target="#myLgModal" data-toggle="modal"
                            onclick="showLgModal('{{ route('backstage.tagGroups.create')}}')">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加分组
                    </button>
                </header>
                <div class="">
                    @foreach($tagGroups as $tagGroup)
                        <div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true"
                             role="tablist">
                            <div class="panel">
                                <div class="panel-heading" id="exampleHeadingDefault{{$tagGroup->hashId}}"
                                     role="tab">
                                    <a class="panel-title collapsed" data-toggle="collapse"
                                       href="#exampleCollapseDefault{{$tagGroup->hashId}}"
                                       data-parent="#exampleAccordionDefault"
                                       aria-expanded="false" aria-controls="exampleCollapseDefaultOne">
                                        <div>
                                            分类名称: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tagGroup->description }}
                                        </div>

                                    </a>
                                </div>
                                <div class="panel-collapse collapse"
                                     id="exampleCollapseDefault{{$tagGroup->hashId}}"
                                     aria-labelledby="exampleHeadingDefaultOne" role="tabpanel" style="">
                                    <div class="panel-body">
                                        <div class="row collapsed"
                                             style="position: relative;padding: 8px 50px;font-size: 15px;font-weight: 500">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tagGroup->description }}
                                            <div style="position: absolute;right: 45px;top: 5px;">
                                                <button class="btn btn-default btn-sm"
                                                        data-target="#mySimpleModal" data-toggle="modal"
                                                        onclick="showSimpleModal('{{ route('backstage.tags.create', ['tagsGroup' => $tagGroup->hashId ])}}')"
                                                >添加子类
                                                </button>
                                                <div style="position: relative;right: 61px;top: -31px">
                                                    <button class="btn btn-sm btn-primary"
                                                            data-target="#myLgModal" data-toggle="modal"
                                                            onclick="showLgModal('{{ route('backstage.tagGroups.edit', ['tagsGroup' => $tagGroup->hashId])}}')"
                                                    >编辑
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach($tagGroup->tags as $tag)
                                            <div class="row" style="position: relative;padding: 8px 80px;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
                                                        style="text-decoration: none">
                                                    {{ $tag->name }}
                                                </a>
                                                <div style="position: absolute;right: 45px;">
                                                    <button class="btn btn-danger btn-sm"
                                                            onclick="deleteTag('{{ route('backstage.tags.destroy',  ['tagGroup' => $tagGroup->hashId, 'tag' => $tag->id]) }}');">
                                                        删除
                                                    </button>
                                                </div>
                                                <div style="position: absolute;right: 100px;">
                                                    <button class="btn  btn-sm btn-primary"
                                                            data-target="#mySimpleModal" data-toggle="modal"
                                                            onclick="showSimpleModal('{{ route('backstage.tags.edit',
                                                                                 ['tagGroup' => $tagGroup->hashId, 'tag' => $tag->id])}}')"
                                                    >编辑
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
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

        // 展示modal
        function showLgTableModal(url) {
            $("#myLgTableModal .modal-table-content .table-tbody-content").load(url);
        }

        $("#appendChildren").on({
            click: function (e) {
                e.stopPropagation();
            }
        })

        // 用户状态操作
        function deleteTag(fetchUrl) {


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

                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
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








