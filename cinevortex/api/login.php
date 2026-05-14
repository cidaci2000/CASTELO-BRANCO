<?php
// api/login.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados não recebidos']);
    exit;
}

if (empty($data['email']) || empty($data['senha'])) {
    echo json_encode(['success' => false, 'message' => 'E-mail e senha são obrigatórios']);
    exit;
}

try {
    // Verificar conexão com banco
    if (!$pdo) {
        throw new Exception('Erro de conexão com o banco de dados');
    }
    
    // Buscar usuário incluindo o campo 'tipo'
    $stmt = $pdo->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->execute([$data['email']]);
    $usuario = $stmt->fetch();
    
    if (!$usuario || !password_verify($data['senha'], $usuario['senha'])) {
        echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos']);
        exit;
    }
    
    // Iniciar sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Armazenar dados do usuário na sessão
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'];
    $_SESSION['usuario_logado'] = true;
    
    // Determinar página de redirecionamento baseado no tipo
    $redirectPage = ($usuario['tipo'] === 'admin') ? 'admin.php' : 'home.php';
    
    // Implementar "lembrar-me" se necessário
    if (isset($data['lembrar']) && $data['lembrar'] === true) {
        $token = bin2hex(random_bytes(32));
        $expira = time() + (86400 * 30); // 30 dias
        
        // Salvar token no banco (você precisa criar a coluna remember_token)
        $stmt = $pdo->prepare("UPDATE usuarios SET remember_token = ?, token_expira = FROM_UNIXTIME(?) WHERE id = ?");
        $stmt->execute([$token, $expira, $usuario['id']]);
        
        // Salvar token em cookie
        setcookie('remember_token', $token, $expira, '/');
    }
    
    // Retornar sucesso com dados do usuário e página de redirecionamento
    echo json_encode([
        'success' => true,
        'message' => 'Login realizado com sucesso!',
        'redirect' => $redirectPage,
        'usuario' => [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'tipo' => $usuario['tipo']
        ]
    ]);
    
} catch(PDOException $e) {
    error_log("Erro PDO no login: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Erro ao fazer login. Tente novamente mais tarde.'
    ]);
} catch(Exception $e) {
    error_log("Erro geral no login: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>
