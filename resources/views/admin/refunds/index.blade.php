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

        .pagination {
            display: inline-flex;
        !important;
        }

        .pagination-body {
            text-align: center;
        }
    </style>
@stop
@section('page-title', '退款订单管理')
@section('content')


    {{--小的modal--}}
    <div class="modal fade " id="mySimpleModal" aria-labelledby="mySimpleModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{--<div class="panel">--}}
    {{--<div class="panel-body container-fluid" style="padding-bottom: 0px">--}}
    {{--<div class="row row-lg">--}}
    {{--<div class="col-xl-12">--}}
    {{--<!-- Example Tabs -->--}}
    {{--<div class="example-wrap">--}}
    {{--<div class="nav-tabs-horizontal" data-plugin="tabs">--}}
    {{--<div class="tab-content pt-20">--}}
    {{--<div class="row">--}}
    {{--<div class="col-xl-12">--}}
    {{--<div class="panel">--}}
    {{--<header class="panel-heading">--}}
    {{--<h3 class="panel-title">订单搜索:</h3>--}}
    {{--</header>--}}
    {{--<div class="panel-body" style="padding:1px 30px;">--}}
    {{--<form action="{{ route('backstage.orders.index') }}" method="GET">--}}
    {{--<div class="row">--}}
    {{--<div class="form-group col-md-3">--}}
    {{--<input type="text" class="form-control" name="title"--}}
    {{--placeholder="订单名称"--}}
    {{--autocomplete="off" value="{{ request('title') }}">--}}
    {{--</div>--}}
    {{--<div class="form-group col-md-3">--}}
    {{--<select class="form-control" name="status">--}}
    {{--<option value="0">--}}
    {{--<font style="vertical-align: inherit;">--}}
    {{--<font style="vertical-align: inherit;">--}}
    {{--订单状态--}}
    {{--</font>--}}
    {{--</font>--}}
    {{--</option>--}}
    {{--@foreach(\App\Enums\OrderStatus::getValues() as  $value)--}}
    {{--<option value="{{ $value }}"--}}
    {{--@if(request('status') == $value) selected @endif>--}}
    {{--<font style="vertical-align: inherit;">--}}
    {{--<font style="vertical-align: inherit;">--}}
    {{--{{ \App\Enums\OrderStatus::getDescription($value) }}--}}
    {{--</font>--}}
    {{--</font>--}}
    {{--</option>--}}
    {{--@endforeach--}}
    {{--</select>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group text-right">--}}
    {{--<button type="submit" class="btn btn-primary"><font--}}
    {{--style="vertical-align: inherit;">--}}
    {{--<font style="vertical-align: inherit;">搜索--}}
    {{--</font></font></button>--}}
    {{--</div>--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}



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
                            <th class="text-center">退款订单</th>
                            <th class="text-center">订单号</th>
                            <th class="text-center">申请退款金额</th>
                            <th class="text-center">退款人</th>
                            <th class="text-center">申请时间</th>
                            <th class="text-center">退款状态</th>
                            <th class="text-center">处理人</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($refunds as $refund)
                            <tr>
                                <td class="text-center">{{ $refund->title }}</td>
                                <td class="text-center">{{ $refund->payment_sn }}</td>
                                <td class="text-center">{{ $refund->refunded_amount / 100 }}元</td>
                                <td class="text-center">{{ $refund->user->username }}</td>
                                <td class="text-center">{{ $refund->created_at }}</td>
                                <td class="text-center">{{ \App\Enums\OrderStatus::getDescription($refund->status) }}</td>
                                <td class="text-center">{{ $refund->handler->username ?? '无' }}</td>
                                <td class="text-center">

                                    @if(in_array($refund->status, [\App\Enums\OrderStatus::CREATED, \App\Enums\OrderStatus::REFUND_FAILED]))
                                        <button class="btn btn-sm btn-primary"
                                                data-target="#mySimpleModal" data-toggle="modal"
                                                onclick="showSimpleModal('{{ route('backstage.refunds.examine.show', ['refund' => $refund->id]) }}')">
                                            退款审核
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-default"
                                                data-target="#mySimpleModal" data-toggle="modal"
                                                onclick="showSimpleModal('{{ route('backstage.refunds.show', ['refund' => $refund->id]) }}')">
                                            查看详情
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-body">
                    {{ $refunds->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
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

        $("body").on("hidden.bs.modal", function () {
            // 这个#showModal是模态框的id
            $(this).removeData("bs.modal");
            $(this).find(".modal-content").children().remove();
        });

    </script>
@stop








