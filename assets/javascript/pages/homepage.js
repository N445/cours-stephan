import "./../../styles/pages/homepage.scss"
import AOS from 'aos';
import 'aos/dist/aos.css';

import Swiper from 'swiper';
import {Navigation, Pagination, EffectCoverflow} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-coverflow';


AOS.init();

const swiper = new Swiper('.swiper', {
    modules: [Navigation, Pagination, EffectCoverflow],
    centeredSlides: true,
    pagination: {
        el: '.swiper-pagination',
        dynamicBullets: true,
    },
    effect: 'coverflow',
    coverflowEffect: {
        rotate: 15,
        stretch: 20,
        depth: 100,
        modifier: 1.5,
        slideShadows: false,
    },

    // Navigation arrows
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    // And if we need scrollbar
    scrollbar: {
        el: '.swiper-scrollbar',
    },

    slidesPerView: 3,
    spaceBetween: 100,
    breakpoints: {
        // when window width is >= 320px
        0: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        576: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        // when window width is >= 480px
        768: {
            slidesPerView: 1,
            spaceBetween: 30
        },
        // when window width is >= 640px
        992: {
            slidesPerView: 3,
            spaceBetween: 40
        },
        // when window width is >= 640px
        1200: {
            slidesPerView: 3,
            spaceBetween: 100
        },
        // when window width is >= 640px
        1400: {
            slidesPerView: 3,
            spaceBetween: 100
        }
    }
});
