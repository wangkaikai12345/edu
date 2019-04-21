<form id="createuserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLgModal">退款订单审核</h4>
    </div>
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
                <td>退款类型</td>
                <td>{{ $refund->currency }}</td>
            </tr>
            <tr>
                <td>退款人</td>
                <td>{{ $refund->user->username ?? null }}</td>
            </tr>
            <tr>
                <td>申请时间</td>
                <td>{{ $refund->created_at }}</td>
            </tr>
            <tr>
                <td>退款理由</td>
                <td>{{ $refund->reason }}</td>
            </tr>
            <tr>
                <td>退款状态</td>
                <td>{{ \App\Enums\OrderStatus::getDescription($refund->status) }}</td>
            </tr>
            </tbody>
        </table>

        <div class="form-group row">
            <label class="col-md-3 form-control-label" style="padding-left: 25px">
                审核结果
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="inputBasicMale" name="agree" value="1" checked>
                    <label for="inputBasicMale">同意</label>
                </div>
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="inputBasicMale" name="agree" value="0">
                    <label for="inputBasicMale">不同意</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label " style="padding-left: 25px">
                审核说明
            </label>
            <div class="col-md-9">
                <textarea name="handled_reason" class="form-control"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateUserButton">提交</button>
    </div>
</form>

<script>
    (function () {
        formValidationAjax('#createuserForm', '#validateCreateUserButton', {
            agree: {
                validators: {
                    notEmpty: {
                        message: '审核结果不能为空.'
                    },
                }
            },
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.refunds.audit', ['refund' => $refund->id]) }}", 'POST', true, true)
    })();
</script>