import 'swiper/dist/css/swiper.min.css';
import Swiper from 'swiper/dist/js/swiper.min';

window.onload = function () {
    $("#freeprogress").easyPieChart({
        easing: "easeOutBounce",
        trackColor: "#ebebeb",
        barColor: '#6B91FF',
        scaleColor: !1,
        percent: 10,
        lineWidth: 14,
        size: 145,
        onStep: function(t, e, i) {
            $("#freeprogress canvas").css("height", "146px"),
                $("#freeprogress canvas").css("width", "146px"),
            100 == Math.round(i) && $(this.el).addClass("done"),
                $(this.el).find(".percent").html('<span class="percent">学习进度<br><span class="num">'+ $("#freeprogress").data('percent').toFixed(0) + '%</span></span>')
        }
    });

    var mySwiper = new Swiper ('.swiper-container', {
        direction: 'vertical', // 垂直切换选项
        loop: true, // 循环模式选项
        autoplay: true
    });


};