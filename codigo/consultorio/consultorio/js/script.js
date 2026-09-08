// Abrir modal de agendamento
function abrirModal(medicoId) {
    // Buscar dados do médico via AJAX
    fetch(`get_medico.php?id=${medicoId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const medico = data.data;
            
            document.getElementById('modal-nome').textContent = medico.nome;
            document.getElementById('modal-especialidade').textContent = medico.especialidade;
            document.getElementById('modal-crm').textContent = `CRM: ${medico.crm}`;
            document.getElementById('medico-id').value = medico.id;
            
            // Configurar data mínima para hoje
            const hoje = new Date().toISOString().split('T')[0];
            document.getElementById('data-consulta').min = hoje;
            
            // Limpar formulário e mensagens
            document.getElementById('modal-form').reset();
            const alertas = document.querySelectorAll('.modal .alert');
            alertas.forEach(alerta => alerta.remove());
            
            // Abrir modal
            document.getElementById('modal').style.display = 'flex';
        } else {
            alert('Erro ao carregar dados do médico');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao carregar dados do médico');
    });
}

// Fechar modal
function fecharModal() {
    document.getElementById('modal').style.display = 'none';
}

// Fechar modal ao clicar fora
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
        fecharModal();
    }
}

// Validar formulário de agendamento antes de enviar
document.getElementById('modal-form')?.addEventListener('submit', function(e) {
    const data = document.getElementById('data-consulta').value;
    const hora = document.getElementById('hora-consulta').value;
    
    if (!data || !hora) {
        e.preventDefault();
        alert('Por favor, preencha data e hora da consulta');
        return;
    }
    
    // Validar se data não é fim de semana (opcional)
    const dataObj = new Date(data + 'T' + hora);
    const diaSemana = dataObj.getDay();
    
    if (diaSemana === 0 || diaSemana === 6) {
        if (!confirm('Consultas aos sábados e domingos podem ter disponibilidade limitada. Deseja continuar?')) {
            e.preventDefault();
            return;
        }
    }
});

// Máscara para telefone (opcional)
document.addEventListener('input', function(e) {
    if (e.target.name === 'paciente_telefone') {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        
        if (value.length > 6) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5}).*/, '($1) $2');
        } else if (value.length > 0) {
            value = value.replace(/^(\d*)/, '($1');
        }
        
        e.target.value = value;
    }
});