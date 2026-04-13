<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: app.php");
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
        $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                if ($is_ajax) {
                    echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso!']);
                    exit();
                }
                
                header("Location: app.php");
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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barbearia Elite</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>💈 Barbearia Elite</h1>
    <p>Entrar</p>
</header>

<main>
    <form id="formLogin" method="POST">
        <h2>Login</h2>
        
        <?php if ($erro_login): ?>
            <div style="background: #e74c3c; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                <?php echo htmlspecialchars($erro_login); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] == 'sucesso'): ?>
            <div style="background: #2ecc71; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                Cadastro realizado com sucesso! Faça seu login.
            </div>
        <?php endif; ?>

        <input type="email" id="email" name="email" placeholder="Email" required autocomplete="email">
        <input type="password" id="senha" name="senha" placeholder="Senha" required autocomplete="current-password">

        <button type="submit" id="submitBtn">Entrar</button>

        <p>Não tem conta? <a href="cadastro.php">Cadastrar</a></p>
    </form>
</main>

<div id="toast"></div>

<script>
const form = document.getElementById('formLogin');
const submitBtn = document.getElementById('submitBtn');
const toast = document.getElementById('toast');

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;
    
    if (!email || !senha) {
        showToast('Preencha todos os campos!', 'error');
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
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                window.location.href = 'app.php';
            }, 1500);
        } else {
            showToast(data.message, 'error');
            setButtonLoading(false);
            document.getElementById('senha').value = '';
        }
    } catch (error) {
        showToast('Erro ao processar login.', 'error');
        setButtonLoading(false);
    }
});

function showToast(message, type = 'success') {
    toast.textContent = message;
    toast.className = 'show';
    if (type === 'error') {
        toast.classList.add('error');
    }
    
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.remove('error');
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
</script>

</body>
</html>