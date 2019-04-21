
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
        <div class="font-small">商品价格: {{ $order->product['coin_price'] ? $order->product['coin_price'].' 虚拟币' : ftoy($order->product['price']).' 元'}}</div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">订单价格: {{ $order['currency'] == 'coin' ? $order['price_amount'].' 虚拟币' : ftoy($order['price_amount']).' 元'}}</div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">创建时间: {{ $order['created_at'] }}
        </div>
    </li>
</ul>
<ul class="list-group z-depth-0 list-group-flush">
    <li class="list-group-item justify-content-between">
        <div class="font-small">优惠码:
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