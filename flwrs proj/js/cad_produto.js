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