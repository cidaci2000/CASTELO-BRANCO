<?php
require_once 'config.php';

$erro = '';
$mensagem = '';

// Verificar se veio de logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    $mensagem = 'Logout realizado com sucesso!';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos.';
    } else {
        $stmt = $conn->prepare("SELECT id, nome, email, senha, tipo, status FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verificar se usuário está ativo
            if ($user['status'] != 'ativo') {
                $erro = 'Usuário inativo. Entre em contato com o administrador.';
            } else {
                // Verificar a senha
                if (password_verify($senha, $user['senha'])) {
                    // Login bem sucedido
                    $_SESSION['usuario_id'] = $user['id'];
                    $_SESSION['usuario_nome'] = $user['nome'];
                    $_SESSION['usuario_email'] = $user['email'];
                    $_SESSION['usuario_tipo'] = $user['tipo'];
                    $_SESSION['usuario_logado'] = true;
                    
                    // Redirecionar baseado no tipo
                    if ($user['tipo'] == 'admin') {
                        header('Location: admin.php');
                    } else {
                        header('Location: index.php');
                    }
                    exit;
                } else {
                    $erro = 'Senha incorreta. Tente novamente.';
                }
            }
        } else {
            $erro = 'Usuário não encontrado.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clínica Saúde Total</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ebf4ff, #e2e8f0);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .container {
            background: white;
            border-radius: 24px;
            padding: 3rem 2.5rem;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo i {
            font-size: 3rem;
            color: #2b6cb0;
            background: #ebf4ff;
            padding: 1rem;
            border-radius: 50%;
        }

        .logo h1 {
            font-size: 1.75rem;
            color: #1a202c;
            margin-top: 1rem;
        }

        .logo p {
            color: #718096;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #edf2f7;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2b6cb0;
            box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.1);
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #2b6cb0, #2c5282);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(43, 108, 176, 0.3);
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fff5f5;
            color: #9b2c2c;
            border: 1px solid #feb2b2;
        }

        .alert-success {
            background: #f0fff4;
            color: #276749;
            border: 1px solid #c6f6d5;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #4a5568;
        }

        .register-link a {
            color: #2b6cb0;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .demo-info {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f7fafc;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #4a5568;
        }

        .demo-info strong {
            color: #2b6cb0;
        }

        @media (max-width: 480px) {
            .container {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <i class="fas fa-heartbeat"></i>
            <h1>Clínica Saúde Total</h1>
            <p>Faça login para acessar o sistema</p>
        </div>

        <?php if ($mensagem): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required autofocus>
            </div>

            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </form>

        <div class="register-link">
            Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
        </div>

       
    </div>
</body>
</html>