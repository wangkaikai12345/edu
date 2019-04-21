<div class="modal-content form-horizontal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLgModal">退款订单详情</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <table class="table table-hover  toggle-circle"
               data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
            <tbody class="table-tbody-content">
            <tr>
                <td>订单名称</td>
                <td>{{ $refund->title }}</td>
            </tr>
            <tr>
                <td>订单号</td>
                <td>{{ $refund->payment_sn }}</td>
            </tr>
            <tr>
                <td>申请退款金额</td>
                <td>{{ $refund->refunded_amount / 100 }}元</td>
            </tr>
            <tr>
                <td>退款人</td>
                <td>{{ $refund->user->username }}</td>
            </tr>
            <tr>
                <td>申请时间</td>
                <td>{{ $refund->created_at }}</td>
            </tr>
            <tr>
                <td>退款理由</td>
                <td>{{ $refund->created_at }}</td>
            </tr>
            <tr>
                <td>退款类型</td>
                <td>{{ $refund->currency }}</td>
            </tr>
            <tr>
                <td>退款状态</td>
                <td>{{ \App\Enums\OrderStatus::getDescription($refund->status) }}</td>
            </tr>
            <tr>
                <td>处理人</td>
                <td>{{ $refund->handler->username ?? null }}</td>
            </tr>
            <tr>
                <td>处理时间</td>
                <td>{{ $refund->handled_at}}</td>
            </tr>
            <tr>
                <td>反馈信息</td>
                <td>{{ $refund->handled_reason}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
