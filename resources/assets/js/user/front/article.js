import 'swiper/dist/css/swiper.min.css';
import Swiper from 'swiper/dist/js/swiper.min';

window.onload = function () {
    var mySwiper = new Swiper ('.swiper-container', {
        direction: 'vertical', // 垂直切换选项
        loop: true, // 循环模式选项
        autoplay: true
    })
};
