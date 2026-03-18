<?php
// agendamentos.php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Processar novo agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $medico_id = $_POST['medico_id'];
    $paciente_nome = $_POST['paciente_nome'];
    $paciente_telefone = $_POST['paciente_telefone'];
    $paciente_email = $_POST['paciente_email'];
    $data_consulta = $_POST['data_consulta'];
    $hora_consulta = $_POST['hora_consulta'];
    $observacoes = $_POST['observacoes'];

    $query = "INSERT INTO agendamentos (medico_id, paciente_nome, paciente_telefone, paciente_email, 
              data_consulta, hora_consulta, observacoes) 
              VALUES (:medico_id, :paciente_nome, :paciente_telefone, :paciente_email, 
              :data_consulta, :hora_consulta, :observacoes)";
    
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':medico_id' => $medico_id,
        ':paciente_nome' => $paciente_nome,
        ':paciente_telefone' => $paciente_telefone,
        ':paciente_email' => $paciente_email,
        ':data_consulta' => $data_consulta,
        ':hora_consulta' => $hora_consulta,
        ':observacoes' => $observacoes
    ]);

    header('Location: agendamentos.php?success=1');
    exit;
}

// Buscar agendamentos com dados do médico
$query = "SELECT a.*, m.nome as medico_nome, m.especialidade 
          FROM agendamentos a 
          JOIN medicos m ON a.medico_id = m.id 
          ORDER BY a.data_consulta DESC, a.hora_consulta DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Clínica Médica</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Médicos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="agendamentos.php">Agendamentos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Agendamentos</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Agendamento realizado com sucesso!</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Especialidade</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $agendamento): ?>
                    <tr>
                        <td><?php echo $agendamento['id']; ?></td>
                        <td><?php echo htmlspecialchars($agendamento['paciente_nome']); ?></td>
                        <td><?php echo htmlspecialchars($agendamento['medico_nome']); ?></td>
                        <td><?php echo htmlspecialchars($agendamento['especialidade']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($agendamento['data_consulta'])); ?></td>
                        <td><?php echo $agendamento['hora_consulta']; ?></td>
                        <td><?php echo htmlspecialchars($agendamento['paciente_telefone']); ?></td>
                        <td><?php echo htmlspecialchars($agendamento['paciente_email']); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $agendamento['status'] == 'agendado' ? 'primary' : 
                                    ($agendamento['status'] == 'confirmado' ? 'success' : 
                                    ($agendamento['status'] == 'cancelado' ? 'danger' : 'secondary')); 
                            ?>">
                                <?php echo ucfirst($agendamento['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>