document.addEventListener('DOMContentLoaded', function () {

    // Redirecionar para o formulário de adoção
    window.redirecionarFormulario = function (animal_Id) {
        window.location.href = `formadocao.php?animal_id=${animal_Id}`;
    }

});