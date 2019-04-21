import 'swiper/dist/css/swiper.min.css';
import Swiper from 'swiper/dist/js/swiper.min.js';

window.onload = () => {
    $(() => {
        const bannerNum = $('.banner-swiper-container .swiper-slide').length;
        if (bannerNum >= 1) {
            const bannerSwiper = new Swiper('.banner-swiper-container', {
                loop : true,
                autoplay: true,
                lazy: true,
                pagination: {
                    el: '.banner-swiper-container .swiper-pagination',
                },
                effect : 'fade',
                fadeEffect: {
                    crossFade: true,
                },
                navigation: {
                    nextEl: '.banner-swiper-container .swiper-button-next',
                    prevEl: '.banner-swiper-container .swiper-button-prev',
                },
            });
        }
    })
};