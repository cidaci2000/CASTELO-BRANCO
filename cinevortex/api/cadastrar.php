<?php
// api/cadastrar.php - Versão compatível com data_cadastro
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false, 
        'message' => 'Método não permitido. Use POST.'
    ]);
    exit;
}

require_once '../database.php';

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data && !empty($_POST)) {
    $data = $_POST;
}

if (!$data) {
    echo json_encode([
        'success' => false, 
        'message' => 'Dados não recebidos.'
    ]);
    exit;
}

// Validação
if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Todos os campos são obrigatórios'
    ]);
    exit;
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false, 
        'message' => 'E-mail inválido'
    ]);
    exit;
}

if (strlen($data['senha']) < 6) {
    echo json_encode([
        'success' => false, 
        'message' => 'A senha deve ter no mínimo 6 caracteres'
    ]);
    exit;
}

if (isset($data['confirmar_senha']) && $data['senha'] !== $data['confirmar_senha']) {
    echo json_encode([
        'success' => false, 
        'message' => 'As senhas não coincidem'
    ]);
    exit;
}

try {
    if (!$pdo) {
        throw new Exception('Erro de conexão com o banco de dados');
    }
    
    // Verificar se e-mail já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$data['email']]);
    
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => false, 
            'message' => 'E-mail já cadastrado'
        ]);
        exit;
    }
    
    // Hash da senha
    $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
    
    // Inserir usuário (usando data_cadastro)
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, data_cadastro) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$data['nome'], $data['email'], $senhaHash]);
    
    $usuarioId = $pdo->lastInsertId();
    
    // Iniciar sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['usuario_id'] = $usuarioId;
    $_SESSION['usuario_nome'] = $data['nome'];
    $_SESSION['usuario_email'] = $data['email'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Cadastro realizado com sucesso!',
        'usuario' => [
            'id' => $usuarioId,
            'nome' => $data['nome'],
            'email' => $data['email']
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Erro PDO: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Erro no banco de dados. Tente novamente.'
    ]);
} catch (Exception $e) {
    error_log("Erro: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>