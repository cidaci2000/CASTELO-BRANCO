<?php
// agendar.php (trecho modificado para mostrar a foto)
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$medico_id = $_GET['medico_id'] ?? 0;

// Buscar dados do médico
$query = "SELECT * FROM medicos WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->execute([':id' => $medico_id]);
$medico = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .medico-foto-agendamento {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Clínica Médica</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <img src="uploads/medicos/<?php echo $medico['foto'] ?: 'default-doctor.jpg'; ?>" 
                             alt="Dr(a). <?php echo htmlspecialchars($medico['nome']); ?>" 
                             class="medico-foto-agendamento mb-3"
                             onerror="this.src='uploads/medicos/default-doctor.jpg'">
                        <h5>Agendar Consulta</h5>
                        <h6>Dr(a). <?php echo htmlspecialchars($medico['nome']); ?></h6>
                        <small><?php echo htmlspecialchars($medico['especialidade']); ?> - CRM: <?php echo $medico['crm']; ?></small>
                    </div>
                    <div class="card-body">
                        <!-- Resto do formulário permanece igual -->
                        <form action="agendamentos.php" method="POST">
                            <input type="hidden" name="action" value="create">
                            <input type="hidden" name="medico_id" value="<?php echo $medico_id; ?>">
                            
                            <div class="mb-3">
                                <label for="paciente_nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" id="paciente_nome" name="paciente_nome" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="paciente_telefone" class="form-label">Telefone *</label>
                                <input type="text" class="form-control" id="paciente_telefone" name="paciente_telefone" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="paciente_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="paciente_email" name="paciente_email">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="data_consulta" class="form-label">Data *</label>
                                    <input type="date" class="form-control" id="data_consulta" name="data_consulta" 
                                           min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="hora_consulta" class="form-label">Hora *</label>
                                    <input type="time" class="form-control" id="hora_consulta" name="hora_consulta" 
                                           min="08:00" max="18:00" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="observacoes" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Agendar Consulta</button>
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>