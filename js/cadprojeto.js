// Seleciona o botão de toggle e o formulário
const toggleFormBtn = document.getElementById('toggleFormBtn');
const projectForm = document.getElementById('projectForm');

toggleFormBtn.addEventListener('click', () => {
    projectForm.classList.toggle('open'); // Alterna a classe 'open' que controla a visibilidade do formulário
});
