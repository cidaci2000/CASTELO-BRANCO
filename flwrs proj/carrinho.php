<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Flwrs · Seu carrinho de flores</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <link rel="stylesheet" href="css/carrinho.css">
</head>
<body>

<header>
    <div class="container">
        <div class="header-flex">
            <div class="logo-area">
                <div class="logo-word">
			    Flwrs <strong>·</strong>
		        </div>
                <div class="tagline-header">carrinho · “Flowers that feel like felling”</div>
            </div>
            <div class="nav-menu">
                <a href="home.php">início</a>
                <a href="produtos.php">produtos</a>
                <a href="sobre.php">sobre</a>
                <!-- CARRINHO COM O MESMO ESTILO DA HOME -->
                <a href="carrinho.php" class="cart-link">
                    <div class="cart-icon-wrapper">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-count-badge" id="cartCountBadge">0</span>
                    </div>
                <a href="login.php">Login</a>
                </a>
            </div>
        </div>
    </div>
</header>


<main class="container">
    <div class="cart-hero">
        <h1>seu jardim <span>em compras</span> 🌿</h1>
        <div class="hero-sub">
            <p>Revise seus buquês favoritos, ajuste quantidades e finalize a compra.</p>
        </div>
    </div>

    <div id="cartContainer"></div>
</main>

<footer>
    <p>Flwrs — <span>“Flowers that feel like felling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<script src="js/carrinho.js"></script>
</body>
</html>