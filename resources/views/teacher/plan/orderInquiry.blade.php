@extends('teacher.plan.plan_layout')
@section('plan_style')
    <link rel="stylesheet" href="{{ mix('/css/teacher/plan/orderInquiry.css') }}">
@endsection
@section('plan_content')
    @include('teacher.plan.modal.order-detail')
    <div class="czh_order_inquiry_content col-xl-9 col-md-12 col-12 form_content p-0">
        <div class="operation_header">
            <p>订单查询</p>
        </div>
        <div class="operation_content">
            <div class="form_head">
                <form action="" method="get">
                    <div class="form_head_top">
                        <p>创建时间</p>
                        <div class="form-group">
                            <div class="input-group input-group-transparent">
                                <input name="start_at" type="date" value="{{ request()->start_at }}"
                                       class="form-control p-0" placeholder="开始时间">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-transparent">
                                <input name="end_at" type="date" value="{{ request()->end_at }}"
                                       class="form-control p-0" placeholder="结束时间">
                            </div>
                        </div>
                    </div>
                    <div class="form_head_bottom">
                        <p>筛选条件</p>
                        <div class="form-group form_select">
                            <div class="input-group input-group-transparent">
                                <select name="status" class="form-control">
                                    <option value="">订单状态</option>
                                    @foreach(\App\Enums\OrderStatus::toSelectArray() as $key => $value)
                                        <option value="{{ $key }}" {{ request()->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group form_select">
                            <div class="input-group input-group-transparent">
                                <select name="payment" class="form-control">
                                    <option value="">支付方式</option>
                                    @foreach(\App\Enums\Payment::toSelectArray() as $key => $value)
                                        <option value="{{ $key }}" {{ request()->payment == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-transparent">
                                <input name="trade_uuid" type="text" value="{{ request()->trade_uuid }}"
                                       class="form-control pl-2" placeholder="订单号搜索">
                            </div>
                        </div>
                        <div class="form-group form_button">
                            <div class="input-group input-group-transparent">
                                <button type="submit">搜索</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table_con">
                <table class="table_content table table-hover table-cards align-items-center">
                    <thead>
                    <tr>
                        <th scope="col">商品名称</th>
                        <th scope="col">订单状态</th>
                        <th scope="col">商品价格</th>
                        <th scope="col">订单价格</th>
                        <th scope="col">购买者</th>
                        <th scope="col">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($orders->count())
                      @foreach($orders as $order)
                        <tr class="bg-white">
                            <td>{{ $order->title }}</td>
                            <td>{{ \App\Enums\OrderStatus::getDescription($order->status) }}</td>
                            <td>
                              @if ($order->product->is_free)
                                  免费
                                  @else
                                      @if ($order->product->coin_price)
                                            {{ $order->product->is_free }} 虚拟币
                                          @else
                                            {{ ftoy($order->product->price) }} 元
                                          @endif
                                @endif
                            </td>
                            <td>
                                @switch($order->currency)
                                    @case('cny')
                                       {{ ftoy($order->pay_amount) }} 元
                                    @break
                                    @case('coin')
                                      {{ $order->pay_amount.'虚拟币' }}
                                    @break
                                    @case('free')
                                        免费
                                    @break
                                    @default
                                @endswitch
                            </td>
                            <td>{{ $order->user->username }}</td>
                            <td>
                                <button class="btn btn-link detail_btn"
                                        data-toggle="modal" data-target="#modal"
                                        data-url="{{ route('users.order.edit', $order->id) }}"
                                >查看详情</button>
                            </td>
                        </tr>
                      @endforeach
                    @endif
                    </tbody>
                </table>

                <nav class="pageNumber" aria-label="Page navigation example" style="margin:0 auto;">
                    {{ $orders->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </nav>

                {{--△△△△△△△△△△△△ 没有数据的时候显示这个 △△△△△△△△△△△△--}}
                @if (!$orders->count())
                    <div class="no_data_style">
                        <p>暂无订单...</p>
                    </div>
                @endif
                {{--△△△△△△△△△△△△ 没有数据的时候显示这个 △△△△△△△△△△△△--}}
            </div>
        </div>
    </div>
@endsection
@section('plan_script')
@endsection
