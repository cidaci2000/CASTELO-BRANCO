    function showMessage(text, isSuccess = true) {
        let toastContainer = document.getElementById('toastMsg');
        const toast = document.createElement('div');
        toast.className = 'toast-msg';
        toast.innerHTML = `<i class="fas fa-flower" style="margin-right: 8px;"></i> ${text}`;
        toastContainer.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    const orderButtons = document.querySelectorAll('.action-btn');
    orderButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const orderId = btn.getAttribute('data-order') || 'pedido';
            showMessage(`🔍 Pedido ${orderId}: ação registrada no painel administrativo.`, true);
        });
    });

    const addBtn = document.getElementById('addArrangementBtn');
    if(addBtn) {
        addBtn.addEventListener('click', () => {
            window.location.href = 'cad_produto.php';
            // showMessage(`✨ Abrir formulário: novo arranjo floral (catálogo atualizado em tempo real).`, true);
        });
    }

    const deliveryBtn = document.getElementById('deliveryBtn');
    if(deliveryBtn) {
        deliveryBtn.addEventListener('click', () => {
            showMessage(`🚚 Logística: Status das entregas atualizados. Motoristas notificados.`, true);
        });
    }

    const reportBtn = document.getElementById('reportBtn');
    if(reportBtn) {
        reportBtn.addEventListener('click', () => {
            showMessage(`📊 Relatório de vendas exportado (últimos 30 dias) — faturamento: R$ 12.480,00.`, true);
        });
    }

    const msgBtn = document.getElementById('msgBtn');
    if(msgBtn) {
        msgBtn.addEventListener('click', () => {
            showMessage(`📬 Central de mensagens: 3 novas avaliações e 2 dúvidas respondidas.`, true);
        });
    }

    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const sectionName = link.innerText;
            showMessage(`🔐 Navegação admin: seção "${sectionName}" em desenvolvimento — dados carregados em breve.`, true);
        });
    });

    const updateSpecificOrder = document.querySelector('button[data-order="FL-1025"]');
    if(updateSpecificOrder) {
        updateSpecificOrder.addEventListener('click', (e) => {
            const statusSpan = document.querySelector('#orders-tbody tr:nth-child(2) .status-badge');
            if(statusSpan && statusSpan.innerText.includes('Preparando')) {
                statusSpan.innerText = 'Em rota';
                statusSpan.classList.remove('status-pending');
                statusSpan.classList.add('status-badge');
                showMessage(`✅ Pedido #FL-1025 atualizado para "Em rota" — cliente será notificado.`, true);
            } else if (statusSpan) {
                showMessage(`⚠️ Status atual: ${statusSpan.innerText}. Utilize edição avançada.`, true);
            }
        });
    }

    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, idx) => {
        card.addEventListener('click', () => {
            if(idx === 0) showMessage(`📈 Detalhamento: 147 pedidos (+12%) — ticket médio R$ 195,00`, true);
            if(idx === 1) showMessage(`🌿 Estoque atual: 34 variedades, alerta de reposição para 2 itens (girassol e lavanda).`, true);
            if(idx === 2) showMessage(`👥 Base de clientes: 892 ativos, 63 novos este mês. Fidelidade em alta.`, true);
        });
    });