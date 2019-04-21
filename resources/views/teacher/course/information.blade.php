@extends('teacher.course.course_layout')
@section('course_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/information/index.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
@endsection
@section('course_content')
    @include('teacher.course.courseInformation')
@endsection
@section('course_script')
    <script src="/backstage/global/vendor/cropper/cropper.min.js"></script>
    {{--<script src="/tools/qiniu/plupload_2_1_9.js"></script>--}}
    {{--<script src="/tools/qiniu/qiniu.min.js"></script>--}}
    {{--<script src="/tools/qiniu/upload-qiniu-img.js"></script>--}}
    <script src="/vendor/timepicker/js/bootstrap-datetimepicker.js"></script>
    <script src="/vendor/ueditor/ueditor.config.js"></script>
    <script src="/vendor/ueditor/ueditor.all.js"></script>
    <script src="{{ mix('/js/front/course/information/index.js') }}"></script>


    <script>
        window.onload = function () {
            var ue = UE.getEditor('editor', {
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });
            if ($('#editor1').length) {
                var ue1 = UE.getEditor('editor1', {
                    UEDITOR_HOME_URL: '/vendor/ueditor/',
                    serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
                });
            }

            $('.form_datetime').datetimepicker({
                autoclose: true,
                clearBtn: true, //清除按钮
                todayBtn: false, //今日按钮
                format: "yyyy-mm-dd"
            });

            /**
             * 目标相关的操作, 适应人群相关操作
             */
            $(document).on('click', '.list-target a', function () {
                // 获取总共的数量
                var length = $(this).parent().parent().children().length;
                // 如果数量等于1的时候证明他的子元素已经没有。删除全部的元素
                if (length == 1) {
                    $(this).parent().parent().hide();
                }
                $(this).parent().remove();
            });
            $('#target-add-btn').click(function () {
                // 显示div
                $('#list-target').show();
                var content = $('#target-add-input').val();
                if (content.length > 0) {
                    $('#list-target').append('<p>' + content + '<a href="javascript:;">×</a><input type="hidden" name="goals[]" value="' + content + '"/></p>');
                    $('#target-add-input').val('');
                }
            });

            $('#person-add-btn').click(function () {
                // 显示div
                $('#list-person').show();
                var content = $('#person-add-input').val();
                if (content.length > 0) {
                    $('#list-person').append('<p>' + content + '<a href="javascript:;">×</a><input type="hidden" name="audiences[]" value="' + content + '"/></p>');
                    $('#person-add-input').val('');
                }
            });

            /**
             * 分类相关的select2样式
             */
            $('.select2-selection--single').addClass('select2-selection--single-tt');

            /**
             * 标签相关的select2搜索+多选
             */
            var labels = {!! $labels !!};

            var check = {!! $course->tags !!};
            console.log($("#label"));
            $("#label").select2({
                maximumSelectionSize: 20,
                placeholder: '请输入标签名称',
                createSearchChoice: function (term, data) {
                    if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                        return {id: term, text: term};
                    }
                },
                multiple: true,
                data: labels,
            });

            $.each(check, function (index, ele) {
                // Jason 因為原本資料不存在，所以會被加入，其他則僅是替換
                var option = new Option(ele.name, ele.id, true, true);
                $("#label").append(option).trigger('change');
            });

            $("#label").change(function () {
                var labels = $("#label").select2("data");
                console.log(labels);
                if (labels.length > 0) {
                    $('#labels-input').html('');
                    var str = '';
                    for (var o in labels) {
                        str += '<input type="hidden" name="labels[]" value="' + labels[o].id + '"/>';
                    }
                    $('#labels-input').html(str);
                } else {
                    $('#labels-input').html('<input type="hidden" name="labels[]" value=""/>');
                }
            });

//            $("#label").select2({
//                maximumSelectionSize: 20,
//                placeholder: '请输入标签名称',
//                multiple: true,
//                ajax: {
////                    url: $('#label').data('matchUrl'),
//                    url: 'http://eduplaywk.mamp/manage/label/search',
//                    dataType: 'json',
//                    type: 'get',
//                    quietMillis: 100,
//                    data: function(term, page) {
//                        return {
//                            title: term,
//                        };
//                    },
//                    processResults: function(data) {
//                        var results = [];
//                        if (data.status == 200) {
//                            for (o in data.data) {
//                                results.push({
//                                    id: data.data[o].title,
//                                    title: data.data[o].title,
//                                    classroomId:data.data[o].id
//                                });
//                            }
//                        }
//                        return {results: results};
//                    }
//                },
//            });

        };
    </script>

    <script>

    </script>
@endsection