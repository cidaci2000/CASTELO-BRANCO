<?php
require_once '../config/conexao.php';

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
       * { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f9f5f2; /* bege pastel base */
    min-height: 100vh;
}

/* Header */
.header {
    background-color: #d8a7b1; /* rosa vintage */
    padding: 20px;
    box-shadow: 0 2px 10px rgba(90, 70, 70, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    border-radius: 10px;
}

.header h1 { 
    color: #3b2c2c; /* marrom escuro */
}

.user-info { 
    display: flex; 
    align-items: center; 
    gap: 20px; 
}

.logout-btn {
    background-color: #fcd5ce; /* rosa pastel claro */
    color: #7c2d2d; /* vermelho queimado */
    padding: 8px 20px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    text-decoration: none;
    font-weight: bold;
}

/* Container */
.container { 
    max-width: 1200px; 
    margin: 40px auto; 
    padding: 0 20px; 
}

/* Welcome */
.welcome-card {
    background: #fffaf7; /* branco quente */
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 30px;
    text-align: center;
}

.welcome-card h2 { 
    color: #b83b5e; /* rosa destaque */
    margin-bottom: 10px; 
}

/* Features */
.features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.feature-card {
    background: #fffaf7;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    transition: transform 0.3s ease;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(90, 70, 70, 0.1);
}

.feature-card:hover { 
    transform: translateY(-5px); 
}

.feature-card i { 
    font-size: 48px; 
    color: #b83b5e; 
    margin-bottom: 15px; 
    display: inline-block; 
}

.feature-card h3 { 
    color: #b83b5e; 
    margin-bottom: 10px; 
}

.feature-card p { 
    color: #6e5f5f; /* marrom acinzentado */
}

/* Responsivo */
@media (max-width: 768px) {
    .header { 
        flex-direction: column; 
        text-align: center; 
    }
}
.feature-card {
    background: #fffaf7;
    border-radius: 15px;
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(90, 70, 70, 0.1);
    position: relative;
    overflow: hidden;
}

/* efeito suave de fundo ao passar o mouse */
.feature-card::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f9d5e5, #f3e1dd);
    top: 0;
    left: 0;
    opacity: 0;
    transition: 0.3s;
    z-index: 0;
}

.feature-card:hover::before {
    opacity: 1;
}

.feature-card:hover {
    transform: translateY(-8px) scale(1.02);
}

/* garante que o conteúdo fique acima do efeito */
.feature-card * {
    position: relative;
    z-index: 1;
}

/* imagens */
.feature-icon {
    width: 70px;
    height: 70px;
    object-fit: contain;
    margin-bottom: 15px;
    transition: transform 0.3s ease;
}

/* animação da imagem */
.feature-card:hover .feature-icon {
    transform: scale(1.1) rotate(3deg);
}

.feature-card h3 {
    color: #b83b5e;
    margin-bottom: 10px;
}

.feature-card p {
    color: #6e5f5f;
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
            <p>Explore nossas receitas, livros e descubra delícias incríveis!</p>
        </div>
        
        <div class="features">
    <div class="feature-card" onclick="window.location.href='receitas.php'">
        <img src="https://i1-e.pinimg.com/1200x/71/a3/cb/71a3cbb6ede796632c91d99fc80151d9.jpg" alt="Receitas" class="feature-icon">
        <h3>Receitas</h3>
        <p>Explore nossas receitas exclusivas</p>
    </div>

    <div class="feature-card" onclick="window.location.href='favoritos.php'">
        <img src="img/favoritos.png" alt="Favoritos" class="feature-icon">
        <h3>Favoritos</h3>
        <p>Suas receitas favoritas</p>
    </div>

    <div class="feature-card" onclick="window.location.href='perfil.php'">
        <img src="img/perfil.png" alt="Perfil" class="feature-icon">
        <h3>Meu Perfil</h3>
        <p>Gerencie seus dados</p>
    </div>
</div>
    </div>
</body>
</html>