<?php
session_start();
require_once 'config.php';

// Proteção: Apenas administradores (ou verificação de sessão básica)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Lógica de Cancelamento via Admin (AJAX)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao'])) {
    header('Content-Type: application/json');
    
    if ($_POST['acao'] == 'deletar') {
        $id = $_POST['id'] ?? 0;
        $sql = "DELETE FROM agendamentos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Registro removido com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover registro.']);
        }
        $stmt->close();
        exit();
    }
}

// 1. Buscar métricas para os cards
$metrica_total = $conn->query("SELECT COUNT(*) as total FROM agendamentos")->fetch_assoc()['total'];
$metrica_pendentes = $conn->query("SELECT COUNT(*) as total FROM agendamentos WHERE status = 'agendado'")->fetch_assoc()['total'];

// 2. Buscar todos os agendamentos para a tabela
$sql = "SELECT a.*, u.nome as barbeiro 
        FROM agendamentos a 
        LEFT JOIN usuarios u ON a.usuario_id = u.id 
        ORDER BY a.data DESC, a.horario DESC";
$result = $conn->query($sql);
$agendamentos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Barbearia Elite</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos específicos para o Dashboard que complementam seu style.css */
        .admin-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; border-bottom: 4px solid #d4af37; }
        .stat-card h3 { font-size: 14px; color: #666; text-transform: uppercase; margin-bottom: 10px; }
        .stat-card p { font-size: 28px; font-weight: bold; color: #333; }
        
        .admin-table-container { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; background: #f8f9fa; padding: 12px; border-bottom: 2px solid #eee; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-agendado { background: #e3f2fd; color: #1976d2; }
        .badge-cancelado { background: #ffebee; color: #c62828; }
        .btn-delete { background: #ff4757; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
        .btn-delete:hover { background: #ff6b81; }
    </style>
</head>
<body>

<header>
    <h1>💈 Painel Administrativo</h1>
    <p>Gestão Geral do Sistema</p>
</header>

<div class="admin-container">
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total de Agendamentos</h3>
            <p><?php echo $metrica_total; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pendentes</h3>
            <p><?php echo $metrica_pendentes; ?></p>
        </div>
        <div class="stat-card">
            <h3>Barbeiros Ativos</h3>
            <p><?php echo $conn->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0]; ?></p>
        </div>
    </div>

    <div class="admin-table-container">
        <h2>📋 Todos os Agendamentos</h2>
        <table>
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Cliente</th>
                    <th>Serviço</th>
                    <th>Responsável</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agendamentos as $row): ?>
                <tr id="row-<?php echo $row['id']; ?>">
                    <td><?php echo date('d/m/Y', strtotime($row['data'])) . " - " . $row['horario']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['cliente_nome']); ?></strong><br>
                        <small><?php echo htmlspecialchars($row['cliente_telefone']); ?></small>
                    </td>
                    <td><?php echo ucfirst($row['servico']); ?></td>
                    <td><?php echo htmlspecialchars($row['barbeiro'] ?? 'N/A'); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $row['status']; ?>">
                            <?php echo strtoupper($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn-delete" onclick="deletarRegistro(<?php echo $row['id']; ?>)">Excluir</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p style="text-align: center; margin-top: 20px;">
        <a href="app.php" style="color: #d4af37; text-decoration: none; font-weight: bold;">← Voltar ao App</a>
    </p>
</div>

<div id="toast"></div>

<script>
async function deletarRegistro(id) {
    if (!confirm('Deseja excluir permanentemente este agendamento?')) return;
    
    const formData = new FormData();
    formData.append('acao', 'deletar');
    formData.append('id', id);
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            document.getElementById(`row-${id}`).style.display = 'none';
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Erro ao excluir registro.', 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'show';
    if (type === 'error') toast.classList.add('error');
    setTimeout(() => { toast.className = ''; }, 3000);
}
</script>

</body>
</html>