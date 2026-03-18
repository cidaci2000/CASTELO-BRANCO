<?php
// admin/medicos.php
session_start();
require_once '../config/database.php';

// Simples autenticação (você pode implementar um sistema de login real)
$admin_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if (!$admin_logged_in) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$message = '';
$message_type = '';

// Processar formulário de cadastro com upload de foto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $especialidade = $_POST['especialidade'] ?? '';
    $crm = $_POST['crm'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    
    // Processar upload da foto
    $foto_nome = 'default-doctor.jpg'; // foto padrão
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/medicos/';
        
        // Criar diretório se não existir
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nome = uniqid() . '.' . $extensao;
        $caminho_foto = $upload_dir . $foto_nome;
        
        // Verificar tipo de arquivo
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (in_array($_FILES['foto']['type'], $tipos_permitidos)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_foto)) {
                // Foto enviada com sucesso
            } else {
                $foto_nome = 'default-doctor.jpg';
                $message = "Erro ao fazer upload da foto.";
                $message_type = "warning";
            }
        } else {
            $foto_nome = 'default-doctor.jpg';
            $message = "Tipo de arquivo não permitido. Use JPG, PNG ou GIF.";
            $message_type = "warning";
        }
    }

    if ($nome && $especialidade && $crm) {
        try {
            $query = "INSERT INTO medicos (nome, especialidade, crm, email, telefone, foto) 
                      VALUES (:nome, :especialidade, :crm, :email, :telefone, :foto)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':nome' => $nome,
                ':especialidade' => $especialidade,
                ':crm' => $crm,
                ':email' => $email,
                ':telefone' => $telefone,
                ':foto' => $foto_nome
            ]);
            $message = "Médico cadastrado com sucesso!";
            $message_type = "success";
        } catch (PDOException $e) {
            $message = "Erro ao cadastrar médico: " . $e->getMessage();
            $message_type = "danger";
        }
    } else {
        $message = "Preencha todos os campos obrigatórios!";
        $message_type = "warning";
    }
}

// Buscar médicos cadastrados
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
    <title>Administração - Médicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .medico-foto-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #dee2e6;
        }
        .medico-foto-tabela {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin - Clínica Médica</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="medicos.php">Médicos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Gerenciar Médicos</h1>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5>Cadastrar Novo Médico</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome *</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="especialidade" class="form-label">Especialidade *</label>
                                <input type="text" class="form-control" id="especialidade" name="especialidade" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="crm" class="form-label">CRM *</label>
                                <input type="text" class="form-control" id="crm" name="crm" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone">
                            </div>
                            
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto do Médico</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Tamanho máximo: 2MB</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Cadastrar Médico</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5>Médicos Cadastrados</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Especialidade</th>
                                        <th>CRM</th>
                                        <th>Contato</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medicos as $medico): ?>
                                    <tr>
                                        <td>
                                            <img src="../uploads/medicos/<?php echo $medico['foto'] ?: 'default-doctor.jpg'; ?>" 
                                                 alt="Foto do médico" 
                                                 class="medico-foto-tabela"
                                                 onerror="this.src='../uploads/medicos/default-doctor.jpg'">
                                        </td>
                                        <td><?php echo $medico['id']; ?></td>
                                        <td><?php echo htmlspecialchars($medico['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($medico['especialidade']); ?></td>
                                        <td><?php echo htmlspecialchars($medico['crm']); ?></td>
                                        <td>
                                            <?php if ($medico['email']): ?>
                                                <small>📧 <?php echo htmlspecialchars($medico['email']); ?></small><br>
                                            <?php endif; ?>
                                            <?php if ($medico['telefone']): ?>
                                                <small>📞 <?php echo htmlspecialchars($medico['telefone']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>