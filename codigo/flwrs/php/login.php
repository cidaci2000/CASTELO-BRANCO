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
    <title>flwrs · login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<style>
    /* ===== RESET & BASE ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: #fcf8f5;
    color: #3d3835;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(150deg, #fcf8f5 0%, #fefaf5 50%, #f5ece9 100%);
    padding: 2rem;
}

/* ===== LOGIN BOX ===== */
.login-box {
    background: white;
    padding: 3.5rem 3rem;
    border-radius: 32px;
    border: 1px solid rgba(180, 165, 160, 0.08);
    box-shadow: 0 30px 80px -20px rgba(0, 0, 0, 0.06), 0 10px 30px -10px rgba(0, 0, 0, 0.02);
    max-width: 440px;
    width: 100%;
    transition: all 0.4s ease;
}

.login-box:hover {
    box-shadow: 0 40px 100px -24px rgba(0, 0, 0, 0.08);
}

/* ===== LOGO ===== */
.logo {
    text-align: center;
    font-size: 2.2rem;
    font-weight: 200;
    letter-spacing: 0.04em;
    color: #3d3835;
    margin-bottom: 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
}

.logo strong {
    font-weight: 500;
    color: #e94e77;
}

.logo small {
    font-size: 0.6rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #a8958f;
    font-weight: 300;
    margin-top: 0.2rem;
}

/* ===== TYPOGRAPHY ===== */
.login-box h2 {
    text-align: center;
    font-size: 1.6rem;
    font-weight: 400;
    letter-spacing: 0.02em;
    color: #2d2825;
    margin-bottom: 0.2rem;
}

.sub {
    text-align: center;
    font-size: 0.85rem;
    color: #a8958f;
    margin-bottom: 2rem;
    font-weight: 300;
}

/* ===== ERROR MESSAGE ===== */
.error {
    background: #fef6f5;
    border: 1px solid #f8d7da;
    border-radius: 16px;
    padding: 0.8rem 1.2rem;
    margin-bottom: 1.5rem;
    color: #721c24;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

/* ===== FORM ===== */
.input-group {
    margin-bottom: 1.5rem;
}

.input-group label {
    display: block;
    font-size: 0.65rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #6d6560;
    margin-bottom: 0.5rem;
}

.input-group input {
    width: 100%;
    padding: 0.9rem 1.2rem;
    font-size: 0.95rem;
    font-family: inherit;
    color: #3d3835;
    background: #fcf8f5;
    border: 1.5px solid rgba(180, 165, 160, 0.15);
    border-radius: 16px;
    transition: all 0.3s ease;
    outline: none;
}

.input-group input:focus {
    border-color: #e94e77;
    background: white;
    box-shadow: 0 0 0 4px rgba(233, 78, 119, 0.06);
}

.input-group input::placeholder {
    color: #bbb0aa;
    font-weight: 300;
}

/* ===== BUTTON ===== */
.btn-login {
    width: 100%;
    padding: 1rem 2rem;
    background: #e94e77;
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    margin-top: 0.5rem;
}

.btn-login:hover {
    background: #d43f68;
    transform: translateY(-2px);
    box-shadow: 0 12px 40px -8px rgba(233, 78, 119, 0.3);
}

.btn-login:active {
    transform: translateY(0);
}

/* ===== LINKS ===== */
.links {
    text-align: center;
    margin-top: 2rem;
    font-size: 0.8rem;
    color: #a8958f;
    display: flex;
    justify-content: center;
    gap: 0.6rem;
    flex-wrap: wrap;
}

.links a {
    color: #6d6560;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 400;
    border-bottom: 1px solid transparent;
}

.links a:hover {
    color: #e94e77;
    border-bottom-color: #e94e77;
}

.links .separator {
    color: #e8d5d0;
}

/* ===== PASSWORD FORGOT ===== */
.forgot-password {
    text-align: right;
    margin: -0.5rem 0 1.5rem;
}

.forgot-password a {
    font-size: 0.7rem;
    color: #a8958f;
    text-decoration: none;
    transition: all 0.3s ease;
    letter-spacing: 0.04em;
}

.forgot-password a:hover {
    color: #e94e77;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 600px) {
    body {
        padding: 1.5rem;
        align-items: flex-start;
        padding-top: 3rem;
    }

    .login-box {
        padding: 2.5rem 1.5rem;
        border-radius: 24px;
        max-width: 100%;
    }

    .logo {
        font-size: 1.8rem;
        margin-bottom: 2rem;
    }

    .login-box h2 {
        font-size: 1.3rem;
    }

    .btn-login {
        font-size: 0.75rem;
        padding: 0.9rem 1.5rem;
    }

    .input-group input {
        font-size: 16px; /* Previne zoom automático em iOS */
        padding: 0.8rem 1rem;
    }
}

@media (max-width: 400px) {
    .login-box {
        padding: 2rem 1rem;
        border-radius: 20px;
    }

    .links {
        flex-direction: column;
        gap: 0.4rem;
    }

    .links .separator {
        display: none;
    }
}
</style>
<body>
    <div class="login-box">
        <div class="logo">
            flwrs <strong>·</strong>
            <small>Flowers that feel like feeling</small>
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

<script src="../js/login.js"></script>
</body>
</html>