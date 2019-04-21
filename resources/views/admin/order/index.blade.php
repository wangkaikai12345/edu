@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/timepicker/css/bootstrap-datetimepicker.css') }}">

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
@section('page-title', '订单管理')
@section('content')


    {{--小的modal--}}
    <div class="modal fade" id="mySimpleModal" aria-labelledby="mySimpleModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple">
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
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <header class="panel-heading">
                                                <h3 class="panel-title">订单搜索:</h3>
                                            </header>
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <form action="{{ route('backstage.orders.index') }}" method="GET">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <input type="text" class="form-control" name="title"
                                                                   placeholder="订单名称"
                                                                   autocomplete="off" value="{{ request('title') }}">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="status">
                                                                <option value="0">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            订单状态
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                @foreach(\App\Enums\OrderStatus::getValues() as  $value)
                                                                    <option value="{{ $value }}"
                                                                            @if(request('status') == $value) selected @endif>
                                                                        <font style="vertical-align: inherit;">
                                                                            <font style="vertical-align: inherit;">
                                                                                {{ \App\Enums\OrderStatus::getDescription($value) }}
                                                                            </font>
                                                                        </font>
                                                                    </option>
                                                                @endforeach
                                                            </select>
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
                </header>
                <div class="panel-body ">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
                        <thead>
                        <tr>
                            <th class="text-center">商品名称</th>
                            <th class="text-center">订单状态</th>
                            <th class="text-center">订单价格</th>
                            <th class="text-center">应付金额</th>
                            <th class="text-center">优惠</th>
                            <th class="text-center">购买者</th>
                            <th class="text-center">支付方式</th>
                            {{--<th class="text-center">渠道</th>--}}
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="text-center">{{ $order->title }}</td>
                                <td class="text-center">{{ \App\Enums\OrderStatus::getDescription($order->status) }}</td>
                                <td class="text-center">{{ $order->price_amount / 100 }}元</td>
                                <td class="text-center">{{ $order->pay_amount / 100 }}元</td>
                                <td class="text-center">{{ $order->pay_amount / 100 }}元</td>
                                <td class="text-center">{{ $order->user->username ?? '' }}</td>
                                <td class="text-center">{{ $order->payment }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-default"
                                            data-target="#mySimpleModal" data-toggle="modal"
                                            onclick="showSimpleModal('{{ route('backstage.orders.show', ['order' => $order->id]) }}')">
                                        查看详情
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-body">
                    {{ $orders->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
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








