<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Se já estiver logado, redirecionar
if (isset($_SESSION['usuario_id'])) {
    if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin') {
        header("Location: ../admin/dashboard.php");
        exit();
    } elseif (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'instrutor') {
        header("Location: ../instrutor/dashboard.php");
        exit();
    } else {
        header("Location: dashboard.php");
        exit();
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $error = 'Por favor, preencha todos os campos.';
    } else {
        $stmt = $conn->prepare("SELECT id, nome_completo, email, senha, modalidade, tipo_usuario FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome_usuario'] = $usuario['nome_completo'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['modalidade'] = $usuario['modalidade'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
                
                $success = 'Login realizado com sucesso!';
                
                // Redirecionar baseado no tipo
                if ($usuario['tipo_usuario'] === 'admin') {
                    $redirect = '../php/admin/dashboard.php';
                } elseif ($usuario['tipo_usuario'] === 'instrutor') {
                    $redirect = '../php/instrutor/dashboard.php';
                } else {
                    $redirect = 'dashboard.php';
                }
                
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "' . $redirect . '";
                    }, 1500);
                </script>';
                
            } else {
                $error = 'Senha incorreta. Tente novamente.';
            }
        } else {
            $error = 'E-mail não encontrado.';
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
    <title>Login | SportLife</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-glow: rgba(79, 70, 229, 0.4);
            --bg-dark: #06060a;
            --card-bg: rgba(10, 10, 15, 0.7);
            --card-border: rgba(255, 255, 255, 0.06);
            --input-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #64748b;
            --success: #22c55e;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .bg-glow-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(79,70,229,0.15) 0%, rgba(0,0,0,0) 70%);
            top: -10%;
            left: -10%;
            z-index: 1;
        }

        .bg-glow-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(67, 56, 202, 0.12) 0%, rgba(0,0,0,0) 70%);
            bottom: -20%;
            right: -10%;
            z-index: 1;
        }

        .card-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--card-border);
            padding: 45px 35px;
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3.5rem;
            letter-spacing: 1px;
            line-height: 1;
            margin-bottom: 8px;
            background: linear-gradient(180deg, #ffffff 20%, #818cf8 65%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 35px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: left;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--input-border);
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-main);
            font-size: 0.95rem;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: var(--primary);
            background: rgba(79, 70, 229, 0.04);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        button {
            width: 100%;
            padding: 16px;
            margin-top: 15px;
            background: var(--primary);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.3);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(79, 70, 229, 0.5);
            filter: brightness(1.1);
        }

        button:active {
            transform: translateY(0);
        }

        .note {
            margin-top: 30px;
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.8;
        }

        .note a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .note a:hover {
            color: white;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            margin-top: 0;
            width: auto;
            box-shadow: none;
        }

        .toggle-password:hover {
            color: var(--text-main);
            transform: translateY(-50%) scale(1.1);
            box-shadow: none;
            filter: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .card { padding: 35px 24px; }
            h1 { font-size: 3rem; }
        }
    </style>
</head>
<body>
    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="card-container">
        <div class="card">
            <h1>Entrar</h1>
            <div class="subtitle">Acesse sua conta no SportLife</div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                </div>
                <div class="input-group">
                    <label for="senha">Senha</label>
                    <div class="input-wrapper">
                        <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                        <button type="button" class="toggle-password" onclick="toggleSenha()">👁️</button>
                    </div>
                </div>
                <button type="submit">Acessar Plataforma</button>
            </form>
            
            <div class="note">
                Não tem uma conta? <a href="cadastro.php">Cadastre-se</a><br>
                <a href="../php/login.php" style="font-size: 0.75rem; opacity: 0.6;">🔒 Área Administrativa</a>
            </div>
        </div>
    </div>

    <script>
        function toggleSenha() {
            const senhaInput = document.getElementById('senha');
            const toggleBtn = document.querySelector('.toggle-password');
            
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                senhaInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }
    </script>
</body>
</html>