@extends('frontend.review.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/plan/header.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/order/payment.css') }}">
@endsection
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="#">首页</a></li>
                <li class="breadcrumb-item active" aria-current="page">订单详情</li>
            </ol>
        </nav>
        <div class="row padding-content second">
            <div class="col-md-12">
                <div class="banner-wrap second">
                    <div class="banner-right">
                        <div class="swiper-container banner-swiper-container">
                            <div class="swiper-wrapper swiper-no-swiping">
                                <div class="swiper-slide">
                                    <div class="col-md-12 row p-0 m-0">
                                        <div class="number_sort_content">
                                            <div class="number_sort">
                                                <div class="number_item active">
                                                    1
                                                </div>
                                                <div class="number_item_text">
                                                    订单确认
                                                </div>
                                            </div>
                                            <div class="number_sort">
                                                <div class="number_item">
                                                    2
                                                </div>
                                                <div class="number_item_text">
                                                    订单支付
                                                </div>
                                            </div>
                                            <div class="number_sort">
                                                <div class="number_item">
                                                    3
                                                </div>
                                                <div class="number_item_text">
                                                    订单完成
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="order_hr">
                                    <div class="col-md-12 row p-0 m-0">
                                        <div class="order_content">
                                            <div class="order_img">
                                                <img src="/imgs/user/front/index/cover.png" alt="">
                                            </div>
                                            <div class="order_desc">
                                                <div class="order_title">
                                                    基于Java的开发微信小程序和公众号小程序和公众号小程序和公众号
                                                </div>
                                                <div class="order_price">
                                                    12000 <span>元</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="order_hr second" style="margin-top:210px;margin-bottom:20px;">
                                    <div class="col-md-12 row p-0 m-0">
                                        <div class="order_discount p-0">
                                            <div class="order_coupon_title">
                                                优惠券
                                                <span>（通过优惠券抵扣金额）</span>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-4 p-0">
                                                    <div class="input-group input-group-transparent">
                                                        <input type="text" class="form-control" id="input-email"
                                                               placeholder="请输入优惠码">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0 col-sm-12 col-lg-5">
                                                    <button class="btn btn-primary item-btn">使用</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 row p-0 m-0 mt-5">
                                        <div class="order_discount p-0">
                                            <div class="order_coupon_title first">
                                                商品价格：
                                                <span class="order-price-content">12000</span>
                                                元
                                            </div>
                                            <div class="order_coupon_title first">
                                                应付金额：
                                                <span class="order-price-content">12000</span>
                                                元
                                            </div>
                                            <button class="swiper-button-next btn btn-primary btn-submit m-0 float-right">
                                                提交订单
                                            </button>
                                        </div>
                                    </div>
                                    {{--<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>--}}
                                </div>
                                <div class="swiper-slide second">
                                    <div class="col-md-12 row p-0 m-0">
                                        <div class="number_sort_content">
                                            <div class="number_sort active">
                                                <div class="number_item">
                                                    <i class="iconfont">
                                                        &#xe6b0;
                                                    </i>
                                                </div>
                                                <div class="number_item_text">
                                                    订单确认
                                                </div>
                                            </div>
                                            <div class="number_sort">
                                                <div class="number_item active">
                                                    2
                                                </div>
                                                <div class="number_item_text">
                                                    订单支付
                                                </div>
                                            </div>
                                            <div class="number_sort">
                                                <div class="number_item">
                                                    3
                                                </div>
                                                <div class="number_item_text">
                                                    订单完成
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="order_hr">
                                    <div class="col-md-12 row p-0 m-0">
                                        <div class="order_content col-md-10 row">
                                            <div class="order_img col-lg-4 col-md-12 col-12">
                                                <img src="/imgs/user/front/index/cover.png" alt="">
                                            </div>
                                            <div class="order_desc information col-lg-8 col-md-12 col-12">
                                                <div class="order_information col-md-12 col-12 row">
                                                    <div class="order_title second col-md-2 text-left">
                                                        订单号
                                                    </div>
                                                    <div class="order_title_desc col-md-10 col-10 p-0">
                                                        ahjbfvahjwbekvaevjhbsdvchafgvhejvbjv
                                                    </div>
                                                </div>
                                                <div class="order_information col-md-12 row">
                                                    <div class="order_title second col-md-2 col-2 text-left">
                                                        订单名称
                                                    </div>
                                                    <div class="order_title_desc col-10 price_left p-0 order_padding">
                                                        基于Java的开发微信小程序和公众号小程序和公众号小程序和公众号
                                                    </div>
                                                </div>
                                                <div class="order_information col-md-12 row">
                                                    <div class="order_title second col-md-2 text-left">
                                                        订单价格
                                                    </div>
                                                    <div class="order_title_desc price_left">
                                                        0.01元
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="order_hr second" style="margin-top:210px;margin-bottom:20px;">
                                    <div class="col-md-12 row p-0 m-0">
                                        <div class="order_discount p-0">
                                            <div class="order_coupon_title">
                                                支付方式
                                                <span>（选择合适的现金支付方式）</span>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-12 col-12 col-lg-3 p-0">
                                                    <div class="pay_mode col-md-12 active">
                                                        <img src="/imgs/user/front/order/wechat-pay.png" alt="">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12 col-12 col-lg-3 p-0">
                                                    <div class="pay_mode bao_pay col-md-12">
                                                        <img src="/imgs/user/front/order/bao-pay.png" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 row p-0 m-0 mt-5">
                                        <div class="order_discount p-0">
                                            <div class="order_coupon_title first">
                                                商品价格：
                                                <span class="order-price-content">0.01</span>
                                                元
                                            </div>
                                            <div class="order_coupon_title first">
                                                优惠金额：
                                                <span class="order-price-content">0</span>
                                                元
                                            </div>
                                            <div class="order_coupon_title first">
                                                应付金额：
                                                <span class="order-price-content text-danger">0.01</span>
                                                元
                                            </div>
                                            <button class="swiper-button-next btn btn-primary btn-submit m-0 float-right">
                                                支付订单
                                            </button>
                                        </div>
                                    </div>
                                    {{--<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>--}}
                                </div>
                                <div class="swiper-slide">
                                    <div class="col-md-12 row p-0 m-0">
                                        <div class="number_sort_content">
                                            <div class="number_sort active">
                                                <div class="number_item">
                                                    <i class="iconfont">
                                                        &#xe6b0;
                                                    </i>
                                                </div>
                                                <div class="number_item_text">
                                                    订单确认
                                                </div>
                                            </div>
                                            <div class="number_sort active">
                                                <div class="number_item">
                                                    <i class="iconfont">
                                                        &#xe6b0;
                                                    </i>
                                                </div>
                                                <div class="number_item_text">
                                                    订单支付
                                                </div>
                                            </div>
                                            <div class="number_sort">
                                                <div class="number_item active">
                                                    3
                                                </div>
                                                <div class="number_item_text">
                                                    订单完成
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="order_hr">
                                    <div class="col-md-12 row p-0 m-0">
                                        <div class="order_pay_content">
                                            <div class="order_pay_img">
                                                <img src="/imgs/user/front/order/order-bg.png" alt="">
                                            </div>
                                            <div class="order_pay_tip">
                                                <div class="order_pay_success">
                                                    <i class="iconfont">
                                                        &#xe6b0;
                                                    </i>
                                                </div>
                                                <div class="order_pay_text">
                                                    订单已经完成
                                                </div>
                                                <div class="order_success_text">
                                                    您已经支付成功，页面将在
                                                    <span>2</span>
                                                    秒后跳转到学习页面
                                                </div>
                                                <button class="btn btn-primary go-study">立即去学习</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>--}}
                                </div>
                            </div>
                            {{--<!-- swiper controls -->--}}
                            {{--<div class="swiper-button-prev"></div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('/js/front/order/payment.js') }}"></script>
@endsection