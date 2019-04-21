@foreach($coupons as $coupon)
    <tr>
        <td>{{ $coupon->batch }}</td>
        <td>{{ $coupon->created_at }}</td>
        <td>  {{$coupon->status == 'unused' ? '未使用' : '已使用'}}</td>
        <td>{{\App\Enums\CouponType::getDescription($coupon->type)}}</td>
        <td>
            <button class="btn btn-sm btn-danger"
                    onclick="deleteCouponBatch('{{route('backstage.coupons.destroy', ['batch' => $coupon->batch])}}')">
                删除
            </button>
        </td>
    </tr>
@endforeach
<tr id="couponPagination">
    <td colspan="5" style="padding-top: 25px;text-align: center">
        <style>
            .pagination {
                display: inline-flex;
            !important;
            }
        </style>
        {{ $coupons->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
    </td>
</tr>
<script src="/backstage/global/vendor/jquery/1.11.3/jquery.min.js"></script>
<script>
    $('#couponPagination  .pagination .page-item .page-link').click(function (e) {
        e.preventDefault();

        // 获取请求的url
        const url = $(this).attr('href');

        // url存在
        if (url) {
            $("#destroyCouponModal .modal-content .table-tbody-content").children().remove();
            $("#destroyCouponModal .modal-content .table-tbody-content").load(url);
        }
    });


    // 用户状态操作
    function deleteCouponBatch(fetchUrl) {
        alertify.theme("bootstrap");
        alertify
            .okBtn("确定")
            .cancelBtn("取消")
            .confirm("确认删除?", function () {
                // 进行AJAX请求
                $.ajax({
                    url: fetchUrl,
                    type: 'DELETE',
                    dataType: 'JSON',
                    data: {"_token": "{{csrf_token()}}"},
                    success: function (response) {
                        // 提示操作成功
                        notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});

                        window.location.reload();
                    },
                    error: function (error) {
                        // 获取返回的状态码
                        const statusCode = error.status;

                        // 提示信息
                        let message = null;
                        // 状态码判断
                        switch (statusCode) {
                            case 422:
                                message = getFormValidationMessage(error.responseJSON.errors);
                                break;
                            default:
                                message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                break;
                        }

                        // 弹出提示
                        notie.alert({'type': 3, 'text': message, 'time': 1.5});
                    }
                });
            }, function () {

            });
    }
</script>