document.addEventListener('DOMContentLoaded', function () {
   // Botão de Voltar para o Topo
   const scrollToTopBtn = document.getElementById("scrollToTopBtn");
   if (scrollToTopBtn) {
       window.onscroll = function () {
           scrollToTopBtn.style.display = (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? "block" : "none";
       };

       scrollToTopBtn.addEventListener('click', function () {
           window.scrollTo({
               top: 0,
               left: 0,
               behavior: 'smooth', // Rolagem suave
           });
       });
   }

   // Menu Responsivo para Telas Menores
   const menuIcon = document.getElementById("menu-icon");
   const navList = document.querySelector(".navlist");

   if (menuIcon && navList) {
       menuIcon.addEventListener("click", function () {
           navList.classList.toggle("active");
       });
   }


});