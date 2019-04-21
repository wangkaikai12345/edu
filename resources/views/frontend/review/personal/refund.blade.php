@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/personal/order.css') }}">
@endsection

@section('content')
    <div class="xh_plan_content">
        {{--@include('frontend.review.personal.refund-modal')--}}
        <div class="container">
            <div class="row padding-content">
                @include('frontend.review.personal.navBar')
                <div class="czh_order col-xl-9">
                    <div class="view_head">
                        <div class="view_head_title">
                            <p>退款管理</p>
                        </div>
                        <div class="view_head_form">
                            <form action="" method="get">
                                <div class="form-group">
                                    <input id="searchInp" type="text" class="form-control" name="title" placeholder="请输入商品名搜索" value="{{ old('title', '') }}" required>
                                    <a href="javascript:;" class="clear" onclick="cssClear()"><i class="iconfont">&#xe6f2;</i></a>
                                </div>
                                <div class="searchBtn" style="width:160px; display:inline-flex;">
                                    <button type="submit">查询</button>
                                    <button type="button"><a href="{{ route('users.order') }}">重置</a></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="view_content row">
                        @foreach($refunds as $refund)
                            <div class="order_item">
                                <div class="order_item_title">
                                    <p class="order_title" data-toggle="tooltip" data-placement="top" title="{{ $order['title'] }}">{{ $order['title'] }}</p>
                                    <div class="dropdown">
                                        <button type="button" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            <i class="iconfont">&#xe62b;</i>
                                        </button>
                                        <div class="dropdown-menu">

                                            @switch($refund['status'])
                                                @case('refunding')
                                                <form action="{{ route('users.refund.update', $refund) }}"
                                                      method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('PATCH') }}
                                                    <a
                                                       class="dropdown-item cancel"
                                                    >取消退款</a>
                                                </form>
                                                @break

                                                @default
                                                <form action="{{ route('users.refund.destroy', $refund) }}"
                                                      method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <a type="submit"
                                                       class="dropdown-item delete"
                                                    >删除退款</a>
                                                </form>

                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                                <div class="order_item_content">
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>订单号:</span>
                                                </label>
                                                <div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">
                                                    {{ $refund['order']['trade_uuid'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>退款金额:</span>
                                                </label>
                                                <div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">
                                                    {{ ftoy($refund['refunded_amount']).' 元'}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>退款单号:</span>
                                                </label>
                                                <div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">
                                                    {{ $refund['payment_sn'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>申请时间:</span>
                                                </label>
                                                <div class="data_item_ccc col-md-12 col-xl-9 col-lg-9 row pl-0">
                                                    {{ $refund['created_at'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="data_item">
                                        <div class="data_item_c col-md-12 pl-0 col-11">
                                            <div class="data_item_cc input-group input-group-transparent">
                                                <label class="control-label col-md-2 col-lg-2 col-xl-3 text-left modal-label modal-last-label">
                                                    <span>详细状态:</span>
                                                </label>
                                                <div class="order_status_end data_item_ccc col-xl-6 pl-0">
                                                    {{ render_order_status($refund['status']) }}
                                                </div>
                                                <div class="data_item_details text-right col-xl-3 pl-0">
                                                    <a
                                                            href="javascript:;"
                                                            data-route="{{ route('users.refund.show', $refund->id) }}"
                                                            class="refund-info"
                                                    >详情</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <nav class="pageNumber" aria-label="Page navigation example">
                        {!! $refunds->render() !!}
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- 订单详情model -->
    <div class="modal fade" id="refundInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">退款详情</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="refund-body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function cssClear() {
            // 清空input框内的值
            $('#searchInp').val('');
        }

        // 退款详情的模态框
        $('.refund-info').click(function(){

            $.ajax({
                url: $(this).data('route'),
                type:'get',
                success:function(res){},
                error:function(res){
                    if (res.status == 200) {
                        $('#refund-body').empty();
                        $('#refund-body').append(res.responseText);
                        $('#refundInfoModal').modal('show');
                    }else {
                        alert('查看退款出错');
                    }
                }

            })
            return false;
        })

        // 取消退款
        $('.cancel').click(function(){

            var that = $(this);

            if (confirm("您确定要取消这个退款?")) {
                that.parents('form').submit();
            }
            return false;
        })

        // 删除退款
        $('.delete').click(function(){

            var that = $(this);

            if (confirm("您确定要删除这个退款?")) {
                that.parents('form').submit();
            }
            return false;
        })

    </script>
@endsection