var queTime = setInterval(function () {
    // 获取时间
    var $demo = $('#secondNum');
    var $num = $demo.html();
    if ($num * 1 <= 0) {
        alert('考试时间到！');
        clearTimeout(queTime);
    } else {
        $demo.html($num * 1 - 1);
    }
}, 1000);
