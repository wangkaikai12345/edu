{{--<link rel="stylesheet" href="{{ mix('/css/front/personal/details_modal.css') }}">--}}
{{--<div class="modal modal-fluid fade" id="orderDetails" tabindex="-1" role="dialog" aria-labelledby="modal_1"--}}
{{--aria-hidden="true">--}}
{{--<div class="modal-dialog modal-lg" role="document">--}}
{{--<div class="modal-content">--}}
{{--<div class="details-modal-body">--}}
{{--<div class="modal_head_title row">--}}
{{--<p class="col-md-10">订单详情</p>--}}
{{--<button class="col-md-2" type="button" data-dismiss="modal"><i class="iconfont">&#xe656;</i>--}}
{{--</button>--}}
{{--</div>--}}
{{--<div class="modal_con_data">--}}
{{--<div class="order_item_content">--}}
{{--<div class="data_item">--}}
{{--<div class="data_item_c col-md-12 pl-0 col-11">--}}
{{--<div class="data_item_cc input-group input-group-transparent">--}}
{{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">--}}
{{--<span>订单号:</span>--}}
{{--</label>--}}
{{--<div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">--}}
{{--bhjk123jhg1jh12f3hjf12hjf3hj1gv2jh3v12jhv3hj--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="data_item">--}}
{{--<div class="data_item_c col-md-12 pl-0 col-11">--}}
{{--<div class="data_item_cc input-group input-group-transparent">--}}
{{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">--}}
{{--<span>商品名称:</span>--}}
{{--</label>--}}
{{--<div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">--}}
{{--阿萨德快乐就好看了大圣科技撒按时--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="data_item">--}}
{{--<div class="data_item_c col-md-12 pl-0 col-11">--}}
{{--<div class="data_item_cc input-group input-group-transparent">--}}
{{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">--}}
{{--<span>商品价格:</span>--}}
{{--</label>--}}
{{--<div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">--}}
{{--0.01元--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="data_item">--}}
{{--<div class="data_item_c col-md-12 pl-0 col-11">--}}
{{--<div class="data_item_cc input-group input-group-transparent">--}}
{{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">--}}
{{--<span>订单价格:</span>--}}
{{--</label>--}}
{{--<div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">--}}
{{--0.01元--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="data_item">--}}
{{--<div class="data_item_c col-md-12 pl-0 col-11">--}}
{{--<div class="data_item_cc input-group input-group-transparent">--}}
{{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">--}}
{{--<span>创建时间:</span>--}}
{{--</label>--}}
{{--<div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">--}}
{{--2019-01-01 00:00:00--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="data_item">--}}
{{--<div class="data_item_c col-md-12 pl-0 col-11">--}}
{{--<div class="data_item_cc input-group input-group-transparent">--}}
{{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">--}}
{{--<span>订单状态:</span>--}}
{{--</label>--}}
{{--<div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">--}}
{{--交易结束--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="data_item">--}}
{{--<div class="data_item_c col-md-12 pl-0 col-11">--}}
{{--<div class="data_item_cc input-group input-group-transparent">--}}
{{--<label class="control-label col-md-2 col-lg-2 col-xl-5 text-left modal-label modal-last-label">--}}
{{--<span style="color:#999;">退款已过期</span>--}}
{{--</label>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="identifyAndCancel">--}}
{{--<button type="button" data-dismiss="modal">关闭</button>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}

<div class="modal-header">
    <h5 class="modal-title">订单详情</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <ul class="list-group z-depth-0 list-group-flush">
        <li class="list-group-item justify-content-between">
            <div class="font-small">订单号:
                {{ $order['trade_uuid'] }}
            </div>
        </li>
        <li class="list-group-item justify-content-between">
            <div class="font-small">订单状态: {{ render_order_status($order['status']) }}</div>
        </li>
        <li class="list-group-item justify-content-between">
            <div class="font-small">商品名称:{{ $order['title'] }}
            </div>
        </li>
        <li class="list-group-item justify-content-between">
            <div class="font-small">
                商品价格: {{ $order->product['coin_price'] ? $order->product['coin_price'].' 虚拟币' : ftoy($order->product['price']).' 元'}}</div>
        </li>
        <li class="list-group-item justify-content-between">
            <div class="font-small">
                订单价格: {{ $order['currency'] == 'coin' ? $order['price_amount'].' 虚拟币' : ftoy($order['price_amount']).' 元'}}</div>
        </li>
        <li class="list-group-item justify-content-between">
            <div class="font-small">创建时间: {{ $order['created_at'] }}
            </div>
        </li>
    </ul>
    <ul class="list-group z-depth-0 list-group-flush">
        <li class="list-group-item justify-content-between">
            <div class="font-small">优惠券:
                {{ $order['coupon_code'] ? '('.render_coupon_type($order['coupon_type']).')'.$order['coupon_code'] : '无' }}
            </div>
        </li>
    </ul>
    <ul class="list-group z-depth-0 list-group-flush">
        <li class="list-group-item justify-content-between">
            <div class="font-small">退款信息:
                无
            </div>
        </li>
    </ul>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-primary"
            style="width: 90px;height: 30px;line-height: 9px;font-weight: 400;" data-dismiss="modal">关闭
    </button>
</div>

