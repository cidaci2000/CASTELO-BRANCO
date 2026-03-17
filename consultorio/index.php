<?php

require_once 'config.php';

// Buscar médicos ativos do banco
$sql = "SELECT * FROM medicos WHERE status = 'ativo' ORDER BY nome";
$result = $conn->query($sql);

// Verificar se a consulta foi bem sucedida
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

$medicos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $medicos[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Médica - Sistema de Agendamento</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header>
        <h1>🏥 Clínica Saúde Total</h1>
        <p>Sistema de Cadastro de Médicos e Agendamento de Consultas</p>
    </header>

    <main>
         <!-- Lista de Médicos em Cards -->
        <section class="medicos-section">
            <h2>👨‍⚕️ Médicos Disponíveis</h2>
            
            <?php if (empty($medicos)): ?>
                <div class="sem-medicos">
                    <p>Nenhum médico cadastrado ainda.</p>
                    <p>Use o formulário acima para cadastrar o primeiro médico!</p>
                </div>
            <?php else: ?>
                <div id="medicos-container" class="medicos-grid">
                    <?php foreach ($medicos as $medico): ?>
                        <div class="medico-card" onclick="abrirModal(<?php echo $medico['id']; ?>, 
                            '<?php echo addslashes($medico['nome']); ?>', 
                            '<?php echo addslashes($medico['especialidade']); ?>', 
                            '<?php echo addslashes($medico['crm']); ?>')">
                            <div class="medico-foto">
                                <?php if (!empty($medico['foto'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($medico['foto']); ?>" 
                                         alt="Foto de <?php echo htmlspecialchars($medico['nome']); ?>"
                                         onerror="this.src='img/default-doctor.png'">
                                <?php else: ?>
                                    <div class="foto-placeholder">
                                        <span>👨‍⚕️</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h3><?php echo htmlspecialchars($medico['nome']); ?></h3>
                            <p><strong><?php echo htmlspecialchars($medico['especialidade']); ?></strong></p>
                            <p><?php echo htmlspecialchars($medico['descricao']); ?></p>
                            <p class="crm">CRM: <?php echo htmlspecialchars($medico['crm']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        
       

       
    </main>

    <!-- Modal de Agendamento -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            
            <?php
            // Limpar mensagens do modal que vieram de redirect
            if (isset($_SESSION['modal_message'])) {
                $modal_message_type = $_SESSION['modal_message_type'] ?? 'info';
                echo "<div class='alert alert-{$modal_message_type}'>{$_SESSION['modal_message']}</div>";
                unset($_SESSION['modal_message']);
                unset($_SESSION['modal_message_type']);
            }
            ?>
            
            <div class="modal-doctor-info">
                <div class="modal-doctor-foto" id="modal-foto">
                    <!-- A foto será inserida via JavaScript -->
                </div>
                <div class="modal-doctor-details">
                    <h2 id="modal-nome"></h2>
                    <p id="modal-especialidade"></p>
                    <p id="modal-crm"></p>
                </div>
            </div>
            
            <form id="modal-form" action="processa_agendamento.php" method="POST">
                <input type="hidden" name="medico_id" id="medico-id">
                <input type="text" name="paciente_nome" placeholder="Seu nome completo" required>
                <input type="email" name="paciente_email" placeholder="Seu e-mail" required>
                <input type="text" name="paciente_telefone" id="telefone" placeholder="Telefone para contato" required>
                <input type="date" name="data_consulta" id="data-consulta" required min="<?php echo date('Y-m-d'); ?>">
                <input type="time" name="hora_consulta" id="hora-consulta" required>
                <textarea name="observacoes" placeholder="Observações (opcional)" rows="3"></textarea>
                <button type="submit">Agendar Consulta</button>
            </form>
        </div>
    </div>

    <script>
    // Array com os dados dos médicos para acesso no JavaScript
    const medicosData = <?php echo json_encode($medicos); ?>;
    
    // Função para abrir modal com dados do médico
    function abrirModal(id, nome, especialidade, crm) {
        document.getElementById('medico-id').value = id;
        document.getElementById('modal-nome').textContent = nome;
        document.getElementById('modal-especialidade').textContent = especialidade;
        document.getElementById('modal-crm').textContent = 'CRM: ' + crm;
        
        // Buscar a foto do médico
        const medico = medicosData.find(m => m.id == id);
        const fotoContainer = document.getElementById('modal-foto');
        
        if (medico && medico.foto) {
            fotoContainer.innerHTML = `<img src="uploads/${medico.foto}" alt="Foto de ${nome}" onerror="this.src='img/default-doctor.png'">`;
        } else {
            fotoContainer.innerHTML = '<div class="foto-placeholder"><span>👨‍⚕️</span></div>';
        }
        
        // Limpar campos do formulário
        document.getElementById('modal-form').reset();
        
        // Remover mensagens anteriores
        const alerts = document.querySelectorAll('.modal .alert');
        alerts.forEach(alert => alert.remove());
        
        // Abrir modal
        document.getElementById('modal').style.display = 'flex';
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

    // Máscara para telefone
    document.getElementById('telefone')?.addEventListener('input', function(e) {
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
    });
    </script>
</body>
</html>