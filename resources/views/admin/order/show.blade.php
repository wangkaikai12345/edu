<div class="modal-content form-horizontal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLgModal">订单详情</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <table class="table table-hover  toggle-circle"
               data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
            <tbody class="table-tbody-content">
            <tr>
                <td>订单号</td>
                <td>{{ $order->id }}</td>
            </tr>
            <tr>
                <td>订单状态</td>
                <td>{{ \App\Enums\OrderStatus::getDescription($order->status) }}</td>
            </tr>
            <tr>
                <td>商品名称</td>
                <td>{{ $order->title }}</td>
            </tr>
            <tr>
                <td>购买人</td>
                <td>{{ $order->user->username ?? '' }}</td>
            </tr>
            <tr>
                <td>商品价格</td>
                <td>{{ $order->price_amount / 100}}元</td>
            </tr>
            <tr>
                <td>订单价格</td>
                <td>{{ $order->pay_amount / 100 }}元</td>
            </tr>
            <tr>
                <td>创建时间</td>
                <td>{{ $order->created_at }}</td>
            </tr>
            <tr>
                <td>退款有效期</td>
                <td>{{ $order->closed_at }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
