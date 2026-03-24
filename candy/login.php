<?php
require_once 'config/conexao.php';

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $mensagem = 'Preencha todos os campos!';
        $tipo_mensagem = 'error';
    } else {
        try {
            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("SELECT id, nome, email, tipo, status FROM usuarios WHERE email = ? AND senha = ? AND status = 'ativo'");
            $stmt->execute([$email, md5($senha)]);
            $usuario = $stmt->fetch();
            
            if ($usuario) {
                iniciarSessao();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_tipo'] = $usuario['tipo'];
                
                // Atualizar último acesso
                $stmt_update = $db->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
                $stmt_update->execute([$usuario['id']]);
                
                // Redirecionar
                if ($usuario['tipo'] === 'admin') {
                    header("Location: admin-dashboard.php");
                } elseif ($usuario['tipo'] === 'funcionario') {
                    header("Location: funcionario-dashboard.php");
                } else {
                    header("Location: cliente-dashboard.php");
                }
                exit();
            } else {
                $mensagem = 'Email ou senha incorretos!';
                $tipo_mensagem = 'error';
            }
        } catch (PDOException $e) {
            $mensagem = 'Erro ao fazer login: ' . $e->getMessage();
            $tipo_mensagem = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Candy Love's</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
            padding: 40px;
        }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #ff6b6b; font-size: 2rem; margin-bottom: 15px; }
        .header img { 
            border-radius: 50%; 
            width: 150px; 
            height: 150px; 
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #666; font-weight: bold; }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ffe0e0;
            border-radius: 25px;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }
        .form-group input:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 5px rgba(255,107,107,0.3);
        }
        button {
            width: 100%;
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,107,0.3);
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            display: <?php echo $mensagem ? 'block' : 'none'; ?>;
        }
        .message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .link-text { text-align: center; margin-top: 20px; color: #666; }
        .link-text a { color: #ff6b6b; text-decoration: none; font-weight: bold; }
        .link-text a:hover { text-decoration: underline; }
        @media (max-width: 480px) {
            .container { padding: 25px; }
            .header h1 { font-size: 1.5rem; }
            .header img { width: 100px; height: 100px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Candy Love's</h1>
            <img src="https://i.pinimg.com/736x/40/2b/e4/402be4a859fd33d361fe0992accda514.jpg" alt="Cupcake">
        </div>
        
        <h2 style="text-align: center; color: #ff6b6b; margin-bottom: 20px;">Acessar Conta</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        
        <?php if ($mensagem): ?>
            <div class="message <?php echo $tipo_mensagem; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <div class="link-text">
            Não tem uma conta? <a href="cadastro_simples.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>