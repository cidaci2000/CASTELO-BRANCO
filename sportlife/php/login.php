<?php
session_start();
require_once '../database.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];
        
        if ($usuario['tipo'] == 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: inicial.php');
        }
        exit();
    } else {
        $erro = "Email ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportLife - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #4f46e5;
            --bg-black: #0a0a0f;
            --card-gray: rgba(255, 255, 255, 0.03);
            --border: rgba(79, 70, 229, 0.15);
            --text-muted: #94a3b8;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: var(--bg-black);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .glow {
            position: fixed;
            width: 400px;
            height: 400px;
            background: #3730a3;
            filter: blur(150px);
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
        }
        .card {
            background: var(--card-gray);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            padding: 40px 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }
        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            margin-bottom: 15px;
            background: linear-gradient(to bottom, #fff, var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .input-group {
            margin: 15px 0;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 14px 18px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
            color: white;
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
        }
        input:focus {
            border-color: var(--primary-purple);
            background: rgba(79,70,229,0.08);
            box-shadow: 0 0 15px rgba(79,70,229,0.2);
        }
        button {
            width: 100%;
            padding: 16px;
            margin-top: 20px;
            background: var(--primary-purple);
            border: none;
            border-radius: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.4s;
            box-shadow: 0 4px 15px rgba(79,70,229,0.3);
            color: white;
        }
        button:hover {
            background: white;
            color: black;
            transform: translateY(-3px);
        }
        .mensagem-erro {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }
        .note {
            margin-top: 20px;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: center;
        }
        .note a {
            color: var(--primary-purple);
            text-decoration: none;
        }
        .note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="glow"></div>
    <div class="card">
        <h1>SPORTLIFE</h1>
        
        <?php if ($erro): ?>
            <div class="mensagem-erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="input-group">
                <label>📧 E-MAIL</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>
            
            <div class="input-group">
                <label>🔒 SENHA</label>
                <input type="password" name="senha" required placeholder="Digite sua senha">
            </div>
            
            <button type="submit">ENTRAR</button>
        </form>
        
        <div class="note">
            Não tem cadastro? <a href="cadastro.php">Cadastre-se agora</a>
        </div>
    </div>
</body>
</html>