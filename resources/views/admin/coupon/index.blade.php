@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/timepicker/css/bootstrap-datetimepicker.css') }}">

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

        .pagination {
            display: inline-flex;
        !important;
        }

        .pagination-body {
            text-align: center;
        }
    </style>
@stop
@section('page-title', '优惠码管理')
@section('content')
    <div class="modal fade " id="createCouponModal" aria-labelledby="createCouponModalLabel" role="dialog"
         tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <form class="modal-content form-horizontal" id="createuserForm" autocomplete="off" action="javaScript:"
                  method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="createCouponModal" onclick="addCoupon()">添加优惠码</h4>
                </div>
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            优惠方式
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <div id="coupon_type">
                                <div class="radio-custom radio-default radio-inline">
                                    <input type="radio" id="discount" name="type" value="discount" checked>
                                    <label for="discount">折扣</label>
                                </div>
                                <div class="radio-custom radio-default radio-inline">
                                    <input type="radio" id="voucher" name="type" value="voucher">
                                    <label for="voucher">代金券</label>
                                </div>
                                <div class="radio-custom radio-default radio-inline">
                                    <input type="radio" id="audition" name="type" value="audition">
                                    <label for="audition">试听券</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" id="coupon_value">
                        <label class="col-md-3 form-control-label">
                            <span id="coupon_title">折扣(%)</span>
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="value" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row" id="audition_deadline" style="display: none;">
                        <label class="col-md-3 form-control-label">
                            试听截止日期
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="audition_deadline" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            生成数量
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="number" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            使用对象
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-5">
                            <div>
                                <div class="radio-custom radio-default radio-inline">
                                    <input type="radio" id="null" name="product_type" value="" checked
                                           onclick="closePlan()">
                                    <label for="null">全部课程</label>
                                </div>
                                <div class="radio-custom radio-default radio-inline">
                                    <input type="radio" id="plan" name="product_type" value="plan"
                                           data-target="#plansModal" data-toggle="modal">
                                    <label for="plan">指定课程</label>
                                </div>
                                <div class="radio-custom radio-default radio-inline">
                                    <input type="radio" id="recharging" name="product_type" value="recharging"
                                           onclick="closePlan()">
                                    <label for="recharging">充值</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-left: -70px;">
                            <div class="alert alert-primary alert-dismissible plan_title" role="alert"
                                 style="display: none;margin-bottom:0px">
                                <button type="button" class="close"
                                        onclick="closePlan()">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <span id="plan_title_content"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            优惠券截止日期
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="expired_at" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            备注
                        </label>
                        <div class="col-md-9">
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="product_id" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="validateCreateUserButton">提交</button>
                </div>
            </form>
        </div>
    </div>

    @include('admin.coupon.course_table')
    @include('admin.coupon.destroy_coupon_table')

    <div class="panel">
        <div class="panel-body container-fluid" style="padding-bottom: 0px">
            <div class="row row-lg">
                <div class="col-xl-12">
                    <!-- Example Tabs -->
                    <div class="example-wrap">
                        <div class="nav-tabs-horizontal" data-plugin="tabs">
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <header class="panel-heading">
                                                <h3 class="panel-title">优惠码搜索:</h3>
                                            </header>
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <form action="{{ route('backstage.coupons.index') }}" method="GET">
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="type">
                                                                <option value="0">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            优惠券类型
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                @foreach($types as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                            @if(request('type') == $key) selected @endif>
                                                                        <font style="vertical-align: inherit;">
                                                                            <font style="vertical-align: inherit;">
                                                                                {{ $value }}
                                                                            </font>
                                                                        </font>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select class="form-control" name="status">
                                                                <option value="0">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">
                                                                            使用状态
                                                                        </font>
                                                                    </font>
                                                                </option>
                                                                @foreach($couponStatus as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                            @if(request('status') == $key) selected @endif>
                                                                        <font style="vertical-align: inherit;">
                                                                            <font style="vertical-align: inherit;">
                                                                                {{ $value }}
                                                                            </font>
                                                                        </font>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <button type="submit" class="btn btn-primary"><font
                                                                    style="vertical-align: inherit;">
                                                                <font style="vertical-align: inherit;">搜索
                                                                </font></font></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xl-12">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block"></h3>
                    <button type="button" class="btn btn-sm btn-outline btn-primary"
                            style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px"
                            data-target="#createCouponModal" data-toggle="modal">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加优惠码
                    </button>
                    <button type="button" class="btn btn-sm btn-outline btn-danger"
                            style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px"
                            data-target="#destroyCouponModal" data-toggle="modal" id="destroyCoupons">
                        <i class="icon wb-minus" aria-hidden="true"></i> 批量撤销
                    </button>
                    <a href="{{ route('backstage.coupons.export') }}" class="btn btn-sm btn-outline btn-primary"
                            style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px">
                       导出优惠券
                    </a>
                </header>
                <div class="panel-body ">
                    <table class="table table-bordered table-hover  toggle-circle"
                           data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
                        <thead>
                        <tr>
                            <th>优惠码</th>
                            <th>类型</th>
                            <th>优惠内容</th>
                            <th>截止日期</th>
                            <th>状态</th>
                            <th>创建者</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($coupons as $coupon)
                            <tr>
                                <td>{{$coupon->code}}</td>
                                <td>{{\App\Enums\CouponType::getDescription($coupon->type)}}</td>
                                <td>
                                    @switch($coupon->type)
                                        @case('discount')
                                        @if(empty($coupon->product_id))
                                            折扣{{$coupon->value}}%&nbsp;-&nbsp;全部课程
                                        @else
                                            折扣{{$coupon->value}}%&nbsp;-&nbsp;{{$coupon->product->course_title}}
                                        @endif
                                        @break
                                        @case('voucher')
                                        @if(empty($coupon->product_id))
                                            抵现{{$coupon->value}}元&nbsp;-&nbsp;全部课程
                                        @else
                                            抵现{{$coupon->value}}元&nbsp;-&nbsp;{{$coupon->product->course_title}}
                                        @endif
                                        @break
                                    @endswitch
                                </td>
                                <td>{{$coupon->expired_at}}</td>
                                <td>{{$coupon->status == 'unused' ? '未使用' : '已使用'}}</td>
                                <td>{{$coupon->user->username ?? ''}}</td>
                                <td>{{$coupon->created_at}}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger"
                                            onclick="deleteCoupon('{{route('backstage.coupons.destroy', ['code' => $coupon->code])}}')">
                                        删除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-body">
                    {{ $coupons->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@stop



@section('script')
    @include('admin.layouts.validation')

    <script src="/vendor/timepicker/js/bootstrap-datetimepicker.js"></script>
    <script>
        $('input[name="expired_at"]').datetimepicker({
            autoclose: true,
            clearBtn: true, //清除按钮
            todayBtn: false, //今日按钮
            format: "yyyy-mm-dd",
            language: "cn",
        });
        $('input[name="audition_deadline"]').datetimepicker({
            autoclose: true,
            clearBtn: true, //清除按钮
            todayBtn: false, //今日按钮
            format: "yyyy-mm-dd",
            language: "cn",
        });

        // 折扣类型选择
        $('#coupon_type input[type="radio"]').click(function () {
            switch ($(this).val()) {
                case 'audition':
                    $('#coupon_value').hide();
                    $('#audition_deadline').show();
                    $('input[name="product_id"]').val(0);
                    break;
                case 'discount':
                    $('#coupon_title').html('折扣(%)')
                    $('#coupon_value').show();
                    $('#audition_deadline').hide();
                    $('input[name="product_id"]').val(0);
                    break;
                case 'voucher':
                    $('#coupon_title').html('代金券(元)')
                    $('#coupon_value').show();
                    $('#audition_deadline').hide();
                    $('input[name="product_id"]').val(0);
                    break;
            }
        });

        // 点击指定课程
        $('#plan').click(function () {
            $("#plansModal .modal-content .table-tbody-content").load('{{ route('backstage.coupons.courses') }}');
        });

        // 点击指定课程
        $('#destroyCoupons').click(function () {
            $("#destroyCouponModal .modal-content .table-tbody-content").load('{{ route('backstage.coupons.batch') }}');
        });

        // 搜索
        $('#search_course_btn').click(function () {
            let title = $('.search-course input[name="title"]').val();
            let status = $('.search-course input[name="status"]').val();
            let url = '{{ route('backstage.coupons.courses') }}';
            if (title !== undefined || title !== null) {
                url = url + '?title=' + title;
            }

            if (status !== undefined || status !== null) {
                status = null
            }

            if (title && status) {
                url = url + '?title=' + title + '&status=' + status;
            }

            if (status) {
                url = url + '?status=' + status;
            }

            $("#plansModal .modal-content .table-tbody-content").load(url);
        })


        // 添加优惠券
        formValidationAjax('#createuserForm', '#validateCreateUserButton', {
            type: {
                validators: {
                    notEmpty: {
                        message: '折扣类型不能为空.'
                    }
                }
            },
            value: {
                validators: {
                    callback: {
                        message: '折扣不能为空.',
                        callback: function (input) {
                            const type = $('input[name="type"]:checked').val();
                            console.log(type)
                            if (type === 'discount' || type === 'voucher') {
                                if (!input) {
                                    return false;
                                }

                                if (type === 'discount' && input > 100) {
                                    return {
                                        valid: false,
                                        message: '折扣最多可达100%'
                                    };
                                }
                                return true;
                            } else {
                                return true;
                            }
                        }
                    }
                }
            },
            number: {
                validators: {
                    notEmpty: {
                        message: '生成数量不能为空.',
                    },
                    integer: {
                        message: '生成数量只能为整数.'
                    }
                }
            },
            product_type: {
                validators: {
                    notEmpty: {
                        message: '使用对象不能为空.',
                    },
                }
            },
            product_id: {
                validators: {
                    callback: {
                        message: '课程不能为空.',
                        callback: function (input) {
                            const value = input.value;
                            const type = $('input[name="product_type"]').val();

                            if (type === 'plan') {
                                if (!value) {
                                    return false;
                                }
                                return true;
                            } else {
                                return true;
                            }
                        }
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.coupons.store') }}", 'POST', true, true, null, false)


        // 清除版本
        function closePlan() {
            $('input[name="product_id"]').val('');
            $('.plan_title').hide();
            $('#plan_title_content').html('');
        }


        // 用户状态操作
        function deleteCoupon(fetchUrl) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确认删除?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});

                            window.location.reload();
                        },
                        error: function (error) {
                            // 获取返回的状态码
                            const statusCode = error.status;

                            // 提示信息
                            let message = null;
                            // 状态码判断
                            switch (statusCode) {
                                case 422:
                                    message = getFormValidationMessage(error.responseJSON.errors);
                                    break;
                                default:
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }
    </script>
@stop








