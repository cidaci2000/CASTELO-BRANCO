<?php
// login_simples.php - Versão ultra simplificada sem CSS
session_start();
require_once 'config.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    echo "<pre>";
    echo "Email recebido: " . $email . "\n";
    echo "Senha recebida: " . strlen($senha) . " caracteres\n";
    
    global $pdo;
    
    try {
        $sql = "SELECT id, nome_completo, email, senha_hash, tipo_usuario FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        echo "Linhas encontradas: " . $stmt->rowCount() . "\n";
        
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch();
            echo "Usuário encontrado: " . $usuario['email'] . "\n";
            echo "Hash da senha no banco: " . $usuario['senha_hash'] . "\n";
            
            if (password_verify($senha, $usuario['senha_hash'])) {
                echo "SENHA CORRETA! ✅\n";
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome_completo'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
                
                echo "Redirecionando para: " . ($usuario['tipo_usuario'] === 'admin' ? 'admin.php' : 'compra.php') . "\n";
                header("refresh:3; url=" . ($usuario['tipo_usuario'] === 'admin' ? 'admin.php' : 'compra.php'));
                $sucesso = "Login realizado com sucesso! Redirecionando...";
            } else {
                echo "SENHA INCORRETA! ❌\n";
                $erro = "E-mail ou senha incorretos";
            }
        } else {
            echo "Usuário NÃO encontrado! ❌\n";
            $erro = "E-mail ou senha incorretos";
        }
    } catch(Exception $e) {
        echo "ERRO: " . $e->getMessage() . "\n";
        $erro = "Erro: " . $e->getMessage();
    }
    echo "</pre>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Simples</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Login - Versão de Teste</h1>
    
    <?php if ($erro): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">
            <strong>Erro:</strong> <?php echo $erro; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($sucesso): ?>
        <div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;">
            <strong>Sucesso:</strong> <?php echo $sucesso; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" required style="width: 300px; padding: 8px;">
        </div>
        <div style="margin-top: 10px;">
            <label>Senha:</label><br>
            <input type="password" name="senha" required style="width: 300px; padding: 8px;">
        </div>
        <div style="margin-top: 20px;">
            <button type="submit" style="padding: 10px 20px;">Entrar</button>
        </div>
    </form>
    
    <hr>
    
    <h3>Usuários cadastrados no banco:</h3>
    <?php
    global $pdo;
    $users = $pdo->query("SELECT id, email, nome_completo, tipo_usuario FROM usuarios");
    echo "<ul>";
    while($user = $users->fetch()) {
        echo "<li>ID: {$user['id']} - Email: {$user['email']} - Nome: {$user['nome_completo']} - Tipo: {$user['tipo_usuario']}</li>";
    }
    echo "</ul>";
    ?>
    
    <h3>Para testar, use estas credenciais:</h3>
    <p><strong>Admin:</strong> admin@flwrs.com / password</p>
    <p><strong>Cliente:</strong> cliente@flwrs.com / password</p>
    <p>OU crie um novo usuário abaixo:</p>
    
    <h3>Criar novo usuário de teste:</h3>
    <form method="POST" action="criar_usuario_rapido.php">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <select name="tipo">
            <option value="cliente">Cliente</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Criar</button>
    </form>
</body>
</html>