<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: app.php");
    exit();
}

$erro_cadastro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';
    
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar)) {
        $erro_cadastro = "Todos os campos são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro_cadastro = "Email inválido.";
    } elseif ($senha !== $confirmar) {
        $erro_cadastro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6) {
        $erro_cadastro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $erro_cadastro = "Email já cadastrado.";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nome, $email, $senhaHash);
            
            if ($stmt->execute()) {
                header("Location: login.php?cadastro=sucesso");
                exit();
            } else {
                $erro_cadastro = "Erro ao cadastrar. Tente novamente.";
            }
        }
        $stmt->close();
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
</head>
<body>

<header>
    <h1>💈 Barbearia Elite</h1>
    <p>Criar conta</p>
</header>

<main>
    <form id="formCadastro" method="POST">
        <h2>Cadastro</h2>
        
        <?php if ($erro_cadastro): ?>
            <div style="background: #e74c3c; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                <?php echo htmlspecialchars($erro_cadastro); ?>
            </div>
        <?php endif; ?>

        <input type="text" id="nome" name="nome" placeholder="Nome completo" required value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
        <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        <input type="password" id="senha" name="senha" placeholder="Senha (mínimo 6 caracteres)" required>
        <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirmar senha" required>

        <button type="submit">Cadastrar</button>

        <p>Já tem conta? <a href="login.php">Entrar</a></p>
    </form>
</main>

<script>
document.getElementById('formCadastro').addEventListener('submit', function(e) {
    const senha = document.getElementById('senha').value;
    const confirmar = document.getElementById('confirmar_senha').value;
    
    if (senha !== confirmar) {
        e.preventDefault();
        alert('As senhas não coincidem!');
        return;
    }
    
    if (senha.length < 6) {
        e.preventDefault();
        alert('A senha deve ter pelo menos 6 caracteres!');
        return;
    }
});
</script>

</body>
</html>