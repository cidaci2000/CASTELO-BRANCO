(function() {
    // ===== SIMULAÇÃO CARRINHO (badge) =====
    const badge = document.getElementById('cartCountBadge');
    if (badge) {
        let count = 0;
        badge.textContent = count;
    }

    // ===== RECADOS PRONTOS =====
    const botoes = document.querySelectorAll('.btn-select');
    const previewContent = document.getElementById('previewContent');

    function updatePreview(text) {
        if (previewContent) {
            previewContent.innerHTML = `<i class="fas fa-quote-left" style="opacity:0.3; font-size:1.2rem; display:block; margin-bottom:0.3rem;"></i>“${text}”`;
        }
        // feedback visual no botão (remove seleção anterior)
        botoes.forEach(btn => btn.classList.remove('selected'));
    }

    botoes.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const msg = this.getAttribute('data-message');
            if (msg) {
                updatePreview(msg);
                this.classList.add('selected');
                // opcional: preencher o textarea também?
                const textarea = document.getElementById('customMessage');
                if (textarea) textarea.value = msg;
            }
        });
    });

    // ===== RECADO PERSONALIZADO =====
    const customTextarea = document.getElementById('customMessage');
    const sendBtn = document.getElementById('sendCustomMsg');

    if (sendBtn && customTextarea) {
        sendBtn.addEventListener('click', function() {
            const msg = customTextarea.value.trim();
            if (msg.length === 0) {
                alert('Por favor, escreva um recado ou escolha um dos prontos.');
                return;
            }
            updatePreview(msg);
            // remove seleção dos botões
            botoes.forEach(btn => btn.classList.remove('selected'));
            // feedback
            this.textContent = 'recado selecionado ✓';
            setTimeout(() => {
                this.textContent = 'usar este recado';
            }, 2000);
        });
    }

    // ===== ATUALIZA PRÉVIA DIGITANDO (opcional) =====
    if (customTextarea) {
        customTextarea.addEventListener('input', function() {
            // não atualiza automático para não conflitar com cliques, mas mostra se estiver vazio
            if (this.value.trim().length === 0) {
            if (previewContent) {
                previewContent.innerHTML = `<i class="fas fa-feather-alt"></i><span>sua mensagem aparecerá aqui</span>`;
            }
            }
        });
    }

    // ===== DEMO: ao clicar em um recado pronto, rola suavemente até o preview =====
    document.querySelectorAll('.btn-select').forEach(btn => {
        btn.addEventListener('click', function() {
                const previewArea = document.querySelector('.custom-message-area');
            if (previewArea) {
                previewArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });

    console.log('🌸 flwrs · palavras em flor carregado');
})();