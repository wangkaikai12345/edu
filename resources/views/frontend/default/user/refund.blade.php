@extends('frontend.default.user.index')
@section('title', '我的退款')

@section('partStyle')
    <link href="{{ asset('dist/my_order/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">
                <!--Title-->
                <h6 class="card-title">我的退款</h6>
                <hr>
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
                            <a type="button" href="{{ route('users.refund') }}"
                               class="btn btn-danger btn-rounded btn-sm waves-effect waves-light mt-4">重置
                            </a>
                        </div>
                    </div>
                </form>
                <div class="row mb-3 mt-3">
                    <div class="col-xl-12">
                        @foreach($refunds as $refund)
                            {{--{{ dd($refund) }}--}}
                            <div class="card">
                                <div class="card-body row text-center">
                                    <div class="col-xl-5 text-left">
                                        <h6 class="font-weight-bold mt-3 mb-3 text-truncate ">{{ $refund['title'] }}</h6>
                                        <p class="text-uppercase font-small text-truncate">订单号：<span>{{ $refund['order']['trade_uuid'] }}</span>
                                        </p>
                                        <p class="text-uppercase font-small">退款金额：<span class="deep-orange-text">
                                            {{ ftoy($refund['refunded_amount']).' 元'}}
                                        </span></p>
                                    </div>
                                    <div class="col-xl-5 text-left">
                                        <p class="text-uppercase font-small text-truncate">退款单号：
                                            <span class="orange-text">
                                                {{ $refund['payment_sn'] }}
                                            </span>
                                        </p>
                                        <p class="text-uppercase font-small">申请时间：<span>{{ $refund['created_at'] }}</span></p>
                                        <p class="text-uppercase font-small">订单状态：<span>{{ render_order_status($refund['status']) }}</span></p>
                                    </div>
                                    <div class="col-xl-2 text-center"
                                         style="flex-direction: column-reverse;display: flex;">
                                        <button type="button"
                                                class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light refund-info"
                                                data-route="{{ route('users.refund.show', $refund) }}"
                                                style="white-space: nowrap;">退款详情</button>

                                        @switch($refund['status'])
                                            @case('refunding')
                                            <form action="{{ route('users.refund.update', $refund) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('PATCH') }}
                                                <button type="submit"
                                                        class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light cancel"
                                                        style="white-space: nowrap;">取消退款</button>
                                            </form>
                                            @break

                                            @default
                                                <form action="{{ route('users.refund.destroy', $refund) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit"
                                                            class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light delete"
                                                            style="white-space: nowrap;">删除退款</button>
                                                </form>

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
                            {!! $refunds->render() !!}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Height Modal Right -->
    <div class="modal fade right" id="refundInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-full-height modal-right" role="document">
            <ul class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title w-100" id="myModalLabel">订单详情</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="refund-body">

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                </div>
            </ul>
        </div>
    </div>
    <!-- Full Height Modal Right -->
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/my_order/js/index.js') }}"></script>
    <script>
        // 退款详情
        $('.refund-info').click(function(){

            edu.ajax({
                url: $(this).data('route'),
                method:'get',
                callback:function(res){
                    if (res.status == 'html') {
                        $('#refund-body').empty();
                        $('#refund-body').append(res.data);
                        $('#refundInfoModal').modal('show');
                    }
                },
                elm: '.refund-info'
            })
            return false;
        })

        // 取消退款
        $('.cancel').click(function(){
            var that = $(this);
            add_modal({
                title: '取消退款',
                content: '您确定要取消这个退款',
                callback: function (res) {
                    if (res.status) {
                        that.parents('form').submit();
                    };
                }
            })
            return false;
        })

        // 删除退款
        $('.delete').click(function(){
            var that = $(this);
            add_modal({
                title: '删除退款',
                content: '您确定要删除这个退款',
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









