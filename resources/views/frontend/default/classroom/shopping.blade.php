@extends('frontend.default.layouts.app')
@section('title', '班级购买')

@section('style')
    <link href="{{ asset('dist/shopping/css/index.css') }}" rel="stylesheet">
    <style>
        .navbar {
            font-weight: 400;
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="" href="#">首页</a>
                <span class="ml-2">/</span>
            </li>
            <li class="breadcrumb-item">
                <a class="" href="{{ route('classrooms.index') }}">班级中心</a>
                <span class="ml-2">/</span>
            </li>
            <li class="breadcrumb-item active">订单创建</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-body">
            <!-- Horizontal Steppers -->
            <div class="row">
                <div class="col-md-12">
                    <!-- /.Stepers Wrapper -->
                    <ul class="stepper horizontal horizontal-fix linear" id="horizontal-stepper-fix">
                        <li class="step {{ active_class(!$order) }}">
                            <div class="step-title waves-effect waves-dark step-disabled">订单确认</div>
                            <div class="step-new-content">
                                <div class="row">
                                    <!-- Card -->
                                    <div class="card order-card">
                                        <div class="card-body text-center">
                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <img class="card-img-top" src="{{ render_cover($classroom->cover, 'classroom') }}"
                                                         alt="Card image cap">
                                                </div>
                                                <div class="col-xl-5 text-left">
                                                    <h6 class="font-weight-bold mb-3 text-truncate mt-1"> 班级：{{ $classroom['title'] }}</h6>

                                                    <p class="text-uppercase font-small red-text mt-3">价格：
                                                        <span>
                                                            {{ ftoy($classroom['price']) }} 元
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-xl-12">
                                                    <ul class="list-group list-group-flush">

                                                        <li class="list-group-item font-small pt-4 pb-4">
                                                                <span class="float-right red-text">
                                                                    {{ ftoy($classroom['price']) }} 元
                                                                </span>
                                                            <span class="list-title float-right">
                                                                    商品价格
                                                                </span>
                                                        </li>
                                                        <li class="list-group-item font-small pt-4 pb-4">
                                                                <span class="float-right red-text">

                                                                    {{ ftoy($classroom['price']) }} 元
                                                                </span>
                                                            <span class="list-title float-right">
                                                                    应付
                                                                </span>
                                                        </li>
                                                        <li class="list-group-item font-small">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                <div class="step-actions">
                                    <button class="waves-effect waves-dark btn btn-sm btn-primary next-step mr-5"
                                            id="store_order"
                                            data-feedback="createOrder"
                                            data-plan="{{ $classroom['id'] }}"
                                            data-payment="cny"
                                            data-route="{{ route('users.order.store') }}"
                                    >提交订单
                                    </button>
                                </div>
                            </div>
                        </li>
                        <li class="step {{ active_class($order) }}">
                            <div class="step-title waves-effect waves-dark step-disabled">订单支付</div>
                            <div class="step-new-content">
                                <div class="row">
                                    <!-- Card -->
                                    <div class="card order-card">
                                        <div class="card-body text-center">
                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <img class="card-img-top" src="{{ render_cover($classroom->cover, 'classroom') }}"
                                                         alt="Card image cap">
                                                </div>
                                                <div class="col-xl-5 text-left">
                                                    <h6 class="font-weight-bold mb-3 text-truncate mt-1" id="order_title">{{ $order ? $order['title'] : ''}}</h6>

                                                    <p class="text-uppercase font-small red-text mt-3">价格：<span id="order_price">{{ $order ? ($order['currency'] == 'coin' ?  $order['price_amount'].'虚拟币' : ftoy($order['price_amount']).'元'): '' }}</span>
                                                    </p>


                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-xl-12">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item font-small pt-4 pb-4 border-top-0">
                                                            <div class="row">
                                                                <h6 class="float-left mr-5">
                                                                    支付方式
                                                                </h6>
                                                                <span class="float-left">
                                                                        选择合适的支付方式
                                                                    </span>
                                                            </div>
                                                            <div class="row pay-method" id="pay_type">
                                                                @if ($wechat)
                                                                    <div class="col-md-2 p-0 text-left payment" data-val="wechat">
                                                                        <img src="data:image/png;base64,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" alt="">
                                                                    </div>
                                                                @endif
                                                                @if($alipay)
                                                                    <div class="col-md-2 p-0 text-left payment" data-val="alipay">
                                                                        <img src="data:image/png;base64,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" alt="">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item font-small pt-4 pb-4">
                                                                <span class="float-right red-text" id="product_price">
                                    {{ $order ? ($order['currency'] == 'coin' ?  $order['pay_amount'].'虚拟币' : ftoy($order['pay_amount']).'元'): '' }}
                                                                </span>
                                                            <span class="list-title float-right">
                                                                    商品价格
                                                                </span>
                                                        </li>
                                                        <li class="list-group-item font-small pt-4 pb-4">
                                                                <span class="float-right red-text" id="pay_price">
                                    {{ $order ? ($order['currency'] == 'coin' ?  $order['pay_amount'].'虚拟币' : ftoy($order['pay_amount']).'元'): '' }}
                                                                </span>
                                                            <span class="list-title float-right">
                                                                    应付
                                                                </span>
                                                        </li>
                                                        <li class="list-group-item font-small">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
                                <div class="step-actions">
                                    <button class="waves-effect waves-dark btn btn-sm btn-primary next-step mr-5"
                                            id="pay_order"
                                            data-feedback="payOrder"
                                            data-id="{{ $order ? $order['id']:'' }}"
                                            data-payment = "{{ $order && $order['currency'] == 'coin' ? 'coin' :'' }}"
                                            data-route="{{ route('pay.store') }}"
                                    >支付订单
                                    </button>
                                </div>
                            </div>
                        </li>
                        <li class="step">
                            <div class="step-title waves-effect waves-dark step-disabled">订单完成</div>
                            <div class="step-new-content text-center">
                                <div class="mt-5">
                                    <i class="fas fa-check fa-4x mb-3 animated rotateIn text-success"></i>
                                    <h6 class="font-small mb-4">订单已完成</h6>
                                    <h6 class="font-small mb-2">您已支付成功</h6>
                                    <h6 class="font-small" style="line-height: 46px;">网页未跳转？请点击这里：</h6>
                                    <h6 class="font-small">
                                        <a href="{{ route('classrooms.show', $classroom) }}" class="btn btn-primary btn-sm">立即去班级学习</a>
                                    </h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.Horizontal Steppers -->
        </div>
    </div>

    <div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-side modal-top-left" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold" id="exampleModalLabel">扫码支付</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="code" class="text-center pt-5 pb-5"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('dist/shopping/js/index.js') }}"></script>
    <script>

        // 创建订单
        function createOrder () {
            var product_id = $('#store_order').data('plan');

            var payment = $('#store_order').data('payment');

            if (!product_id || !payment) {
                edu.toastr.error('参数错误！'); $('#horizontal-stepper-fix').destroyFeedback(); return false;
            }

            // product_type', 'product_id', 'coupon_code', 'currency'
            edu.ajax({
                url: $('#store_order').data('route'),
                method:'post',
                data:{
                    'product_type':'classroom',
                    'product_id':product_id,
                    'currency':payment,
                },
                callback:function(res) {
                    if (res.status == 'success') {

                        var order = res.data.order;
                        // 标题
                        $('#order_title').html(order.title);

                        // 价格
                        $('#order_price').html(order.price_amount / 100 + '元');
                        $('#pay_price').html(order.pay_amount / 100 + '元');
                        $('#product_price').html(order.pay_amount / 100 + '元');

                        // 支付方式
                        if (res.data.wechat) {
                            $('#pay_type').append(`
                             <div class="col-md-2 p-0 text-left payment" data-val="wechat">
                            <img src="data:image/png;base64,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" alt="">
                            </div>`)
                        }
                        if (res.data.alipay) {
                            $('#pay_type').append(`
                             <div class="col-md-2 p-0 text-left payment" data-val="alipay">
                            <img src="data:image/png;base64,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" alt="">
                            </div>`)
                        }

                        $('#pay_order').data('id', order.id);

                        $('#horizontal-stepper-fix').nextStep();
                    }
                },
                elm: '#store_order'
            })
        }

        // 选择支付方式
        $(document).on('click', '.payment', function () {
            $('.payment').removeClass('active');
            $(this).addClass('active');
            var value = $(this).attr('data-val');
            $('#pay_order').attr('data-payment', value)
        });

        // 支付订单
        function payOrder() {

            var order_id = $('#pay_order').data('id');
            var payment = $('#pay_order').data('payment');

            if (!order_id || !payment) {
                edu.toastr.error('请选择支付方式');  $('#horizontal-stepper-fix').destroyFeedback(); return false;
            }

            edu.ajax({
                url: $('#pay_order').data('route'),
                method: 'post',
                data: {
                    'order_id':order_id,
                    'payment':payment,
                },
                callback:function(res){

                    if (res.status === 'success') {

                        if (res.data.type === 'qr_code') {
                            $('#code').html('').qrcode(res.data.code);

                            $('#basicExampleModal').modal('show');

                            $('#horizontal-stepper-fix').destroyFeedback();
                        }

                    }
                }
            });
        }

        // 关闭支付模态框
        $('#basicExampleModal').on('hidden.bs.modal', function (e) {
            //去掉定时器的方法
            window.clearInterval(t1);
        })

        // 开启支付模态框
        $('#basicExampleModal').on('show.bs.modal', function (e) {

            //循环执行，每隔3秒钟执行一次 1000
            t1 = window.setInterval(checkStatus, 3000);
        })

        // 查询订单状态
        function checkStatus() {

            var id = $('#pay_order').data('id');

            edu.ajax({
                url: '/my/order/'+id,
                method: 'get',
                data: {},
                callback:function(res){

                    if (res.status === 'success') {
                        if (res.data.status == 'success') {
                            //去掉定时器的方法
                            window.clearInterval(t1);
                            $('#basicExampleModal').modal('hide');
                            $('#horizontal-stepper-fix').nextStep()
                        }
                    }
                },
                disabled_pop: true,
            });
        }

    </script>
@endsection
