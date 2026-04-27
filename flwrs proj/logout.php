<?php
// logout.php - Versão com tela de confirmação
session_start();

// Processar logout
if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    // Destruir todas as variáveis de sessão
    $_SESSION = array();
    
    // Destruir cookie de sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destruir a sessão
    session_destroy();
    
    // Remover cookie de "lembrar de mim"
    if (isset($_COOKIE['lembrar_email'])) {
        setcookie('lembrar_email', '', time() - 3600, '/');
    }
    
    // Redirecionar para home
    header("Location: home.php?msg=logout");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saindo da Flwrs · confirmação</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #fefaf5;
            font-family: 'Inter', sans-serif;
            color: #2a2a2a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-container {
            background: #ffffffd9;
            backdrop-filter: blur(2px);
            border-radius: 32px 16px 32px 16px;
            padding: 2.8rem 2.5rem;
            box-shadow: 0 20px 35px -12px rgba(152, 123, 136, 0.2);
            border: 1px solid rgba(247, 213, 231, 0.6);
            max-width: 480px;
            width: 90%;
            text-align: center;
        }

        .logout-icon {
            font-size: 4rem;
            color: #b4849a;
            margin-bottom: 1rem;
        }

        h2 {
            color: #b4849a;
            font-weight: 320;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        p {
            color: #7f7269;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-confirm {
            background: #b2e4b3;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 60px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 500;
            color: #2d3b2d;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-confirm:hover {
            background: #deef6e;
            transform: scale(1.02);
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid #f7d5e7;
            padding: 0.8rem 2rem;
            border-radius: 60px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 500;
            color: #b4849a;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancel:hover {
            background: #fefaf5;
            border-color: #c06f8b;
            color: #c06f8b;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">
            <span class="material-symbols-outlined" style="font-size: 4rem;">logout</span>
        </div>
        
        <h2>Sair da sua conta?</h2>
        
        <p>Tem certeza que deseja sair?<br>
        Você precisará fazer login novamente para acessar sua conta.</p>
        
        <div class="button-group">
            <a href="?confirm=yes" class="btn-confirm">Sim, sair</a>
            <a href="javascript:history.back()" class="btn-cancel">Cancelar</a>
        </div>
    </div>
</body>
</html>