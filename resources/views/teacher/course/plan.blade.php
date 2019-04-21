@extends('teacher.course.course_layout')
@section('course_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/plan/index.css') }}">
    <link rel="stylesheet" href="{{ '/vendor/timepicker/css/bootstrap-datetimepicker.css' }}">
@endsection
@section('course_content')
    @include('teacher.course.plan_list')
@endsection
@section('course_script')
{{--    <script src="{{ mix('/js/front/course/plan/index.js') }}"></script>--}}
<script src="{{ '/vendor/timepicker/js/bootstrap-datetimepicker.js' }}"></script>

<script>
    window.onload = function () {

//        $('.form_datetime').datetimepicker({
//            autoclose: true,
//            clearBtn: true, //清除按钮
//            todayBtn: false, //今日按钮
//            format: "yyyy-mm-dd"
//        });
//
//        $('input[name=expiry_mode]').on({
//            change: function () {
//                if($(this).val() === 'period') {
//                    $('.time_select').addClass('active');
//                }else {
//                    $('.time_select').removeClass('active');
//                }
//            }
//        });

        /**
         * 创建版本
         */
        var createForm = $('#plan-add-form');
        // 请求地址
        var route = $('#plan-add-form').attr('action');

        FormValidator.init(createForm, {
            rules: {
                title: {
                    required: true,
                },
            },
            messages: {
                title: {
                    required: "请输入版本名称！",
                },

            }
        }, function () {
            $.ajax({
                'url': route,
                'method': 'post',
                'data': createForm.serialize(),
                'success': function(res) {
                    if (res.status =='200') {
                        edu.alert('success', '版本创建成功!');

                        window.location.reload();
                    } else {
                        edu.alert('danger', res.message);
                    }
                }
            });

            return false;
        });

        $('.expiry_mode').change(function(){

            switch($(this).val())
            {
                // 时间范围
                case 'period':
                    $('#period').css('display', 'block');
                    $('#valid').css('display', 'none');
                    break;
                // 有效天数
                case 'valid':
                    $('#valid').css('display', 'block');
                    $('#period').css('display', 'none');
                    break;
                // 永久有效
                case 'forever':
                    $('#period').css('display', 'none');
                    $('#valid').css('display', 'none');
                    break;
                default:

            }
        })


        /**
         * 发布版本
         */
        $('.plan-publish').click(function () {
            var url = $(this).data('url');

            if (!url) { edu.alert('danger', '请选择版本'); return false;}
            var status = $(this).data('status');

            if (!status) { edu.alert('danger', '请选择版本'); return false;}

            $.ajax({
                'url': url,
                'type': 'patch',
                'data': {status: status},
                'success': function(res) {
                    if (res.status == '200') {
                        edu.alert('success', '操作成功');
                        window.location.reload();
                    } else {
                        edu.alert('danger', res.message);
                    }
                }
            });

        });

        // 复制的点击
        $('.copy').click(function(){
            var title = $(this).data('title');
            if (!title) { return false;}
            $('#copy_title').html('复制版本《 '+title+' 》为新的版本');
        })

        /**
         * 复制版本
         * @type {void|*|Mixed|jQuery|HTMLElement}
         */

        var copyForm = $('#plan-copy-form');
        // 请求地址
        var routes = $('#plan-copy-form').attr('action');

        FormValidator.init(copyForm, {
            rules: {
                title: {
                    required: true,
                },
            },
            messages: {
                title: {
                    required: "请输入新版本名称！",
                },

            }
        }, function () {
            $.ajax({
                'url': routes,
                'method': 'post',
                'data': copyForm.serialize(),
                'success': function(res) {
                    if (res.status =='200') {
                        edu.alert('success', '版本复制成功!');

                        window.location.reload();
                    } else {
                        edu.alert('danger', res.message);
                    }
                }
            });

            return false;
        });


    }
</script>
@endsection