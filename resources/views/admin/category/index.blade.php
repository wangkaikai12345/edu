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
@section('page-title', '分类分组管理')
@section('content')


    {{--小的modal--}}
    <div class="modal fade" id="mySimpleModal" aria-labelledby="mySimpleModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <form class="modal-content">

            </form>
        </div>
    </div>


    {{--小的modal--}}
    <div class="modal fade" id="myCategorySimpleModal" aria-labelledby="myCategorySimpleModalLabel" role="dialog"
         tabindex="-1"
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

    {{--大的modal--}}
    <div class="modal fade" id="myLgTableModal" aria-labelledby="myLgTableModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <div class="modal-table-content" id="myLgTableModal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLgTableModal">子类</h4>
                </div>
                <div class="modal-body ">
                    <table class="table table-hover   toggle-circle"
                           data-paging="false">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody class="table-tbody-content">

                        </tbody>
                    </table>
                </div>
            </div>
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
                            onclick="showLgModal('{{ route('backstage.categoryGroup.create')}}')">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加分组
                    </button>
                </header>
                <div class="">
                    @foreach($categoryGroups as $categoryGroup)
                        <div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true"
                             role="tablist">
                            <div class="panel">
                                <div class="panel-heading" id="exampleHeadingDefault{{$categoryGroup->hashId}}"
                                     role="tab">
                                    <a class="panel-title collapsed" data-toggle="collapse"
                                       href="#exampleCollapseDefault{{$categoryGroup->hashId}}"
                                       data-parent="#exampleAccordionDefault"
                                       aria-expanded="false" aria-controls="exampleCollapseDefaultOne">
                                        <div>
                                            分类名称: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $categoryGroup->title }}
                                        </div>

                                    </a>
                                </div>
                                <div class="panel-collapse collapse"
                                     id="exampleCollapseDefault{{$categoryGroup->hashId}}"
                                     aria-labelledby="exampleHeadingDefaultOne" role="tabpanel" style="">
                                    <div class="panel-body">
                                        <div class="row collapsed"
                                             style="position: relative;padding: 8px 50px;font-size: 15px;font-weight: 500">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $categoryGroup->title }}
                                            <div style="position: absolute;right: 45px;top: 5px;">
                                                <button class="btn btn-default btn-sm"
                                                        data-target="#mySimpleModal" data-toggle="modal"
                                                        onclick="showSimpleModal('{{ route('backstage.category.create', ['categoryGroup' => $categoryGroup->hashId ])}}')"
                                                >添加子类
                                                </button>
                                                <div style="position: relative;right: 61px;top: -31px">
                                                    <button class="btn btn-sm btn-primary"
                                                            data-target="#myLgModal" data-toggle="modal"
                                                            onclick="showLgModal('{{ route('backstage.categoryGroup.edit', ['categoryGroup' => $categoryGroup->hashId])}}')"
                                                    >编辑
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach($categoryGroup->categories as $category)
                                            <div class="row" style="position: relative;padding: 8px 80px;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a data-target="#myLgTableModal"
                                                                                 data-toggle="modal"
                                                                                 onclick="showLgTableModal('{{ route('backstage.category.index',
                                                                                 ['categoryGroup' => $categoryGroup->hashId, 'parent_id' => $category->id])}}')">{{ $category->name }}</a>
                                                <div style="position: absolute;right: 45px;">
                                                    <button class="btn btn-default btn-sm"
                                                            data-target="#mySimpleModal" data-toggle="modal"
                                                            onclick="showSimpleModal('{{ route('backstage.category.create',
                                                                                 ['categoryGroup' => $categoryGroup->hashId, 'parent_id' => $category->id])}}')"
                                                    >添加子类
                                                    </button>
                                                </div>
                                                <div style="position: absolute;right: 130px;">
                                                    <button class="btn  btn-sm btn-primary"
                                                            data-target="#mySimpleModal" data-toggle="modal"
                                                            onclick="showSimpleModal('{{ route('backstage.category.edit',
                                                                                 ['categoryGroup' => $categoryGroup->hashId, 'category' => $category->id])}}')"
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

    </script>
@stop








