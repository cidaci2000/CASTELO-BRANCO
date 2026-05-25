<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Processar agendamento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao'])) {
    header('Content-Type: application/json');
    
    if ($_POST['acao'] == 'agendar') {
        $cliente_nome = trim($_POST['cliente_nome'] ?? '');
        $cliente_email = trim($_POST['cliente_email'] ?? '');
        $cliente_telefone = trim($_POST['cliente_telefone'] ?? '');
        $servico = $_POST['servico'] ?? '';
        $data = $_POST['data'] ?? '';
        $horario = $_POST['horario'] ?? '';
        $usuario_id = $_SESSION['usuario_id'];
        
        if (empty($cliente_nome) || empty($cliente_email) || empty($cliente_telefone) || empty($servico) || empty($data) || empty($horario)) {
            echo json_encode(['success' => false, 'message' => 'Preencha todos os campos!']);
            exit();
        }
        
        $sql = "INSERT INTO agendamentos (usuario_id, cliente_nome, cliente_email, cliente_telefone, servico, data, horario) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $usuario_id, $cliente_nome, $cliente_email, $cliente_telefone, $servico, $data, $horario);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Agendamento realizado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao agendar. Tente novamente.']);
        }
        $stmt->close();
        exit();
    }
    
    if ($_POST['acao'] == 'cancelar') {
        $agendamento_id = $_POST['agendamento_id'] ?? 0;
        $usuario_id = $_SESSION['usuario_id'];
        
        $sql = "UPDATE agendamentos SET status = 'cancelado' WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $agendamento_id, $usuario_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Agendamento cancelado!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao cancelar.']);
        }
        $stmt->close();
        exit();
    }
}

// Buscar agendamentos
$agendamentos = [];
$sql = "SELECT * FROM agendamentos WHERE usuario_id = ? AND status = 'agendado' ORDER BY data ASC, horario ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbearia Elite - Agendamentos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>💈 Barbearia Elite</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</p>
</header>

<main>
    <form id="formAgendamento">
        <h2>Novo Agendamento</h2>
        
        <input type="text" id="cliente_nome" placeholder="Nome do cliente" required>
        <input type="email" id="cliente_email" placeholder="Email do cliente" required>
        <input type="tel" id="cliente_telefone" placeholder="Telefone (WhatsApp)" required>
        
        <select id="servico" required>
            <option value="">Selecione o serviço</option>
            <option value="corte">💇 Corte de cabelo - R$ 40,00</option>
            <option value="barba">🧔 Barba - R$ 30,00</option>
            <option value="corte_barba">💈 Corte + Barba - R$ 60,00</option>
            <option value="combo">⭐ Combo Premium - R$ 80,00</option>
        </select>
        
        <input type="date" id="data" required>
        <input type="time" id="horario" required>
        
        <button type="submit">Agendar</button>
        
        <p><a href="logout.php">Sair do sistema</a></p>
    </form>
    
    <div class="agenda">
        <h2>📅 Agendamentos</h2>
        <ul id="listaAgendamentos">
            <?php if (empty($agendamentos)): ?>
                <li style="text-align: center; color: #888;">Nenhum agendamento encontrado</li>
            <?php else: ?>
                <?php foreach ($agendamentos as $agenda): ?>
                    <li data-id="<?php echo $agenda['id']; ?>">
                        <strong><?php echo htmlspecialchars($agenda['cliente_nome']); ?></strong><br>
                        <div class="servico-info">
                            📞 <?php echo htmlspecialchars($agenda['cliente_telefone']); ?><br>
                            ✂️ <?php 
                                $servicos = [
                                    'corte' => 'Corte de cabelo - R$ 40,00',
                                    'barba' => 'Barba - R$ 30,00',
                                    'corte_barba' => 'Corte + Barba - R$ 60,00',
                                    'combo' => 'Combo Premium - R$ 80,00'
                                ];
                                echo $servicos[$agenda['servico']] ?? $agenda['servico'];
                            ?>
                        </div>
                        <div class="data-hora">
                            📅 <?php echo date('d/m/Y', strtotime($agenda['data'])); ?> às <?php echo $agenda['horario']; ?>
                        </div>
                        <button onclick="cancelarAgendamento(<?php echo $agenda['id']; ?>)">Cancelar</button>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</main>

<div id="toast"></div>

<script>
const form = document.getElementById('formAgendamento');
const toast = document.getElementById('toast');

// Configurar data mínima (amanhã)
const hoje = new Date();
const amanha = new Date(hoje);
amanha.setDate(hoje.getDate() + 1);
document.getElementById('data').min = amanha.toISOString().split('T')[0];

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('acao', 'agendar');
    formData.append('cliente_nome', document.getElementById('cliente_nome').value);
    formData.append('cliente_email', document.getElementById('cliente_email').value);
    formData.append('cliente_telefone', document.getElementById('cliente_telefone').value);
    formData.append('servico', document.getElementById('servico').value);
    formData.append('data', document.getElementById('data').value);
    formData.append('horario', document.getElementById('horario').value);
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Erro ao agendar. Tente novamente.', 'error');
    }
});

async function cancelarAgendamento(id) {
    if (!confirm('Tem certeza que deseja cancelar este agendamento?')) return;
    
    const formData = new FormData();
    formData.append('acao', 'cancelar');
    formData.append('agendamento_id', id);
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Erro ao cancelar.', 'error');
    }
}

function showToast(message, type = 'success') {
    toast.textContent = message;
    toast.className = 'show';
    if (type === 'error') {
        toast.classList.add('error');
    }
    
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.remove('error');
    }, 3000);
}
</script>

</body>
</html>