@extends('frontend.default.user.index')
@section('title', '我的订单')

@section('partStyle')
    <link href="{{ asset('dist/my_order/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">
                <!--Title-->
                <h6 class="card-title">我的订单</h6>
                <hr>
                <!--Text-->
                <form action="" method="get">
                    <div class="row">
                        <div class="col-xl-3 mr-3 col-md-5 col-sm-6">
                            <div class="md-form ml-3">
                                <input type="text" name="title" class="form-control" value="{{ old('title', '') }}"/>
                                <label for="form1">输入商品名搜索</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-5 col-sm-5">
                            <button type="submit"
                                    class="btn btn-primary btn-rounded btn-sm waves-effect waves-light mt-4">查询
                            </button>
                            <a type="button" href="{{ route('users.order') }}"
                                    class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mt-4">重置
                            </a>
                        </div>
                    </div>
                </form>
                <div class="row mb-3 mt-3">
                    <div class="col-xl-12">
                        @foreach($orders as $order)
                            <div class="card">
                                <div class="card-body row text-center">
                                    <div class="col-xl-5 text-left">
                                    <h6 class="font-weight-bold mt-3 mb-3 text-truncate ">{{ $order['title'] }}</h6>
                                    <p class="text-uppercase font-small text-truncate">订单号：<span>{{ $order['trade_uuid'] }}</span>
                                    </p>
                                    <p class="text-uppercase font-small">价格：<span class="deep-orange-text">
                                            {{ $order['currency'] == 'coin' ? $order['price_amount'].' 虚拟币' : ftoy($order['price_amount']).' 元'}}
                                        </span></p>
                                    </div>
                                    <div class="col-xl-5 text-left">
                                        <p class="text-uppercase font-small text-truncate mt-3">优惠码：
                                            <span class="orange-text">
                                                {{ $order['coupon_code'] ? '('.render_coupon_type($order['coupon_type']).')'.$order['coupon_code'] : '无' }}
                                            </span>
                                        </p>
                                        <p class="text-uppercase font-small">创建时间：<span>{{ $order['created_at'] }}</span></p>
                                        <p class="text-uppercase font-small">订单状态：<span>{{ render_order_status($order['status']) }}</span></p>
                                    </div>
                                    <div class="col-xl-2 text-center"
                                         style="flex-direction: column-reverse;display: flex;">
                                        {{--<a href="{{ route('users.order.show', $order['id']) }}">查看订单</a>--}}
                                        <button type="button"
                                                class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light order-info"
                                                data-route="{{ route('users.order.edit', $order) }}"
                                                style="white-space: nowrap;">订单详情</button>

                                        @switch($order['status'])
                                            @case('created')
                                            <a type="button"
                                                    class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light"
                                               @switch($order['product_type'])
                                               @case('plan')
                                               href="{{ route('plans.shopping', [ $order['product']['course_id'], $order['product']['id']]) }}"
                                               @break
                                               @case('recharging')
                                               href="{{ route('users.coin.shopping', $order['product']) }}"
                                               @break
                                               @case('classroom')

                                               @break
                                               @default

                                               @endswitch
                                                    style="white-space: nowrap;">支付订单</a>

                                            <form action="{{ route('users.order.update', $order) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT') }}
                                                <button type="submit"
                                                        class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light cancel"
                                                        style="white-space: nowrap;">取消订单</button>
                                            </form>

                                            @break

                                            @case('paid')

                                            @break

                                            @case('refunding')

                                            @break

                                            @case('refunded')
                                            <button type="button"
                                                    class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light"
                                                    style="white-space: nowrap;">查看退款</button>
                                            @break

                                            @case('closed')
                                            <form action="{{ route('users.order.destroy', $order) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit"
                                                        class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light delete"
                                                        style="white-space: nowrap;">删除订单</button>
                                            </form>

                                            @break

                                            @case('success')
                                                @if($order['refund_deadline'] > now())
                                                <button type="button"
                                                        class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light"
                                                        style="white-space: nowrap;">申请退款</button>
                                                @endif

                                            @break

                                            @case('finished')

                                            @break

                                            @case('refund_disagree')

                                            @break

                                            @default

                                        @endswitch
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <nav aria-label="Page navigation example">
                            {!! $orders->render() !!}
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 订单详情模态框 -->
    <div class="modal fade right" id="orderInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-full-height modal-right" role="document">
            <ul class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title w-100" id="myModalLabel">订单详情</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="order-body">

                </div>
            </ul>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/my_order/js/index.js') }}"></script>
    <script>
        // 订单详情
        $('.order-info').click(function(){

            edu.ajax({
                url: $(this).data('route'),
                method:'get',
                callback:function(res){
                    console.log(res);
                    if (res.status == 'html') {
                        $('#order-body').empty();
                        $('#order-body').append(res.data);
                        $('#orderInfoModal').modal('show');
                    }
                },
                elm: '.order-info'
            })
            return false;
        })

        // 取消订单
        $('.cancel').click(function(){
            var that = $(this);
            add_modal({
                title: '取消订单',
                content: '您确定要取消这个订单',
                callback: function (res) {
                    if (res.status) {
                        that.parents('form').submit();
                    };
                }
            })
            return false;
        })

        // 删除订单
        $('.delete').click(function(){
            var that = $(this);
            add_modal({
                title: '删除订单',
                content: '您确定要删除这个订单',
                callback: function (res) {
                    if (res.status) {
                        that.parents('form').submit();
                    };
                }
            })
            return false;
        })
    </script>
@endsection







