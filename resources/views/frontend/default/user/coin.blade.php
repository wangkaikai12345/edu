@extends('frontend.default.user.index')
@section('title', '我的虚拟币')

@section('partStyle')
    <link href="{{ asset('dist/my_order/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">
                <!--Title-->
                <h6 class="card-title">
                    虚拟币账户
                    <span style="float:right;color:red">余额:{{ auth('web')->user()['coin'] }}</span>
                </h6>
                <hr>
                <!--Text-->
                <div class="classic-tabs">
                    <div class="row">
                        @foreach($coins as $coin)
                            <div class="col-xl-4 col-md-6 col-12">

                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            {{ $coin['title'] }}
                                            <a href="{{ route('users.coin.shopping', $coin) }}" class="btn btn-primary btn-sm ml-4">充值</a>
                                        </h6>
                                        <hr>
                                        <p class="card-text mb-0">
                                            价格：{{ ftoy($coin['price']) }}元
                                        </p>
                                        <p class="card-text mb-0">
                                            虚拟币:{{ $coin['coin'] }}
                                        </p>
                                        <p class="card-text mb-0">
                                            赠送虚拟币:{{ $coin['extra_coin'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/my_order/js/index.js') }}"></script>
@endsection







