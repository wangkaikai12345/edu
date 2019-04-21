@extends('teacher.plan.plan_layout')
@section('plan_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/information/index.css') }}">
@endsection
@section('plan_content')
    @include('teacher.plan.notice')
@endsection
@section('plan_script')
    <script src="{{ '/vendor/timepicker/js/bootstrap-datetimepicker.js' }}"></script>
    <script src="{{ '/vendor/ueditor/ueditor.config.js' }}"></script>
    <script src="{{ '/vendor/ueditor/ueditor.all.js' }}"></script>
    <script src="{{ mix('/js/front/course/information/index.js') }}"></script>
    <script>
        window.onload = function () {
            // var ue = UE.getEditor('editor', {
            //     UEDITOR_HOME_URL: '/vendor/ueditor/',
            //     serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            // });
            // if($('#editor1').length) {
            //     var ue1 = UE.getEditor('editor1', {
            //         UEDITOR_HOME_URL: '/vendor/ueditor/',
            //         serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            //     });
            // }
            //
            // $('.form_datetime').datetimepicker({
            //     autoclose: true,
            //     clearBtn: true, //清除按钮
            //     todayBtn: false, //今日按钮
            //     format: "yyyy-mm-dd"
            // });

            /**
             * 删除版本
             */
            $('.notice-del-btn').click(function () {
                if (confirm('确定要删除这个公告吗?')) {
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'delete',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status == 200) {
                                edu.alert('success', res.message);
                                window.location.reload();
                            } else {
                                edu.alert('danger', '删除公告失败!');
                            }
                        }
                    })
                }

            })
        };
    </script>
@endsection
