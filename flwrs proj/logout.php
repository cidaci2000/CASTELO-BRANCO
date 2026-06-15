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
    <link rel="stylesheet" href="css/logout.css">
    <style>
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