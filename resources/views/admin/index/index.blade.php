@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <style>
        .table a {
            text-decoration: none;
        }

        .required {
            color: red;
        }

        td {
            line-height: 36px
        }

        .panel > .table-bordered, .panel > .table-responsive > .table-bordered {
            border: 1px solid #e4eaec;
        !important;
        }
    </style>
@stop
@section('page-title', '今日统计')
@section('content')
    <div class="row">
        <div class="col-md-2">
            <div class="card card-inverse bg-default">
                <div class="card-block">
                    <h4 class="card-title" style="color: rgba(0,0,0,.45); font-weight: 100">登录用户</h4>
                    <p class="card-text" style="color:rgba(0,0,0,.85);font-size:30px;">
                        {{ $todayCount['activeCount'] ??  0}}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-inverse bg-default">
                <div class="card-block">
                    <h4 class="card-title" style="color: rgba(0,0,0,.45); font-weight: 100">活跃用户</h4>
                    <p class="card-text" style="color:rgba(0,0,0,.85);font-size:30px;">
                        {{ $todayCount['loginCount'] ??  0}}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-inverse bg-default">
                <div class="card-block">
                    <h4 class="card-title" style="color: rgba(0,0,0,.45); font-weight: 100">新增用户</h4>
                    <p class="card-text" style="color:rgba(0,0,0,.85);font-size:30px;">
                        {{ $todayCount['userCount'] ??  0}}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-inverse bg-default">
                <div class="card-block">
                    <h4 class="card-title" style="color: rgba(0,0,0,.45); font-weight: 100">新增订单</h4>
                    <p class="card-text" style="color:rgba(0,0,0,.85);font-size:30px;">
                        {{ $todayCount['orderCount'] ??  0}}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-inverse bg-default">
                <div class="card-block">
                    <h4 class="card-title" style="color: rgba(0,0,0,.45); font-weight: 100">新增问答</h4>
                    <p class="card-text" style="color:rgba(0,0,0,.85);font-size:30px;">
                        {{ $todayCount['topicCount'] ??  0}}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-inverse bg-default">
                <div class="card-block">
                    <h4 class="card-title" style="color: rgba(0,0,0,.45); font-weight: 100">新增笔记</h4>
                    <p class="card-text" style="color:rgba(0,0,0,.85);font-size:30px;">
                        {{ $todayCount['noteCount'] ??  0}}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block">订单统计</h3>
                    <div style="float: right;padding-top:25px;padding-right: 20px">
                        <a class="btn btn-sm btn-default"
                           href="{{ route('backstage.index', ['date_type' => 'week']) }}">本周</a>
                        <a class="btn btn-sm btn-default"
                           href="{{ route('backstage.index', ['date_type' => 'month']) }}">本月</a>
                    </div>
                </header>
                <div class="panel-body ">
                    <div class="row">
                        <div class="col-md-7">
                            <div id="main" class="col-md-12" style="height: 400px"></div>
                        </div>
                        <div class="col-md-5">
                            <span style="font-size: 20px;color: #000">
                            课程排行榜
                            </span>
                            <table class="table  table-hover  toggle-circle"
                                   data-paging="false">
                                <thead>
                                <tr>
                                    <th>课程名称</th>
                                    <th>新增用户数</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ranking as $value)
                                    <tr>
                                        <td>{{ $value['course']['title'] }}</td>
                                        <td>{{ $value['value'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('backstage/global/js/echarts/echarts.common.min.js') }}"></script>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        let myChart = echarts.init(document.getElementById('main'));


        myChart.setOption(option = {
            title: {
                text: '订单统计'
            },
            legend: {
                data: ['免费订单', '付费订单']
            },
            tooltip: {
                trigger: 'axis'
            },
            xAxis: {
                data: {!!  json_encode($payOrdersDate)  !!}
            },
            yAxis: {
                splitLine: {
                    show: false
                }
            },

            dataZoom: [{
                startValue: '2014-06-01'
            }, {
                type: 'inside'
            }],
            visualMap: {
                top: 10,
                right: 10,
                pieces: [{
                    gt: 0,
                    lte: 50,
                    color: '#096'
                }, {
                    gt: 50,
                    lte: 100,
                    color: '#ffde33'
                }, {
                    gt: 100,
                    lte: 150,
                    color: '#ff9933'
                }, {
                    gt: 150,
                    lte: 200,
                    color: '#cc0033'
                }, {
                    gt: 200,
                    lte: 300,
                    color: '#660099'
                }, {
                    gt: 300,
                    color: '#7e0023'
                }],
                outOfRange: {
                    color: '#999'
                }
            },
            series: [{
                name: '付费订单',
                type: 'line',
                data:  {!!  json_encode($payOrdersValue)  !!},
                markLine: {
                    silent: true,
                    data: [{
                        yAxis: 50
                    }, {
                        yAxis: 100
                    }, {
                        yAxis: 150
                    }, {
                        yAxis: 200
                    }, {
                        yAxis: 300
                    }]
                }
            }, {
                name: '免费订单',
                type: 'line',
                data:  {!!  json_encode($freeOrdersValue)  !!},
                markLine: {
                    silent: true,
                    data: [{
                        yAxis: 50
                    }, {
                        yAxis: 100
                    }, {
                        yAxis: 150
                    }, {
                        yAxis: 200
                    }, {
                        yAxis: 300
                    }]
                }
            },
            ]
        });
        window.onresize = myChart.resize;
    </script>
@stop







