document.addEventListener('DOMContentLoaded', function () {

    window.copyPixKey = function() {
        // Obtém o valor da chave PIX da tag <p> com o id 'pix-key'
        const pixKey = document.getElementById('pix-key').innerText;

        // Copia o valor para a área de transferência
        navigator.clipboard.writeText(pixKey).then(() => {
            alert("Chave PIX copiada!");
        }).catch((err) => {
            console.error("Erro ao copiar chave PIX: ", err);
        });
    }

// Função para mostrar/ocultar detalhes de investimento
    window.toggleDetails = function(element) {
        const details = element.querySelector('.details');
        const icon = element.querySelector('.plus-icon');

        const isVisible = details.style.display === 'block';
        details.style.display = isVisible ? 'none' : 'block';
        icon.textContent = isVisible ? '+' : '-';
    }

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


});
