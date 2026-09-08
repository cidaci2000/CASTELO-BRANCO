document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('.material-symbols-outlined');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility_off';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility';
                }
            });
        });


//


function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('.material-symbols-outlined');
    
    if (input.type === 'password') {
    input.type = 'text';
    icon.textContent = 'visibility_off';
    } else {
    input.type = 'password';
    icon.textContent = 'visibility';
    }
}

// Validação de senhas (permanece)
(function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
    const senha = document.getElementById('senha');
    const confirma = document.getElementById('confirma');
    if (senha.value !== confirma.value) {
        e.preventDefault();
        alert('As senhas não coincidem — por favor, verifique.');
    }
    });
})();

// Função para alternar visibilidade da senha
function togglePasswordVisibility(inputId, buttonElement) {
    const input = document.getElementById(inputId);
    const iconSpan = buttonElement.querySelector('span');
    
    if (input.type === 'password') {
    input.type = 'text';
    iconSpan.textContent = 'visibility_off';
    } else {
    input.type = 'password';
    iconSpan.textContent = 'visibility';
    }
}

// Adicionar eventos para todos os botões de toggle
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        if (targetId) {
        togglePasswordVisibility(targetId, this);
        }
    });
    });
});