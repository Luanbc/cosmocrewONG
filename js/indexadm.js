document.addEventListener('DOMContentLoaded', function () {


// Menu Responsivo para Telas Menores
const menuIcon = document.getElementById("menu-icon");
const navList = document.querySelector(".navlist");

if (menuIcon && navList) {
    menuIcon.addEventListener("click", function () {
        navList.classList.toggle("active");
    });
}

});