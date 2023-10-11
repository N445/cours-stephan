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
    slidesPerView: 3,
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
    spaceBetween: 100,
});
