<?php
// index.php
session_start();
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Buscar todos os médicos
$query = "SELECT * FROM medicos ORDER BY nome";
$stmt = $db->prepare($query);
$stmt->execute();
$medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Médica - Agendamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .medico-card {
            transition: transform 0.3s;
        }
        .medico-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .medico-foto {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
            margin: 0 auto 15px;
            display: block;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/logo.png" alt="Logo" height="30" class="d-inline-block align-top">
                Clínica Médica
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Médicos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="agendamentos.php">Agendamentos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4 text-center">Nossa Equipe de Médicos</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($medicos as $medico): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 medico-card">
                    <div class="card-body text-center">
                        <img src="uploads/medicos/<?php echo $medico['foto'] ?: 'default-doctor.jpg'; ?>" 
                             alt="Dr(a). <?php echo htmlspecialchars($medico['nome']); ?>" 
                             class="medico-foto"
                             onerror="this.src='uploads/medicos/default-doctor.jpg'">
                        
                        <h5 class="card-title mt-3"><?php echo htmlspecialchars($medico['nome']); ?></h5>
                        <h6 class="card-subtitle mb-3 text-primary">
                            <?php echo htmlspecialchars($medico['especialidade']); ?>
                        </h6>
                        
                        <p class="card-text">
                            <span class="badge bg-secondary mb-2">CRM: <?php echo htmlspecialchars($medico['crm']); ?></span><br>
                            
                            <?php if ($medico['email']): ?>
                                <small class="text-muted d-block">
                                    <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($medico['email']); ?>
                                </small><br>
                            <?php endif; ?>
                            
                            <?php if ($medico['telefone']): ?>
                                <small class="text-muted d-block">
                                    <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($medico['telefone']); ?>
                                </small>
                            <?php endif; ?>
                        </p>
                        
                        <a href="agendar.php?medico_id=<?php echo $medico['id']; ?>" class="btn btn-primary mt-3">
                            Agendar Consulta
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2024 Clínica Médica. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>