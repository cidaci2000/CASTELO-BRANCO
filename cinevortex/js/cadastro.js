// js/cadastro.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado, iniciando script...');
    
    const form = document.getElementById('cadastroForm');
    
    if (!form) {
        console.error('Formulário não encontrado!');
        return;
    }
    
    console.log('Formulário encontrado, adicionando evento...');
    
    const errorDiv = document.getElementById('errorMessage');
    const successDiv = document.getElementById('successMessage');
    const senhaInput = document.getElementById('senha');
    const confirmarSenhaInput = document.getElementById('confirmar_senha');
    const passwordStrength = document.getElementById('passwordStrength');

    // Função para mostrar mensagem de erro
    function showError(message) {
        console.error('Erro:', message);
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        successDiv.style.display = 'none';
        
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }

    // Função para mostrar mensagem de sucesso
    function showSuccess(message) {
        console.log('Sucesso:', message);
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        errorDiv.style.display = 'none';
        
        setTimeout(() => {
            window.location.href = 'home.php';
        }, 2000);
    }

    // Verificar força da senha
    function checkPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 6) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!]+/)) strength++;
        
        let strengthText = '';
        let strengthColor = '';
        
        switch(strength) {
            case 0:
            case 1:
                strengthText = 'Senha fraca';
                strengthColor = '#ff4444';
                break;
            case 2:
            case 3:
                strengthText = 'Senha média';
                strengthColor = '#ffaa44';
                break;
            case 4:
            case 5:
                strengthText = 'Senha forte';
                strengthColor = '#44ff44';
                break;
        }
        
        if (password.length > 0) {
            passwordStrength.textContent = strengthText;
            passwordStrength.style.color = strengthColor;
        } else {
            passwordStrength.textContent = '';
        }
    }

    // Validar confirmação de senha
    function validatePasswordMatch() {
        const senha = senhaInput.value;
        const confirmar = confirmarSenhaInput.value;
        
        if (confirmar.length > 0 && senha !== confirmar) {
            confirmarSenhaInput.setCustomValidity('As senhas não coincidem');
            confirmarSenhaInput.style.borderColor = '#ff4444';
            return false;
        } else {
            confirmarSenhaInput.setCustomValidity('');
            confirmarSenhaInput.style.borderColor = '';
            return true;
        }
    }

    senhaInput.addEventListener('input', function(e) {
        checkPasswordStrength(e.target.value);
        validatePasswordMatch();
    });

    confirmarSenhaInput.addEventListener('input', function() {
        validatePasswordMatch();
    });

    // IMPEDIR O ENVIO TRADICIONAL DO FORMULÁRIO
    form.addEventListener('submit', async function(e) {
        // Previne completamente o envio tradicional
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Formulário submetido, processando...');
        
        // Limpar mensagens
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        // Validar termos
        const aceitoTermos = document.getElementById('aceito_termos');
        if (!aceitoTermos.checked) {
            showError('Você deve aceitar os Termos de Uso');
            return;
        }
        
        // Coletar dados
        const nome = document.getElementById('nome').value.trim();
        const email = document.getElementById('email').value.trim();
        const senha = senhaInput.value;
        const confirmar_senha = confirmarSenhaInput.value;
        
        console.log('Dados coletados:', { nome, email, senha: '***', confirmar_senha: '***' });
        
        // Validações
        if (!nome || !email || !senha) {
            showError('Todos os campos são obrigatórios');
            return;
        }
        
        if (senha !== confirmar_senha) {
            showError('As senhas não coincidem');
            return;
        }
        
        if (senha.length < 6) {
            showError('A senha deve ter no mínimo 6 caracteres');
            return;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('Digite um e-mail válido');
            return;
        }
        
        // Preparar dados
        const formData = {
            nome: nome,
            email: email,
            senha: senha,
            confirmar_senha: confirmar_senha
        };
        
        // Desabilitar botão
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Cadastrando...';
        
        try {
            console.log('Enviando requisição para API...');
            
            const response = await fetch('api/cadastrar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            console.log('Resposta recebida, status:', response.status);
            
            const result = await response.json();
            console.log('Resultado:', result);
            
            if (result.success) {
                showSuccess(result.message);
                form.reset();
            } else {
                showError(result.message);
            }
        } catch (error) {
            console.error('Erro detalhado:', error);
            showError('Erro ao conectar com o servidor. Verifique se o arquivo api/cadastrar.php existe.');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
        }
    });
    
    console.log('Script inicializado com sucesso!');
});