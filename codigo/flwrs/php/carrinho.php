<?php
require_once '../includes/functions.php';
$itens = getCarrinho();
$total = totalCarrinho();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>flwrs · seu carrinho de flores</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/carrinho.css">
    <style>
        .notificacao {
            position: fixed; top: 80px; right: 20px;
            padding: 1rem 1.5rem; border-radius: 8px;
            color: #fff; font-weight: 600; z-index: 2000;
            box-shadow: 0 4px 20px rgba(0,0,0,.1);
            animation: slideIn .3s ease-out;
        }
        .notificacao.sucesso { background: linear-gradient(135deg, #5FA86D, #4A8A58); }
        .notificacao.erro    { background: linear-gradient(135deg, #D64545, #B83535); }
        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        .cart-empty {
            text-align: center; padding: 3rem 1rem;
            background: #fff; border-radius: 20px;
        }
        .cart-empty-icon { font-size: 4rem; display: block; margin-bottom: 1rem; }
        .cart-empty h2 { margin-bottom: .5rem; font-weight: 400; }
        .cart-empty p { color: #777; margin-bottom: 1.5rem; }
        .btn-explorar {
            display: inline-block; padding: .8rem 1.6rem;
            background: linear-gradient(135deg, #e8857d, #f4a8a0);
            color: #fff; border-radius: 50px; text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<?php if ($flash): ?>
    <div class="notificacao <?= e($flash['tipo']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<header>
    <div class="container header-flex">
        <div class="header-left">
            <a href="home.php" class="back-button">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div class="logo-area">
                <div class="logo-word">flwrs <strong>·</strong></div>
                <div class="tagline-header">"Flowers that feel like feeling"</div>
            </div>
        </div>
        <nav class="nav-menu">
            <a href="produtos.php">Produtos</a>
            <a href="faq.php">FAQ de delivery</a>
            <a href="info.php">Sobre nós</a>
            <a href="carrinho.php" class="cart-link">
                <div class="cart-icon-wrapper">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count-badge"><?= countCarrinho() ?></span>
                </div>
            </a>
            <?php if (isLogged()): ?>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">
    <section class="cart-header">
        <h1><span>seu carrinho</span> · flores escolhidas com afeto</h1>
        <p>Revise seus buquês favoritos, ajuste quantidades e finalize a compra.</p>
    </section>

    <div id="cartContainer">
        <?php if (empty($itens)): ?>
            <div class="cart-empty">
                <span class="cart-empty-icon">🌸</span>
                <h2>seu carrinho está vazio</h2>
                <p>Que tal escolher um buquê para levar um pouco de afeto?</p>
                <a href="produtos.php" class="btn-explorar">explorar flores</a>
            </div>
        <?php else: ?>

            <form method="POST" action="carrinho_action.php" id="formCarrinho">
                <input type="hidden" name="acao" value="atualizar">

                <?php foreach ($itens as $i):
                    $subtotal = (float)$i['preco'] * (int)$i['quantidade'];
                ?>
                    <div class="cart-item">
                        <button type="button" class="cart-item-remove"
                                onclick="removerItem(<?= $i['item_id'] ?>)">
                            <span class="material-symbols-outlined">close</span>
                        </button>

                        <div class="cart-item-image">
                            <?php if (!empty($i['imagem'])): ?>
                                <img src="<?= e(imagemUrl($i['imagem'])) ?>" alt="<?= e($i['nome']) ?>"
                                     style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                            <?php else: ?>
                                🌸
                            <?php endif; ?>
                        </div>

                        <div class="cart-item-info">
                            <div class="cart-item-name"><?= e($i['nome']) ?></div>
                            <div class="cart-item-category">buquê afetivo</div>
                        </div>

                        <div class="cart-item-price">
                            R$ <?= number_format($i['preco'], 2, ',', '.') ?>
                        </div>

                        <div class="cart-item-actions">
                            <div class="cart-item-quantity">
                                <button type="button" onclick="alterarQtd(<?= $i['item_id'] ?>, -1)">−</button>
                                <input type="number" name="qtd[<?= $i['item_id'] ?>]"
                                       value="<?= (int)$i['quantidade'] ?>"
                                       min="1" max="99"
                                       style="width:50px;text-align:center;border:none;background:transparent;font-weight:600;"
                                       onchange="this.form.submit()">
                                <button type="button" onclick="alterarQtd(<?= $i['item_id'] ?>, 1)">+</button>
                            </div>
                            <div class="cart-item-subtotal">
                                subtotal: <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="cart-summary">
                    <div class="cart-summary-total">
                        <label>Total do pedido</label>
                        <span class="total-value">R$ <?= number_format($total, 2, ',', '.') ?></span>
                    </div>
                    <div class="cart-summary-actions">
                        <a href="produtos.php" class="btn-continuar">
                            <span class="material-symbols-outlined">shopping_bag</span>
                            continuar comprando
                        </a>
                        <button type="button" class="btn-checkout" onclick="finalizarCompra()">
                            finalizar compra
                        </button>
                    </div>
                </div>
            </form>

            <!-- Formulário oculto para finalizar -->
            <form method="POST" action="carrinho_action.php" id="formFinalizar" style="display:none;">
                <input type="hidden" name="acao" value="finalizar">
            </form>

            <!-- Formulário oculto para remover item -->
            <form method="POST" action="carrinho_action.php" id="formRemover" style="display:none;">
                <input type="hidden" name="acao" value="remover">
                <input type="hidden" name="item_id" id="removerItemId">
            </form>

        <?php endif; ?>
    </div>
</main>

<footer>
    <p>flwrs — <span>"Flowers that feel like feeling"</span> — pequenos gestos, memórias eternas</p>
</footer>

<script>
    function alterarQtd(itemId, delta) {
        const input = document.querySelector('input[name="qtd[' + itemId + ']"]');
        let v = parseInt(input.value) || 1;
        v = Math.max(1, v + delta);
        input.value = v;
        input.form.submit();
    }

    function removerItem(itemId) {
        if (!confirm('Remover este item do carrinho?')) return;
        document.getElementById('removerItemId').value = itemId;
        document.getElementById('formRemover').submit();
    }

    function finalizarCompra() {
        if (!confirm('Confirmar finalização do pedido?')) return;
        document.getElementById('formFinalizar').submit();
    }

    // Auto-esconde notificação
    setTimeout(() => {
        const n = document.querySelector('.notificacao');
        if (n) n.style.display = 'none';
    }, 4000);
</script>

</body>
</html>