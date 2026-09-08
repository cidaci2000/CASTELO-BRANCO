<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>flwrs · seu carrinho de flores</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/carrinho.css">
</head>
<body>
<header>
    <div class="container header-flex">
        <div class="header-left">
            <a href="home.php" class="back-button">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div class="logo-area">
                <div class="logo-word">
                    flwrs <strong>·</strong>
                </div>
                <div class="tagline-header">
                    “Flowers that feel like feeling”
                </div>
            </div>
        </div>
        <nav class="nav-menu">
            <a href="produtos.php">Produtos</a>
            <a href="faq.php">FAQ de delivery</a>
            <a href="info.php">Sobre nós</a>
            <a href="carrinho.php" class="cart-link">
                <div class="cart-icon-wrapper">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count-badge" id="cartCountBadge">0</span>
                </div>
            </a>
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="cart-header">
        <h1><span>seu carrinho</span> · flores escolhidas com afeto</h1>
        <p>Revise seus buquês favoritos, ajuste quantidades e finalize a compra.</p>
    </section>

    <div id="cartContainer">
        <!-- Exemplo de item no carrinho -->
        <div class="cart-item">
            <button class="cart-item-remove" onclick="removerItem(this)">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="cart-item-image">🌸</div>
            <div class="cart-item-info">
                <div class="cart-item-name">campos de verão</div>
                <div class="cart-item-category">buquê afetivo</div>
            </div>
            <div class="cart-item-price">R$ 89,90</div>
            <div class="cart-item-actions">
                <div class="cart-item-quantity">
                    <button onclick="alterarQuantidade(this, -1)">−</button>
                    <span>1</span>
                    <button onclick="alterarQuantidade(this, 1)">+</button>
                </div>
                <div class="cart-item-subtotal">
                    subtotal: <strong>R$ 89,90</strong>
                </div>
            </div>
        </div>

        <div class="cart-item">
            <button class="cart-item-remove" onclick="removerItem(this)">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="cart-item-image">💐</div>
            <div class="cart-item-info">
                <div class="cart-item-name">rosas suaves</div>
                <div class="cart-item-category">buquê afetivo</div>
            </div>
            <div class="cart-item-price">R$ 129,90</div>
            <div class="cart-item-actions">
                <div class="cart-item-quantity">
                    <button onclick="alterarQuantidade(this, -1)">−</button>
                    <span>2</span>
                    <button onclick="alterarQuantidade(this, 1)">+</button>
                </div>
                <div class="cart-item-subtotal">
                    subtotal: <strong>R$ 259,80</strong>
                </div>
            </div>
        </div>

        <!-- Resumo do carrinho -->
        <div class="cart-summary">
            <div class="cart-summary-total">
                <label>Total do pedido</label>
                <span class="total-value">R$ 349,70</span>
            </div>
            <div class="cart-summary-actions">
                <a href="produtos.php" class="btn-continuar">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    continuar comprando
                </a>
                <button class="btn-checkout" onclick="finalizarCompra()">
                    finalizar compra
                </button>
            </div>
        </div>
    </div>
</main>

<footer>
    <p>flwrs — <span>“Flowers that feel like feeling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<script>
    // Funções para manipulação do carrinho
    function alterarQuantidade(button, delta) {
        const item = button.closest('.cart-item');
        const quantidadeSpan = item.querySelector('.cart-item-quantity span');
        const subtotalSpan = item.querySelector('.cart-item-subtotal strong');
        const precoSpan = item.querySelector('.cart-item-price');
        
        let quantidade = parseInt(quantidadeSpan.textContent);
        quantidade = Math.max(1, quantidade + delta);
        quantidadeSpan.textContent = quantidade;
        
        // Calcular subtotal
        const preco = parseFloat(precoSpan.textContent.replace('R$ ', '').replace(',', '.'));
        const subtotal = preco * quantidade;
        subtotalSpan.textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
        
        // Atualizar total geral
        atualizarTotal();
    }

    function removerItem(button) {
        const item = button.closest('.cart-item');
        item.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => {
            item.remove();
            atualizarTotal();
            atualizarBadge();
        }, 300);
    }

    function atualizarTotal() {
        const items = document.querySelectorAll('.cart-item');
        let total = 0;
        
        items.forEach(item => {
            const subtotalText = item.querySelector('.cart-item-subtotal strong').textContent;
            const subtotal = parseFloat(subtotalText.replace('R$ ', '').replace(',', '.'));
            total += subtotal;
        });
        
        const totalElement = document.querySelector('.total-value');
        if (totalElement) {
            totalElement.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
        }
        
        // Se não houver itens, mostrar carrinho vazio
        if (items.length === 0) {
            mostrarCarrinhoVazio();
        }
    }

    function atualizarBadge() {
        const items = document.querySelectorAll('.cart-item');
        const badge = document.getElementById('cartCountBadge');
        if (badge) {
            badge.textContent = items.length;
        }
    }

    function mostrarCarrinhoVazio() {
        const container = document.getElementById('cartContainer');
        container.innerHTML = `
            <div class="cart-empty">
                <span class="cart-empty-icon">🌸</span>
                <h2>seu carrinho está vazio</h2>
                <p>Que tal escolher um buquê para levar um pouco de afeto?</p>
                <a href="produtos.php" class="btn-explorar">explorar flores</a>
            </div>
        `;
    }

    function finalizarCompra() {
        window.location.href = 'final_comp.php';
    }

    // Inicializar ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        atualizarBadge();
    });

    // Animação de saída
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(30px);
            }
        }
    `;
    document.head.appendChild(style);
</script>

</body>
</html>