@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/personal/order.css') }}">
@endsection

@section('content')
    <div class="xh_plan_content">
        {{--@include('frontend.review.personal.details-modal')--}}
        {{--@include('frontend.review.personal.cancelOrder')--}}
        {{--@include('frontend.review.personal.applyRefund')--}}
        <div class="container">
            <div class="row padding-content">
                @include('frontend.review.personal.navBar')

                <div class="czh_order col-xl-9">
                    <div class="view_head">
                        <div class="view_head_title">
                            <p>我的订单</p>
                        </div>
                        <div class="view_head_form">
                            <form action="" method="get">
                                <div class="form-group">
                                    <input id="searchInp" type="text" class="form-control" name="title"
                                           placeholder="请输入商品名搜索" value="{{ old('title', '') }}" required>
                                    <a href="javascript:;" class="clear" onclick="cssClear()"><i class="iconfont">&#xe6f2;</i></a>
                                </div>
                                <div class="searchBtn" style="width:160px; display:inline-flex;">
                                    <button type="submit">查询</button>
                                    <button type="button"><a href="{{ route('users.order') }}">重置</a></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="view_content row">
                        @foreach($orders as $order)
                            <div class="order_item">
                                <div class="order_item_title">
                                    <p class="order_title" data-toggle="tooltip" data-placement="top" title="{{ $order['title'] }}">{{ $order['title'] }}</p>
                                    <div class="dropdown">
                                        <button type="button" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            <i class="iconfont">&#xe62b;</i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @switch($order['status'])
                                                @case('created')
                                                <a class="dropdown-item"
                                                   @switch($order['product_type'])
                                                   @case('plan')
                                                   href="{{ route('plans.shopping', [ $order->product->course, $order->product ]) }}"
                                                   @break
                                                   @case('recharging')
                                                   href="{{ route('users.coin.shopping', $order['product']) }}"
                                                        @break
                                                        @case('classroom')

                                                        @break
                                                        @default

                                                        @endswitch
                                                >支付订单</a>

                                                <form action="{{ route('users.order.update', $order) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('PUT') }}
                                                    <a class="dropdown-item cancel">取消订单</a>
                                                </form>

                                                @break

                                                @case('paid')

                                                @break

                                                @case('refunding')
                                                <a class="dropdown-item">退款中</a>
                                                @break

                                                @case('refunded')
                                                <a class="dropdown-item">已退款</a>
                                                @break

                                                @case('closed')
                                                <form action="{{ route('users.order.destroy', $order) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <a type="submit"
                                                       class="dropdown-item delete"
                                                    >删除订单</a>
                                                </form>

                                                @break

                                                @case('success')
                                                @if($order['refund_deadline'] > now() && $order['currency'] == 'cny')
                                                    <a class="dropdown-item apply_refund"
                                                       data-route="{{ route('users.refund.store', $order->id) }}"
                                                       data-name="{{ $order->title }}"
                                                       data-price="{{ ftoy($order['price_amount']) }}"
                                                    >申请退款</a>
                                                @else
                                                    <a class="dropdown-item" href="javascript:;">订单已完成</a>
                                                @endif

                                                @break

                                                @case('finished')
                                                <a class="dropdown-item" href="javascript:;">订单已完成</a>
                                                @break

                                                @case('refund_disagree')

                                                @break

                                                @default

                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                                <div class="order_item_content">
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>订单号:</span>
                                                </label>
                                                <div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">
                                                    {{ $order['trade_uuid'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>价格:</span>
                                                </label>
                                                <div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">
                                                    {{ $order['currency'] == 'coin' ? $order['price_amount'].' 虚拟币' : ftoy($order['price_amount']).' 元'}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>优惠券:</span>
                                                </label>
                                                <div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">
                                                    {{ $order['coupon_code'] ? '('.render_coupon_type($order['coupon_type']).')'.$order['coupon_code'] : '无' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>创建时间:</span>
                                                </label>
                                                <div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">
                                                    {{ $order['created_at'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>详细状态:</span>
                                                </label>
                                                <div class="order_status_end data_item_ccc col-xl-6 pl-0">
                                                    {{ render_order_status($order['status']) }}
                                                </div>
                                                <div class="data_item_details text-right col-xl-3 pl-0">
                                                    <a href="javascript:;"
                                                       data-toggle="modal" data-target="#modal"
                                                       data-url="{{ route('users.order.edit', $order->id) }}"
                                                    >详情</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <nav class="pageNumber" aria-label="Page navigation example">
                        {!! $orders->render() !!}
                    </nav>
                </div>
            </div>
        </div>


        <!-- 订单详情model -->
        <div class="modal fade" id="orderInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">订单详情</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="order-body">

                    </div>
                </div>
            </div>
        </div>

        <!-- 申请退款model -->
        <div class="modal fade bd-example-modal-lg" id="refundModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">申请退款</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 15px;">

                        <div class="form-group">
                            <label for="exampleFormControlInput1">商品名称：</label>
                            <br>
                            <span id="product_name"></span>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">实付金额：</label>
                            <br>
                            <span id="price"></span>
                        </div>

                        <div class="form-group">
                            <label for="reason">退款理由:</label>
                            <textarea class="form-control" id="reason" rows="3"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-secondary" id="toRefund">确定</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('script')
    <script>
        function cssClear() {
            // 清空input框内的值
            $('#searchInp').val('');
        }

        // 订单详情的模态框
//        $('.order-info').click(function () {
//
//            $.ajax({
//                url: $(this).data('route'),
//                type: 'get',
//                success: function (res) {
//                },
//                error: function (res) {
//                    if (res.status == 200) {
//                        $('#order-body').empty();
//                        $('#order-body').append(res.responseText);
//                        $('#orderInfoModal').modal('show');
//                    } else {
//                        alert('查看订单出错');
//                    }
//                }
//
//            })
//            return false;
//        })


        // 取消订单
        $('.cancel').click(function () {
            var that = $(this);

            if (confirm("您确定要取消这个订单?")) {
                that.parents('form').submit();
            }
            return false;
        })

        // 删除订单
        $('.delete').click(function () {
            var that = $(this);

            if (confirm("您确定要删除这个订单?")) {
                that.parents('form').submit();
            }
            return false;
        })

        // 申请退款的模态框
        $('.apply_refund').click(function () {

            //
            $('#product_name').text($(this).data('name'));
            $('#price').text($(this).data('price'));
            $('#toRefund').attr('data-route', $(this).data('route'));

            $('#refundModal').modal('show');
        })

        // 申请退款
        $('#toRefund').click(function () {
            var route = $(this).data('route');
            if (!route) {
                alert('请选择订单');
                return false;
            }

            var reason = $('#reason').val();
            if (!reason) {
                alert('请填写退款理由');
                return false;
            }

            $.ajax({
                url: route,
                type: 'post',
                data: {reason: reason},
                success: function (res) {
                    console.log(res);
                    if (res.status == '200') {
                        alert('退款申请成功');
                        $('#toRefund').attr('data-route', '');
                        $('#refundModal').modal('hide');
                    } else {
                        alert(res.message);
                    }
                },
            })

        })


    </script>
@endsection