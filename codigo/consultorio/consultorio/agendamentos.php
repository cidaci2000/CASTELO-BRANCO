<?php
// agendamentos.php

require_once 'config.php';

// Buscar agendamentos com dados do médico
$sql = "SELECT a.*, m.nome as medico_nome, m.especialidade 
        FROM agendamentos a 
        JOIN medicos m ON a.medico_id = m.id 
        ORDER BY a.data_consulta DESC, a.hora_consulta DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos - Clínica Médica</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .agendamentos-table {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }
        
        th {
            background: #3498db;
            color: white;
        }
        
        tr:hover {
            background: #f5f6fa;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }
        
        .status-agendado { background: #f39c12; color: white; }
        .status-confirmado { background: #3498db; color: white; }
        .status-realizado { background: #2ecc71; color: white; }
        .status-cancelado { background: #e74c3c; color: white; }
        
        .acoes button {
            padding: 5px 10px;
            margin: 0 2px;
            font-size: 0.9em;
            width: auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>📋 Lista de Agendamentos</h1>
        <p><a href="index.php">← Voltar para página inicial</a></p>
    </header>
    
    <main>
        <div class="agendamentos-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Especialidade</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Status</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['paciente_nome']); ?></td>
                                <td><?php echo htmlspecialchars($row['medico_nome']); ?></td>
                                <td><?php echo htmlspecialchars($row['especialidade']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['data_consulta'])); ?></td>
                                <td><?php echo $row['hora_consulta']; ?></td>
                                <td>
                                    <span class="status status-<?php echo $row['status']; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($row['paciente_telefone']); ?></td>
                                <td class="acoes">
                                    <button onclick="confirmarAgendamento(<?php echo $row['id']; ?>)">✓</button>
                                    <button onclick="cancelarAgendamento(<?php echo $row['id']; ?>)">✗</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px;">
                                Nenhum agendamento encontrado.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <script>
        function confirmarAgendamento(id) {
            if (confirm('Confirmar este agendamento?')) {
                window.location.href = 'atualiza_agendamento.php?id=' + id + '&status=confirmado';
            }
        }
        
        function cancelarAgendamento(id) {
            if (confirm('Cancelar este agendamento?')) {
                window.location.href = 'atualiza_agendamento.php?id=' + id + '&status=cancelado';
            }
        }
    </script>
</body>
</html>

<?php

require_once 'config.php';

// Verificar se os parâmetros foram enviados
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header('Location: agendamentos.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

// Validar status permitidos
$status_permitidos = ['confirmado', 'cancelado', 'realizado'];
if (!in_array($status, $status_permitidos)) {
    $_SESSION['mensagem'] = "Status inválido";
    $_SESSION['mensagem_tipo'] = "error";
    header('Location: agendamentos.php');
    exit;
}

// Atualizar o status
$sql = "UPDATE agendamentos SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Agendamento atualizado com sucesso!";
    $_SESSION['mensagem_tipo'] = "success";
} else {
    $_SESSION['mensagem'] = "Erro ao atualizar agendamento: " . $conn->error;
    $_SESSION['mensagem_tipo'] = "error";
}

$stmt->close();
$conn->close();

header('Location: agendamentos.php');
exit;
?>