<ul class="list-group z-depth-0 list-group-flush">
    <li class="list-group-item justify-content-between">
        <div class="font-small">订单号:
         {{ $refund->order['trade_uuid'] }}
        </div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">退款单号:
            {{ $refund['payment_sn'] }}
        </div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">订单支付方式:
            {{ render_payment($refund['payment']) }}
        </div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">订单状态:{{ render_order_status($refund['status']) }} </div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">商品名称: {{ $refund['title'] }}
        </div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">订单价格: {{ ftoy($refund->order['price']) }}</div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">申请退款: {{ ftoy($refund['applied_amount']).'元' }}</div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">实际退款: {{ ftoy($refund['refunded_amount']).'元' }}</div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">申请理由: {{ $refund['reason'] }}</div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">
            申请时间：{{ $refund['created_at'] }}
        </div>
    </li>
    <li class="list-group-item justify-content-between">
        <div class="font-small">
            最后操作时间：{{ $refund['updated_at'] }}
        </div>
    </li>
</ul>