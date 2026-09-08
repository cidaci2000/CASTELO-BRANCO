<?php
// 1. Inicialização e Segurança
session_start();
require_once('../../app/config/config.php');

// Se já estiver logado, redirecionar baseado no tipo
if (isset($_SESSION['usuario_id'])) {
    if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin') {
        header("Location: ../admin/admin.php");
    } else {
        header("Location: ../cliente/app.php");
    }
    exit();
}

// Processar login
$erro = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    // Verificar credenciais
    $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($usuario = $result->fetch_assoc()) {
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'] ?? 'cliente'; // Adiciona o tipo
            
            // Redireciona baseado no tipo
            if ($usuario['tipo'] === 'admin') {
                header("Location: ../admin/admin.php");
            } else {
                header("Location: ../cliente/app.php");
            }
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barbearia Elite</title>
    <style>
        :root {
            --primary: #d4af37;
            --primary-hover: #b5952f;
            --bg-color: #f4f7f6;
            --text-color: #333;
            --card-bg: #ffffff;
            --input-border: #dfe6e9;
            --danger: #ff4757;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--card-bg);
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border-top: 6px solid var(--primary);
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-2px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .login-header h1 span {
            color: var(--primary);
        }

        .login-header p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .logo-icon {
            font-size: 48px;
            display: block;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--input-border);
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s ease;
            background: #fafafa;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        }

        .form-control::placeholder {
            color: #bdc3c7;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
            transform: none;
        }

        /* Mensagem de erro */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fff5f5;
            color: var(--danger);
            border: 1px solid #ffe0e0;
        }

        .alert-success {
            background: #f0fff4;
            color: #2ed573;
            border: 1px solid #d4f5e0;
        }

        .alert-icon {
            font-size: 18px;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Campo com ícone */
        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper .form-control {
            padding-left: 44px;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #bdc3c7;
            pointer-events: none;
        }

        /* Responsivo */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 22px;
            }

            .login-header .logo-icon {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <span class="logo-icon">💈</span>
                <h1>Barbearia <span>Elite</span></h1>
                <p>Faça login para gerenciar seus agendamentos</p>
            </div>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">❌</span>
                    <?php echo htmlspecialchars($erro); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">📧</span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="seu@email.com" 
                            required 
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">🔒</span>
                        <input 
                            type="password" 
                            id="senha" 
                            name="senha" 
                            class="form-control" 
                            placeholder="Digite sua senha" 
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Entrar no sistema
                </button>
            </form>

            <div class="login-footer">
                Não tem uma conta? <a href="registro.php">Cadastre-se</a>
            </div>
        </div>
    </div>
</body>
</html>