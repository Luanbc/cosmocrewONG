document.addEventListener('DOMContentLoaded', function () {
  
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

    // Validação de Senha
    function validarSenha() {
        const senha = document.getElementById('senha').value;
        const comprimento = document.getElementById('comprimento');
        const maiuscula = document.getElementById('maiuscula');
        const especial = document.getElementById('especial');

        // Verifica o comprimento mínimo da senha
        comprimento.classList.toggle('valid', senha.length >= 8);

        // Verifica se contém letra maiúscula
        maiuscula.classList.toggle('valid', /[A-Z]/.test(senha));

        // Verifica se contém caractere especial
        especial.classList.toggle('valid', /[!@#$%^&*(),.?":{}|<>]/.test(senha));
    }

    // Mostrar/ocultar senha
    function mostrarSenha() {
        const senhaInput = document.getElementById('senha');
        const togglePassword = document.querySelector('.toggle-password');
        const isPassword = senhaInput.type === 'password';

        senhaInput.type = isPassword ? 'text' : 'password';
        togglePassword.textContent = isPassword ? '🙈' : '👁️'; // Troca o ícone de olho
    }

    // Fixar o Header durante o Scroll
    window.addEventListener('scroll', function () {
        const header = document.querySelector('.testeheader');
        const carrossel = document.querySelector('.swiper-container');
        const carrosselHeight = carrossel ? carrossel.offsetHeight : 0;

        if (header) {
            header.classList.toggle('fixed', window.scrollY > carrosselHeight);
        }
    });

    // Menu Responsivo para Telas Menores
    const menuIcon = document.getElementById("menu-icon");
    const navList = document.querySelector(".navlist");

    if (menuIcon && navList) {
        menuIcon.addEventListener("click", function () {
            navList.classList.toggle("active");
        });
    }

    // Redirecionar para o formulário de adoção
    window.redirecionarFormulario = function (animal_Id) {
        window.location.href = `formadocao.php?animal_id=${animal_Id}`;
    };

});


