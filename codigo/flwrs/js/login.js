// login.js - Com validação que NÃO bloqueia o envio
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const emailInput = document.querySelector('input[name="email"]');
    const senhaInput = document.querySelector('input[name="senha"]');
    const errorDiv = document.querySelector('.error');
    
    // Remove erros anteriores ao digitar
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            this.style.borderColor = 'rgba(180, 165, 160, 0.15)';
            if (errorDiv) errorDiv.style.display = 'none';
        });
    }
    
    if (senhaInput) {
        senhaInput.addEventListener('input', function() {
            this.style.borderColor = 'rgba(180, 165, 160, 0.15)';
            if (errorDiv) errorDiv.style.display = 'none';
        });
    }
    
    // Validação antes do envio (mas NÃO bloqueia)
    if (form) {
        form.addEventListener('submit', function(e) {
            const email = emailInput?.value.trim();
            const senha = senhaInput?.value;
            let hasError = false;
            
            if (!email) {
                emailInput.style.borderColor = '#e94e77';
                hasError = true;
            }
            
            if (!senha) {
                senhaInput.style.borderColor = '#e94e77';
                hasError = true;
            }
            
            // Se tiver erro, mostra mensagem mas DEIXA o PHP processar
            // O PHP vai mostrar o erro novamente, mas o JS dá feedback imediato
            if (hasError && errorDiv) {
                errorDiv.textContent = '⚠️ Preencha todos os campos';
                errorDiv.style.display = 'flex';
                // NÃO chamamos e.preventDefault() - o formulário continua
            }
        });
    }
});