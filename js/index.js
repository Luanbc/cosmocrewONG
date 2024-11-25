document.addEventListener('DOMContentLoaded', function () {
    // Carrossel
    var swiper = new Swiper('.swiper-container', {
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Fixar o Header durante o Scroll
    window.addEventListener('scroll', function () {
        const header = document.querySelector('.testeheader');
        const carrossel = document.querySelector('.swiper-container');
        const carrosselHeight = carrossel ? carrossel.offsetHeight : 0;

        if (header) {
            header.classList.toggle('fixed', window.scrollY > carrosselHeight);
        }
    });

});


// Menu Responsivo para Telas Menores
const menuIcon = document.getElementById("menu-icon");
const navList = document.querySelector(".navlist");

if (menuIcon && navList) {
    menuIcon.addEventListener("click", function () {
        navList.classList.toggle("active");
    });
}


