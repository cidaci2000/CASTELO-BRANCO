<?php
session_start();
require_once 'config.php';

// Verifica se a conexão foi estabelecida
if (!isset($conn) || $conn->connect_error) {
    die("Erro de conexão com o banco de dados. Contate o administrador.");
}

// Se já estiver logado, redireciona baseado no tipo
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['usuario_tipo'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: app.php");
    }
    exit();
}

$erro_login = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $mensagem = "Preencha todos os campos!";
        if ($is_ajax) {
            echo json_encode(['success' => false, 'message' => $mensagem]);
            exit();
        }
        $erro_login = $mensagem;
    } else {
        // Busca o usuário na tabela unificada
        $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            $mensagem = "Erro ao preparar a consulta.";
            if ($is_ajax) {
                echo json_encode(['success' => false, 'message' => $mensagem]);
                exit();
            }
            $erro_login = $mensagem;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                
                if (password_verify($senha, $usuario['senha'])) {
                    // Armazena dados do usuário na sessão
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['usuario_tipo'] = $usuario['tipo'];
                    
                    // Define o redirecionamento baseado no tipo
                    $redirect_page = ($usuario['tipo'] == 'admin') ? "admin.php" : "app.php";
                    
                    if ($is_ajax) {
                        echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso!', 'redirect' => $redirect_page]);
                        exit();
                    }
                    
                    header("Location: " . $redirect_page);
                    exit();
                }
            }
            
            $mensagem = "Email ou senha inválidos!";
            if ($is_ajax) {
                echo json_encode(['success' => false, 'message' => $mensagem]);
                exit();
            }
            $erro_login = $mensagem;
            $stmt->close();
        }
    }
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
            --danger: #ff4757;
            --danger-bg: #ffebee;
            --card-bg: #ffffff;
            --input-border: #dfe6e9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .header-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header-container h1 { 
            margin: 0; 
            color: #2c3e50; 
            font-size: 28px; 
        }
        
        .header-container p { 
            color: #7f8c8d; 
            margin-top: 5px; 
        }

        .auth-card {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            border-top: 5px solid var(--primary);
        }

        .auth-card h2 {
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
            color: #2c3e50;
            font-size: 22px;
        }

        .alert-error {
            background-color: var(--danger-bg);
            color: var(--danger);
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #ffcccb;
            display: none;
        }

        .alert-error.show {
            display: block;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #a5d6a7;
            display: none;
        }

        .alert-success.show {
            display: block;
        }

        .form-group { 
            margin-bottom: 20px; 
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
            outline: none;
        }

        .form-control:focus { 
            border-color: var(--primary); 
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn-submit:hover:not(:disabled) { 
            background-color: var(--primary-hover); 
        }
        
        .btn-submit:active:not(:disabled) { 
            transform: scale(0.98); 
        }
        
        .btn-submit:disabled { 
            background-color: #bdc3c7; 
            cursor: not-allowed; 
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
        }

        .auth-footer a:hover { 
            text-decoration: underline; 
        }

        .loading-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #fff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #toast {
            visibility: hidden;
            min-width: 280px;
            max-width: 90%;
            background-color: #2ecc71;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 14px 20px;
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.4s ease, visibility 0.4s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            font-size: 15px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #toast.show {
            visibility: visible;
            opacity: 1;
        }

        #toast.error {
            background-color: #e74c3c;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        #toast.show {
            animation: slideUp 0.4s ease forwards;
        }
    </style>
</head>
<body>

<div class="header-container">
    <h1>💈 Barbearia Elite</h1>
    <p>Acesse sua conta</p>
</div>

<main class="auth-card">
    <form id="formLogin" method="POST">
        <h2>Login</h2>
        
        <?php if (!empty($erro_login)): ?>
            <div class="alert-error show">
                ⚠️ <?php echo htmlspecialchars($erro_login); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] == 'sucesso'): ?>
            <div class="alert-success show">
                ✅ Cadastro realizado com sucesso! Faça seu login.
            </div>
        <?php endif; ?>

        <div id="jsErrorAlert" class="alert-error"></div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="seu@email.com" required autocomplete="email">
        </div>

        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required autocomplete="current-password">
        </div>

        <button type="submit" id="submitBtn" class="btn-submit">Entrar</button>

        <div class="auth-footer">
            Não tem conta? <a href="cadastro.php">Cadastrar</a>
        </div>
    </form>
</main>

<div id="toast"></div>

<script>
const form = document.getElementById('formLogin');
const submitBtn = document.getElementById('submitBtn');
const toast = document.getElementById('toast');
const jsErrorAlert = document.getElementById('jsErrorAlert');
let toastTimeout = null;

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;
    
    // Esconde erros anteriores
    jsErrorAlert.classList.remove('show');
    jsErrorAlert.textContent = '';
    
    // Validação básica
    if (!email) {
        mostrarErroJS('Por favor, informe seu email.');
        document.getElementById('email').focus();
        return;
    }
    
    if (!senha) {
        mostrarErroJS('Por favor, informe sua senha.');
        document.getElementById('senha').focus();
        return;
    }
    
    // Validação de formato de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        mostrarErroJS('Por favor, informe um email válido.');
        document.getElementById('email').focus();
        return;
    }
    
    setButtonLoading(true);
    
    try {
        const formData = new FormData();
        formData.append('email', email);
        formData.append('senha', senha);
        
        const response = await fetch(window.location.href, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Erro na requisição: ' + response.status);
        }
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        } else {
            mostrarErroJS(data.message);
            setButtonLoading(false);
            document.getElementById('senha').value = '';
            document.getElementById('senha').focus();
        }
    } catch (error) {
        console.error('Erro:', error);
        mostrarErroJS('Erro ao processar login. Tente novamente.');
        setButtonLoading(false);
    }
});

function mostrarErroJS(mensagem) {
    jsErrorAlert.textContent = '⚠️ ' + mensagem;
    jsErrorAlert.classList.add('show');
    
    // Oculta os alerts do PHP se existirem
    const phpAlertError = document.querySelector('.alert-error:not(#jsErrorAlert)');
    if (phpAlertError) phpAlertError.style.display = 'none';
    
    const phpAlertSuccess = document.querySelector('.alert-success');
    if (phpAlertSuccess) phpAlertSuccess.style.display = 'none';
}

function showToast(message, type = 'success') {
    // Limpa timeout anterior
    if (toastTimeout) {
        clearTimeout(toastTimeout);
    }
    
    toast.textContent = message;
    toast.className = 'show';
    
    if (type === 'error') {
        toast.classList.add('error');
    } else {
        toast.classList.remove('error');
    }
    
    // Esconde após 3 segundos
    toastTimeout = setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

function setButtonLoading(loading) {
    if (loading) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Entrando...';
    } else {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Entrar';
    }
}

// Remove mensagem de erro ao digitar nos campos
document.getElementById('email').addEventListener('input', function() {
    jsErrorAlert.classList.remove('show');
});

document.getElementById('senha').addEventListener('input', function() {
    jsErrorAlert.classList.remove('show');
});
</script>

</body>
</html>