// js/login.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const errorDiv = document.getElementById('errorMessage');
    const successDiv = document.getElementById('successMessage');

    function showError(message) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        successDiv.style.display = 'none';
        
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }

    function showSuccess(message, redirectUrl) {
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        errorDiv.style.display = 'none';
        
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 1500);
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value;
        const lembrar = document.getElementById('lembrar').checked;
        
        if (!email || !senha) {
            showError('Por favor, preencha todos os campos');
            return;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('Digite um e-mail válido');
            return;
        }
        
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Entrando...';
        
        try {
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    senha: senha,
                    lembrar: lembrar
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Redirecionar para a página correta baseada no tipo de usuário
                showSuccess(result.message, result.redirect);
                form.reset();
            } else {
                showError(result.message);
            }
        } catch (error) {
            console.error('Erro:', error);
            showError('Erro ao conectar com o servidor. Tente novamente.');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
        }
    });
});