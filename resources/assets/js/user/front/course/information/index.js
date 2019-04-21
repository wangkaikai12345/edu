window.onload = function () {
    $('.form_datetime').datetimepicker({
        autoclose: true,
        clearBtn: true, //清除按钮
        todayBtn: false, //今日按钮
        format: "dd MM - hh:ii "
    });

    var ue = UE.getEditor('editor', {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
    });

    var ue1 = UE.getEditor('editor1', {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
    });
};