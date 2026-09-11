<?php
require_once 'config.php';

// ============================================
// LOGOUT
// ============================================
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// ============================================
// LOGIN
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_login'])) {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        if ($usuario['status'] !== 'ativo') {
            $_SESSION['erro_login'] = "Usuário pendente ou inativo. Aguarde aprovação!";
            header('Location: index.php');
            exit;
        }
        
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];
        $_SESSION['usuario_status'] = $usuario['status'];
        $_SESSION['usuario_telefone'] = $usuario['telefone'] ?? '';
        
        // Dados extras
        if ($usuario['tipo'] === 'competidor') {
            $stmt2 = $pdo->prepare("SELECT * FROM competidores_detalhes WHERE id = ?");
            $stmt2->execute([$usuario['id']]);
            $det = $stmt2->fetch();
            if ($det) {
                $_SESSION['usuario_data_nascimento'] = $det['data_nascimento'];
                $_SESSION['usuario_categoria'] = $det['categoria'];
            }
        }
        
        if ($usuario['tipo'] === 'representante') {
            $stmt2 = $pdo->prepare("SELECT * FROM representantes_detalhes WHERE id = ?");
            $stmt2->execute([$usuario['id']]);
            $det = $stmt2->fetch();
            if ($det) {
                $_SESSION['usuario_empresa'] = $det['nome_empresa'] ?? '';
            }
        }
        
        $pdo->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?")->execute([$usuario['id']]);
        
        redirecionarDashboard();
    } else {
        $_SESSION['erro_login'] = "Email ou senha inválidos!";
        header('Location: index.php');
        exit;
    }
}

// ============================================
// CADASTRO
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_register'])) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmar = trim($_POST['confirmar_senha'] ?? '');
    $tipo = $_POST['tipo'] ?? 'competidor';
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? null;
    $categoria = $_POST['categoria'] ?? null;
    
    $erros = [];
    if (empty($nome)) $erros[] = 'Nome é obrigatório';
    if (empty($email)) $erros[] = 'Email é obrigatório';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'Email inválido';
    if (strlen($senha) < 6) $erros[] = 'Senha deve ter no mínimo 6 caracteres';
    if ($senha !== $confirmar) $erros[] = 'Senhas não coincidem';
    if ($tipo === 'representante' && empty($telefone)) $erros[] = 'Telefone obrigatório para representantes';
    
    $check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) $erros[] = 'Email já cadastrado';
    
    if (empty($erros)) {
        try {
            $pdo->beginTransaction();
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo, status, telefone) VALUES (?, ?, ?, ?, 'pendente', ?)");
            $stmt->execute([$nome, $email, $senhaHash, $tipo, $telefone]);
            $uid = $pdo->lastInsertId();
            
            if ($tipo === 'representante') {
                $pdo->prepare("INSERT INTO representantes_detalhes (id) VALUES (?)")->execute([$uid]);
            } else {
                $pdo->prepare("INSERT INTO competidores_detalhes (id, data_nascimento, categoria) VALUES (?, ?, ?)")->execute([$uid, $data_nascimento, $categoria]);
            }
            
            $pdo->commit();
            $_SESSION['sucesso_registro'] = "Cadastro realizado! Aguarde aprovação do administrador.";
        } catch(PDOException $e) {
            $pdo->rollBack();
            $_SESSION['erro_login'] = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    } else {
        $_SESSION['erro_login'] = implode(' • ', $erros);
    }
    
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;