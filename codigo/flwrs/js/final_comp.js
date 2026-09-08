(function() {
    // ===== BADGE DO CARRINHO =====
    const badge = document.getElementById('cartCountBadge');
    if (badge) {
        badge.textContent = '3';
    }

    // ===== MÉTODOS DE PAGAMENTO =====
    const paymentOptions = document.querySelectorAll('.payment-option');
    paymentOptions.forEach(option => {
    const radio = option.querySelector('input[type="radio"]');
        if (radio) {
            radio.addEventListener('change', function() {
                paymentOptions.forEach(opt => opt.classList.remove('selected'));
            if (this.checked) {
                option.classList.add('selected');
            }
            });
        }
    });

    // ===== FINALIZAR COMPRA (ambos os botões) =====
    function finalizarCompra() {
        const nome = document.getElementById('nome')?.value.trim();
        const endereco = document.getElementById('endereco')?.value.trim();
        const cidade = document.getElementById('cidade')?.value.trim();

        if (!nome || !endereco || !cidade) {
            alert('Por favor, preencha os campos obrigatórios: Nome, Endereço e Cidade.');
            return;
        }

        const pagamento = document.querySelector('input[name="payment"]:checked');
        const metodo = pagamento ? pagamento.value : 'não selecionado';

        // Simula envio
        const btn = document.getElementById('finalizarBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> processando...';
        btn.disabled = true;

        setTimeout(() => {
            alert('🌸 Pedido finalizado com sucesso!\n\n' +
            'Nome: ' + nome + '\n' +
            'Endereço: ' + endereco + ', ' + cidade + '\n' +
            'Pagamento: ' + metodo.toUpperCase() + '\n\n' +
            'Obrigado por escolher a flwrs! 💐');

            btn.innerHTML = originalText;
            btn.disabled = false;

            // Redirecionar para home (simulado)
            // window.location.href = 'home.php';
        }, 1500);
    }

    const btnFinalizar = document.getElementById('finalizarBtn');
    const btnAside = document.getElementById('finalizarBtnAside');

    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', finalizarCompra);
    }

    if (btnAside) {
        btnAside.addEventListener('click', finalizarCompra);
    }

    // ===== VALIDAÇÃO DO FORM (opcional) =====
    const form = document.getElementById('checkoutForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                finalizarCompra();
        });
    }

    console.log('🌸 flwrs · checkout carregado');
})();