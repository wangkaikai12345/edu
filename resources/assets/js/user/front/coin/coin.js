import 'jquery.qrcode';

window.onload = () => {

    let t1 = null;
    // 充值的模态框
    $('#charge').click(function(){

        $('#chargeModal').modal('show');
    })

    // 选择支付方式
    $(document).on('click', '.payment', function () {
        $('.payment').removeClass('active');
        $(this).addClass('active');
        var value = $(this).attr('data-val');
        $('#recharge').attr('data-payment', value);
    });

    // 点击充值
    $('#recharge').click(function(){

        var reg =/(^[-+]?[1-9]\d*(\.\d{1,2})?$)|(^[-+]?[0]{1}(\.\d{1,2})?$)/;
        // 获取充值钱数
        var price_amount = $('#price_amount').val();

        if (!price_amount) {
            edu.alert('danger','请输入充值钱数'); return false;
        }

        // 获取支付类型
        var payment = $(this).data('payment');

        if (!payment) {
            edu.alert('danger', '请选择充值方式');return false;
        }

        var route = $(this).data('route');

        if (!route) {
            edu.alert('danger','充值出错');return false;
        }

        $.ajax({
            url: route,
            method: 'post',
            data: {
                'price_amount':price_amount,
                'payment':payment,
            },
            success:function(res){

                if (res.status == '200') {

                    if (res.data.type == 'qr_code') {

                        if (payment == 'alipay') {
                            $('#exampleModalLabel').html('支付宝扫码支付');
                        }
                        if (payment == 'wechat') {
                            $('#exampleModalLabel').html('微信扫码支付');
                        }

                        $('#code').html('').qrcode(res.data.code);

                        $('#code').attr('data-id', res.data.order_id);

                        $('#basicExampleModal').modal('show');
                    }

                }
            }
        })
    })


    // 关闭支付模态框
    $('#basicExampleModal').on('hidden.bs.modal', function (e) {

        $('#price_amount').val('');

        $('.payment').removeClass('active');

        //去掉定时器的方法
        window.clearInterval(t1);
    })

    $('#chargeModal').on('hidden.bs.modal', function (e) {

        $('#price_amount').val('');

        $('.payment').removeClass('active');
    })

    // 开启支付模态框
    $('#basicExampleModal').on('show.bs.modal', function (e) {

        //循环执行，每隔3秒钟执行一次 1000
        t1 = window.setInterval(checkStatus, 3000);
    })

    // 查询订单状态
    function checkStatus() {

        var id = $('#code').data('id');

        if (!id) {
            return false;
        }

        $.ajax({
            url: '/my/order/'+id,
            method: 'get',
            data: {},
            success:function(res){

                if (res.status === '200') {
                    if (res.data.status == 'success') {
                        //去掉定时器的方法
                        clearInterval(t1);
                        // 隐藏支付模态框
                        $('#basicExampleModal').modal('hide');

                        edu.alert('success','支付成功！');

                        location.reload();
                    }
                }
            },
        });
    }
}