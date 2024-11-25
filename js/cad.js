document.addEventListener('DOMContentLoaded', function () {

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


// Gerar Estrelas no Fundo
const starContainer = document.querySelector('.stars');
if (starContainer) {
    const numberOfStars = 200;

    for (let i = 0; i < numberOfStars; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        star.style.left = `${Math.random() * 100}vw`; // Posição aleatória horizontal
        star.style.top = `${Math.random() * 100}vh`;   // Posição aleatória vertical
        star.style.animationDelay = `${Math.random() * 2}s`; // Atraso de animação aleatório
        star.style.animationDuration = `${1.5 + Math.random()}s`; // Duração da animação aleatória

        starContainer.appendChild(star);
    }
} else {
    console.log('Elemento ".stars" não encontrado. Nenhuma estrela foi adicionada.');
}


 // Mostrar/ocultar senha
 function mostrarSenha() {
    const senhaInput = document.getElementById('senha');
    const togglePassword = document.querySelector('.toggle-password');
    const isPassword = senhaInput.type === 'password';

    senhaInput.type = isPassword ? 'text' : 'password';
    togglePassword.textContent = isPassword ? '🙈' : '👁️'; // Troca o ícone de olho
}



// Menu Responsivo para Telas Menores
const menuIcon = document.getElementById("menu-icon");
const navList = document.querySelector(".navlist");

if (menuIcon && navList) {
    menuIcon.addEventListener("click", function () {
        navList.classList.toggle("active");
    });
}


