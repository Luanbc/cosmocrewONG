document.addEventListener('DOMContentLoaded', function () {
    window.toggleEditForm = function () {
        var form = document.getElementById('edit-form');
        var editButton = document.getElementById('edit-button');
        
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            editButton.innerText = 'Cancelar Edição';
        } else {
            form.style.display = 'none';
            editButton.innerText = 'Editar Perfil';
        }
    };
});
