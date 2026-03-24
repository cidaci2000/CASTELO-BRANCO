<?php
require_once 'config/conexao.php';

// Verificar se usuário está logado
if (!estaLogado()) {
    redirecionar('login.php');
}

// Verificar se é cliente
if (!temPermissao(['cliente'])) {
    redirecionar('login.php');
}

iniciarSessao();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente - Candy Love's</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            min-height: 100vh;
        }
        .header {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header h1 { color: #ff6b6b; }
        .user-info { display: flex; align-items: center; gap: 20px; }
        .logout-btn {
            background: #ff6b6b;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
        }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }
        .welcome-card h2 { color: #ff6b6b; margin-bottom: 10px; }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .feature-card:hover { transform: translateY(-5px); }
        .feature-card i { font-size: 48px; color: #ff6b6b; margin-bottom: 15px; display: inline-block; }
        .feature-card h3 { color: #ff6b6b; margin-bottom: 10px; }
        .feature-card p { color: #666; }
        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍓 Candy Love's</h1>
        <div class="user-info">
            <span>👤 Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
            <a href="logout.php" class="logout-btn">Sair</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <h2>Bem-vindo(a) ao Candy Love's! 💗</h2>
            <p>Explore nossas receitas e descubra delícias incríveis!</p>
        </div>
        
        <div class="features">
            <div class="feature-card" onclick="window.location.href='receitas.php'">
                <i>📖</i>
                <h3>Receitas</h3>
                <p>Explore nossas receitas exclusivas</p>
            </div>
            <div class="feature-card" onclick="window.location.href='favoritos.php'">
                <i>❤️</i>
                <h3>Favoritos</h3>
                <p>Suas receitas favoritas</p>
            </div>
            <div class="feature-card" onclick="window.location.href='perfil.php'">
                <i>👤</i>
                <h3>Meu Perfil</h3>
                <p>Gerencie seus dados</p>
            </div>
        </div>
    </div>
</body>
</html>