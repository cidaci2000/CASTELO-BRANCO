<?php
// login.php - VERSÃO CORRIGIDA (sem redirecionamentos antes do formulário)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Configuração do banco
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}

$erro = '';

// IMPORTANTE: SÓ redirecionar se for POST e login bem sucedido
// NÃO redirecionar se for GET (quando a página está carregando)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos";
    } else {
        try {
            $sql = "SELECT id, nome_completo, email, senha_hash, tipo, status FROM usuarios WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                if (password_verify($senha, $usuario['senha_hash'])) {
                    if ($usuario['status'] !== 'ativo') {
                        $erro = "Conta inativa ou bloqueada";
                    } else {
                        // Login bem sucedido - só AGORA criamos a sessão
                        $_SESSION['usuario_id'] = $usuario['id'];
                        $_SESSION['usuario_nome'] = $usuario['nome_completo'];
                        $_SESSION['usuario_email'] = $usuario['email'];
                        $_SESSION['usuario_tipo'] = $usuario['tipo'] ?? 'usuario';
                        
                        // Redirecionar após login
                        if ($usuario['tipo'] === 'admin') {
                            header('Location: admin.php');
                        } else {
                            header('Location: home.php');
                        }
                        exit;
                    }
                } else {
                    $erro = "Senha incorreta";
                }
            } else {
                $erro = "E-mail não encontrado";
            }
        } catch(PDOException $e) {
            $erro = "Erro no banco: " . $e->getMessage();
        }
    }
}

// Se chegou aqui, mostra o formulário normalmente
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flwrs · Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fefaf5 0%, #f7e9e9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-box {
            background: white;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .logo {
            text-align: center;
            font-size: 32px;
            font-weight: 300;
            margin-bottom: 30px;
        }
        
        .logo strong {
            color: #c0859d;
            font-weight: 500;
        }
        
        .logo small {
            display: block;
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }
        
        h2 {
            text-align: center;
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 10px;
            color: #333;
        }
        
        .sub {
            text-align: center;
            color: #999;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .error {
            background: #ffe0e0;
            border: 1px solid #ffaaaa;
            color: #c44;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #eee;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #c0859d;
            box-shadow: 0 0 0 3px rgba(192,111,139,0.1);
        }
        
        .btn-login {
            width: 100%;
            background: #c0859d;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background: #a5657e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(192,111,139,0.3);
        }
        
        .links {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }
        
        .links a {
            color: #c0859d;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .info {
            background: #e8f0fe;
            border: 1px solid #b8d4f0;
            color: #2c6b9e;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">
            Flwrs <strong>·</strong>
            <small>Flowers that feel like felling</small>
        </div>
        
        <h2>Bem-vindo(a)</h2>
        <div class="sub">faça login para continuar</div>
        
        <?php if ($erro): ?>
            <div class="error">⚠️ <?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>
        
       
        
        <form method="POST" action="">
            <div class="input-group">
                <label>E-MAIL</label>
                <input type="email" name="email" required autofocus>
            </div>
            
            <div class="input-group">
                <label>SENHA</label>
                <input type="password" name="senha" required>
            </div>
            
            <button type="submit" class="btn-login">Entrar</button>
        </form>
        
        <div class="links">
            <a href="cadastro.php">Criar conta</a>
            | 
            <a href="home.php">Voltar ao site</a>
        </div>
        
       
    </div>
</body>
</html>