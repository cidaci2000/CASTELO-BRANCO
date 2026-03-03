<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar_senha']; // Corrigido: nome do campo

    // Validações básicas
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar)) {
        die("Todos os campos são obrigatórios.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email inválido.");
    }

    // Verifica se as senhas são iguais
    if ($senha !== $confirmar) {
        die("As senhas não coincidem.");
    }

    // Verifica se a senha tem pelo menos 6 caracteres
    if (strlen($senha) < 6) {
        die("A senha deve ter pelo menos 6 caracteres.");
    }

    // Verifica se o email já existe
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        die("Email já cadastrado.");
    }

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere no banco
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$nome, $email, $senhaHash])) {
        // Redireciona para a página de login com mensagem de sucesso
        header("Location: index.php?cadastro=sucesso");
        exit();
    } else {
        echo "Erro ao cadastrar.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Barbearia Elite</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error-message {
            color: #ff3333;
            margin: 10px 0;
            padding: 10px;
            background-color: #ffeeee;
            border-radius: 4px;
            display: none;
        }
        
        .success-message {
            color: #00cc00;
            margin: 10px 0;
            padding: 10px;
            background-color: #eeffee;
            border-radius: 4px;
            display: none;
        }
        
        #formCadastro {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        #formCadastro input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        #formCadastro button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 0;
        }
        
        #formCadastro button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<header>
    <h1>💈 Barbearia Elite</h1>
    <p>Criar conta</p>
</header>

<main>
    <div id="messageContainer"></div>
    
    <form id="formCadastro" method="POST" action="">
        <h2>Cadastro</h2>

        <input type="text" id="nome" name="nome" placeholder="Nome completo" required>
        <input type="email" id="email" name="email" placeholder="Email" required>
        <input type="password" id="senha" name="senha" placeholder="Senha (mínimo 6 caracteres)" required>
        <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirmar senha" required>

        <button type="submit">Cadastrar</button>

        <p>Já tem conta? <a href="index.php">Entrar</a></p>
    </form>
</main>

<div id="toast"></div>

<script>
document.getElementById('formCadastro').addEventListener('submit', function(e) {
    const senha = document.getElementById('senha').value;
    const confirmar = document.getElementById('confirmar_senha').value;
    const messageContainer = document.getElementById('messageContainer');
    
    // Limpa mensagens anteriores
    messageContainer.innerHTML = '';
    
    // Validação do lado do cliente
    if (senha !== confirmar) {
        e.preventDefault();
        messageContainer.innerHTML = '<div class="error-message" style="display: block;">As senhas não coincidem.</div>';
        return;
    }
    
    if (senha.length < 6) {
        e.preventDefault();
        messageContainer.innerHTML = '<div class="error-message" style="display: block;">A senha deve ter pelo menos 6 caracteres.</div>';
        return;
    }
});

// Mostra mensagem de sucesso se veio do redirecionamento
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('cadastro') === 'sucesso') {
    const messageContainer = document.getElementById('messageContainer');
    messageContainer.innerHTML = '<div class="success-message" style="display: block;">Cadastro realizado com sucesso! Faça o login.</div>';
}
</script>

<script src="script.js"></script>
</body>
</html>