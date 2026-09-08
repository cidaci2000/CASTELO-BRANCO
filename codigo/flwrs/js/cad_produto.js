const precoInput = document.getElementById('preco');
        
if (precoInput) {
    precoInput.addEventListener('input', function(e) {
        let valor = e.target.value;
        valor = valor.replace(/[^\d]/g, '');
        if (valor) {
            let numero = (parseInt(valor) / 100).toFixed(2);
            numero = numero.replace('.', ',');
            e.target.value = numero;
        }
    });
}

// Preview da imagem
function previewImagem(input) {
    const preview = document.getElementById('preview');
    const previewImg = document.getElementById('preview-img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        previewImg.src = '#';
    }
}

// Auto-fade para mensagem de sucesso
const successMessage = document.querySelector('.success-message');
if (successMessage) {
    setTimeout(() => {
        successMessage.style.display = 'none';
    }, 3000);
}


//


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