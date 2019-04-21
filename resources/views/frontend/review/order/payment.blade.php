@extends('frontend.review.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/plan/header.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/order/payment.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="/">首页</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">课程列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $course['title'] }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row padding-content second">
            <div class="col-md-12">
                <div class="banner-wrap second">
                    <div class="banner-right">
                        <div class="swiper-container banner-swiper-container">
                            <div class="swiper-wrapper swiper-no-swiping">

                                @if (empty($order))
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
                                    <div class="col-md-12 row p-0 m-0 order_one">
                                        <div class="order_content pt-1">
                                            <div class="order_img">
                                                <img src="{{ render_cover($course['cover'], 'course') }}" alt="">
                                            </div>
                                            <div class="order_desc" style="width: 42%;float: left;margin-left: 50px;">
                                                <div class="order_title">
                                                    {{ $course['title'].'-'.$plan['title'] }}
                                                </div>
                                                <div class="order_price" id="plan_price" data-price="{{ $plan['coin_price'] }}">
                                                    @if ($plan['coin_price'])
                                                        {{ $plan['coin_price'] }} <span>虚拟币</span>
                                                    @else
                                                        {{ ftoy($plan['price']) }} <span>元</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="order_hr second" style="margin-top:210px;margin-bottom:20px;">
                                    <div class="col-md-12 row p-0 m-0">
                                        @if (!$plan['coin_price'])
                                            <div class="order_discount p-0">
                                            <div class="order_coupon_title">
                                                优惠券
                                                <span>（通过优惠券抵扣金额）</span>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-4 p-0">
                                                    <div class="input-group input-group-transparent">
                                                        <input type="text" class="form-control" id="coupon_code"
                                                               placeholder="请输入优惠码">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0 col-sm-12 col-lg-5">
                                                    <button class="btn btn-primary item-btn" id="use_code">使用</button>
                                                </div>
                                            </div>
                                        </div>
                                            @else
                                            <div class="order_discount p-0">
                                                <div class="order_coupon_title">
                                                    虚拟币余额
                                                    <span>（您的账户）</span>
                                                </div>
                                                <div class="col-md-12 row">
                                                    <div class="form-group col-md-4 p-0">
                                                        <div class="input-group input-group-transparent">
                                                            <input
                                                                    type="text" id="coin_account"
                                                                    class="form-control" value="{{ auth('web')->user()['coin'] }}" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-12 row p-0 m-0 mt-5">
                                        <div class="order_discount p-0">
                                            <div class="order_coupon_title first">
                                                商品价格：
                                                @if ($plan['coin_price'])
                                                    <span class="order-price-content">{{ $plan['coin_price'] }}</span> 虚拟币
                                                @else
                                                    <span class="order-price-content">{{ ftoy($plan['price']) }}</span>  元
                                                @endif
                                            </div>
                                            <div class="order_coupon_title first">
                                                应付金额：
                                                @if ($plan['coin_price'])
                                                    <span class="order-price-content">{{ $plan['coin_price'] }}</span> 虚拟币
                                                @else
                                                    <span class="order-price-content" id="should_pay">{{ ftoy($plan['price']) }}</span>  元
                                                @endif
                                            </div>
                                            <button
                                                    class="btn btn-primary btn-submit m-0 float-right"
                                                    data-id="{{ $order ? $order['id']:'' }}"
                                                    id="store_order"
                                                    data-plan="{{ $plan['id'] }}"
                                                    data-payment= "{{ $plan['coin_price'] ? 'coin': 'cny' }}"
                                                    data-route="{{ route('users.order.store') }}"
                                                    data-mobel="{{ is_show_mobile_page() }}"
                                            >
                                                提交订单
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
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
                                        <div class="order_content col-md-12 row pl-0 pr-0 pt-1">
                                            <div class="order_img col-lg-4 col-md-12 col-12 pr-4 pl-4">
                                                <img src="{{ render_cover($course['cover'], 'course') }}" alt="">
                                            </div>
                                            <div class="order_division"></div>
                                            <div class="order_desc information col-lg-5 col-md-12 col-12">
                                                <div class="order_information col-md-12 col-12 row">
                                                    <div class="order_title second text-left">
                                                        订单号
                                                    </div>
                                                    <div class="order_title_desc p-0" id="order_uuid">
                                                        {{ $order ? $order['trade_uuid'] : ''}}
                                                    </div>
                                                </div>
                                                <div class="order_information col-md-12 row">
                                                    <div class="order_title second text-left">
                                                        订单名称
                                                    </div>
                                                    <div class="order_title_desc price_left p-0" id="order_title">
                                                        {{ $order ? $order['title'] : ''}}
                                                    </div>
                                                </div>
                                                <div class="order_information col-md-12 row">
                                                    <div class="order_title second text-left" >
                                                        订单价格
                                                    </div>
                                                    <div class="order_title_desc price_left" id="order_price">
                                                        {{ $order ? ($order['currency'] == 'coin' ?  $order['price_amount'].'虚拟币' : ftoy($order['price_amount']).'元'): '' }}
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
                                                <span>（选择合适的支付方式）</span>
                                            </div>
                                            <div class="col-md-12 row" id="pay_type">

                                                @if ($order && $order['currency'] == 'coin')
                                                    <div class="form-group col-md-10 col-12 col-lg-3 col-sm-10  p-0">
                                                        <div class="pay_mode active pl-4 pr-4 float-left">
                                                           虚拟币支付
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($wechat)
                                                        <div class="form-group col-md-12 col-12 col-lg-3 p-0 mr-3">
                                                            <div class="pay_mode col-md-12 payment wechat_pay active" data-val="wechat">
                                                                <img src="/imgs/user/front/order/wechat-pay.png" alt="">
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($alipay)
                                                        <div class="form-group col-md-12 col-12 col-lg-3 p-0 mr-3">
                                                            <div class="pay_mode col-md-12 payment" data-val="alipay">
                                                                <img src="/imgs/user/front/order/bao-pay.png" alt="">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 row p-0 m-0 mt-5">
                                        <div class="order_discount p-0">
                                            <div class="order_coupon_title first">
                                                商品价格：
                                                @if ($plan['coin_price'])
                                                    <span class="order-price-content" id="product_price">{{ $plan['coin_price'] }} </span>
                                                        虚拟币
                                                @else
                                                    <span class="order-price-content" id="product_price">{{ ftoy($plan['price']) }}</span> 元
                                                @endif
                                            </div>
                                            <div class="order_coupon_title first">
                                                优惠金额：
                                                <span class="order-price-content">0</span>
                                                元
                                            </div>
                                            <div class="order_coupon_title first">
                                                应付金额：
                                                <span class="order-price-content" id="pay_price">
                                                    {{ $order ? ($order['currency'] == 'coin' ?  $order['pay_amount'] : ftoy($order['pay_amount'])): '' }}
                                                </span>
                                                {{ $order ? ($order['currency'] == 'coin' ? '虚拟币' : '元'): '' }}
                                            </div>
                                            <button class="btn btn-primary btn-submit m-0 float-right "
                                                    id="pay_order"
                                                    data-id="{{ $order ? $order['id']:'' }}"
                                                    data-payment = "{{ $order && $order['currency'] == 'coin' ? 'coin' :'wechat' }}"
                                                    data-route="{{ route('pay.store') }}"
                                            >
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
                                                    <span id="num" data-route="{{ route('plans.intro', [$course, $plan]) }}"></span>
                                                    秒后跳转到学习页面
                                                </div>
                                                <button class="btn btn-primary go-study"><a href="{{ route('plans.intro', [$course, $plan]) }}" style="color: #FFFFFF;">立即去学习</a></button>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>--}}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">扫码支付</h5>
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

        <iframe src="" id="pay_iframe" style="display: none;"></iframe>
    </div>
@endsection
@section('script')
{{--    <script type="text/javascript" src="{{ mix('/js/front/order/jweixin-1.4.0.js') }}"></script>--}}
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{ mix('/js/front/order/payment.js') }}"></script>
@endsection