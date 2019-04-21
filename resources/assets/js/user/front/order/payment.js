import 'swiper/dist/css/swiper.min.css';
import Swiper from 'swiper/dist/js/swiper.min.js';
import 'jquery.qrcode';

$(function(){

    const bannerNum = $('.banner-swiper-container .swiper-slide').length;
    let bannerSwiper = null;
    let t2 = null;
    let t1 = null;
    let t = 5;

    if (bannerNum >= 1) {
        bannerSwiper = new Swiper('.banner-swiper-container', {
            loop: false,
            // autoplay: true,
            lazy: true,
            pagination: {
                el: '.banner-swiper-container .swiper-pagination',
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true,
            },
            navigation: {
                nextEl: '.banner-swiper-container .swiper-button-next',
                prevEl: '.banner-swiper-container .swiper-button-prev',
            },
        });
    };

    // 使用优惠券
    $('#use_code').click(function () {

        var code = $('#coupon_code').val();

        var product_id = $('#store_order').data('plan');

        if (!product_id) {
            edu.alert('danger', '请选择商品');
            return false;
        }

        if (!code) {
            edu.alert('danger', '请填写优惠码');
            return false;
        }

        // coupon_code','product_type','product_id
        $.ajax({
            url: '/coupon',
            method: 'post',
            data: {
                'product_type': 'plan',
                'product_id': product_id,
                'coupon_code': code,
            },
            success: function (res) {
                if (res.status == '200') {

                    edu.alert('success', '优惠券使用成功');

                    $('#should_pay').html(res.data.pay_amount / 100);
                } else {
                    edu.alert('danger', res.message);
                    $('#coupon_code').val('');
                }
            }
        });

        return false;
    });

    // 创建订单
    $('#store_order').click(function () {

        var product_id = $(this).data('plan');

        var payment = $(this).data('payment');

        var coin_account = $('#coin_account').val();

        var price = $('#plan_price').data('price');

        if (payment == 'coin' && price > coin_account) {
            edu.alert('danger', '虚拟余额不足！');
            return false;
        }

        if (!product_id || !payment) {
            edu.alert('danger', '商品不存在');
            return false;
        }

        var coupon_code = $('#coupon_code').val();

        // 获取购买商品类型
        var goods = $(this).data('goods');
        var data = {};
        data.product_type = goods ? goods : "plan",
            data.product_id = product_id,
            data.currency = payment,
            coupon_code ? data.coupon_code = coupon_code : '';

        // product_type', 'product_id', 'coupon_code', 'currency'
        $.ajax({
            url: $('#store_order').data('route'),
            method: 'post',
            data: data,
            success: function (res) {

                if (res.status == '200') {

                    var order = res.data.order;
                    // 标题
                    $('#order_title').html(order.title);
                    // 订单号
                    $('#order_uuid').html(order.trade_uuid);

                    // 价格
                    if (order.currency == 'cny') {
                        // 订单价格
                        $('#order_price').html(order.price_amount / 100 + '元');
                        // 应付价格
                        $('#pay_price').html(order.pay_amount / 100);
                        // 商品价格
                        $('#product_price').html(order.pay_amount / 100);
                    } else {
                        $('#order_price').html(order.pay_amount + '虚拟币');
                        $('#pay_price').html(order.pay_amount);
                        $('#product_price').html(order.pay_amount);
                    }

                    // 优惠码
                    // $('#coupon').html(order.coupon_code ? order.coupon_code : '无');

                    if (order.currency == 'coin') {

                        $('#pay_order').attr('data-payment', 'coin');

                        $('#pay_type').append(`<div class="form-group col-md-10 col-12 col-lg-3 col-sm-10  p-0">
                                                        <div class="pay_mode col-md-4 col-5 col-lg-9 col-xl-7 col-sm-5 active pl-4 pr-3">
                                                           虚拟币支付
                                                        </div>
                                                    </div>`)
                    } else {
                        if (res.data.wechat) {
                            $('#pay_type').append(`
                            <div class="form-group col-md-3 p-0 mr-5">
                                <div class="pay_mode col-md-12 payment wechat_pay" data-val="wechat">
                                    <img src="/imgs/user/front/order/wechat-pay.png" alt="">
                                </div>
                            </div>
                             `)
                        }
                        if (res.data.alipay) {
                            $('#pay_type').append(`
                             <div class="form-group col-md-3 p-0">
                                <div class="pay_mode col-md-12 payment" data-val="alipay">
                                    <img src="/imgs/user/front/order/bao-pay.png" alt="">
                                </div>
                            </div>
                             `)
                        }
                    }

                    $('#pay_order').data('id', order.id);

                    bannerSwiper.slideNext();

                }
            },

        })

    })

    // 选择支付方式
    $(document).on('click', '.payment', function () {
        $('.payment').removeClass('active');
        $(this).addClass('active');
        var value = $(this).attr('data-val');
        $('#pay_order').attr('data-payment', value)
    });
    // 支付订单
    $('#pay_order').click(function () {

        var order_id = $('#pay_order').data('id');
        var payment = $('#pay_order').attr('data-payment');
        // payment = payment ? payment : 'wechat';

        if (!order_id || !payment) {
            edu.alert('danger', '请选择支付方式');
            return false;
        }

        // 判断是否是微信内
        var isWechat;
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
            isWechat = 'wx';
        } else {
            isWechat = 'mo';
        }

        $.ajax({
            url: $('#pay_order').data('route'),
            method: 'post',
            dataType: 'json',
            data: {
                'order_id': order_id,
                'payment': payment,
                // 'payment': 'wechat',
                'isWechat': isWechat
            },
            success: function (res) {

                if (res.status == '200') {

                    if (res.data.type == 'coin' && res.data.status) {

                        bannerSwiper.slideNext();

                        t2 = setInterval(refer, 1000); //启动1秒定时 
                    }

                    if (res.data.type == 'free' && res.data.status) {

                        bannerSwiper.slideNext();
                        t2 = setInterval(refer, 1000); //启动1秒定时 
                    }

                    if (res.data.type == 'qr_code') {

                        $('#code').html('').qrcode(res.data.code);

                        $('#basicExampleModal').modal('show');

                        if (payment == 'alipay') {
                            $('#exampleModalLabel').text('支付宝扫码支付');
                        }
                        if (payment == 'wechat') {
                            $('#exampleModalLabel').text('微信扫码支付');
                        }
                    }

                    if (res.data.type == 'mweb'){

                        //循环执行，每隔3秒钟执行一次 1000
                        // t1 = window.setInterval(checkStatus, 3000);
                        // alert(navigator.userAgent )
                        //
                        // var issafariBrowser = /Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent);
                        // alert(issafariBrowser);
                        // if(issafariBrowser) {
                        //     window.top.location.href = res.data.mweb_url;
                        //     return;
                        // }

                        $('#pay_iframe').attr('src', res.data.mweb_url);
                    }

                }
            }
        })
    })

    //循环执行，每隔3秒钟执行一次 1000
    // t1 = window.setInterval(checkStatus, 1000);

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

        if (!id) {
            return false;
        }

        $.ajax({
            url: '/my/order/' + id,
            method: 'get',
            data: {},
            success: function (res) {

                if (res.status === '200') {
                    if (res.data.status == 'success') {
                        //去掉定时器的方法
                        clearInterval(t1);
                        // 隐藏支付模态框
                        $('#basicExampleModal').modal('hide');
                        // 跳转下一步
                        bannerSwiper.slideNext();
                        // 开启定时跳转定时器
                        t2 = setInterval(refer, 1000); //启动1秒定时 
                    }
                }
            },
        });
    }

    function refer() {

        if (t == 0) {
            clearInterval(t2);
            location.href = $('#num').data('route'); //#设定跳转的链接地址 
        }
        $('#num').html(t); // 显示倒计时 
        t--; // 计数器递减 
    }
})