@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/personal/virtualCurrency.css') }}">
@endsection

{{-- 获取后台数据 --}}
@inject('index', 'App\Handlers\IndexHandler')

@section('content')

    <div class="xh_plan_content">
        <div class="container">
            <div class="row padding-content">
                @include('frontend.review.personal.navBar')
                <div class="czh_virtual col-xl-9">
                    <div class="view_head">
                        <div class="view_head_title">
                            <p>虚拟币账户</p>
                        </div>
                        <div class="view_content">
                            <div class="balance">
                                <div class="moneyLogo">
                                    <i class="iconfont">&#xe647;</i>
                                </div>
                                <div class="balance_num">
                                    <p>账户余额</p>
                                    <p>{{ auth('web')->user()->coin }}<span class="company"></span></p>
                                </div>
                            </div>

                            <div class="budget">
                                <div class="income">
                                    <p>总计收入</p>
                                    <p>{{ auth('web')->user()->recharge }}<span class="small_font"></span></p>
                                </div>
                                <div class="expenditure">
                                    <p>总计支出</p>
                                    <p>{{ auth('web')->user()->recharge - auth('web')->user()->coin }}<span class="small_font"></span></p>
                                </div>
                            </div>

                            <div class="recharge">
                                <button type="button" id="charge">充值</button>
                            </div>
                        </div>
                    </div>

                    {{--充值记录--}}
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">名称</th>
                            <th scope="col">订单号</th>
                            <th scope="col">交易时间</th>
                            <th scope="col">收支</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <th scope="row">{{ $order->title }}</th>
                                <td>{{ $order->trade_uuid }}</td>
                                <td>{{ $order->updated_at }}</td>
                                <td>
                                    @if ($order->currency == 'cny')
                                            +{{ ($order->price_amount) * config('app.recharge_proportion') }}
                                        @else
                                            -{{ $order->price_amount }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade czh_modal" id="chargeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">充值虚拟币</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="price_amount">输入充值金额（1元人民币可充值{{ 100 * config('app.recharge_proportion') }}虚拟币）</label>
                            <input type="number" class="form-control" id="price_amount" placeholder="">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">选择支付方式</label>
                            <br>
                            @foreach($index->pay_type() as $key => $pay)
                                @if ($key == 'wechat' && $pay)
                                    <div class="form-group col-md-4 p-0 mr-5">
                                        <div class="pay_mode col-md-12 payment" data-val="wechat">
                                            <img src="/imgs/user/front/order/wechat-pay.png" alt="">
                                        </div>
                                    </div>
                                @endif

                                @if($key == 'alipay' && $pay)
                                    <div class="form-group col-md-4 p-0">
                                        <div class="pay_mode col-md-12 payment" data-val="alipay">
                                            <img src="/imgs/user/front/order/bao-pay.png" alt="">
                                        </div>
                                    </div>
                                @endif

                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-sm btn-primary" id="recharge" data-route="{{ route('pay.coin.store') }}">充值</button>
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
@endsection

@section('script')
    <script src="{{ mix('/js/front/coin/coin.js') }}"></script>
@endsection