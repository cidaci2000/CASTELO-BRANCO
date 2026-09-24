<?php
require_once '../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$pedido = $id ? getPedido($id) : null;

if (!$pedido) {
    setFlash('erro', 'Pedido não encontrado.');
    redirect('carrinho.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido #<?= $pedido['id'] ?> confirmado · flwrs</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="../css/carrinho.css">
    <style>
        body { background:#fdf6f5; font-family:'Segoe UI',sans-serif; }
        .container { max-width:800px; margin:0 auto; padding:2rem; }
        .card { background:#fff; border-radius:20px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,.08); }
        .check { font-size:4rem; text-align:center; display:block; margin-bottom:1rem; }
        h1 { text-align:center; margin-bottom:.5rem; }
        .sub { text-align:center; color:#777; margin-bottom:2rem; }
        table { width:100%; border-collapse:collapse; margin-top:1rem; }
        th, td { padding:.7rem; text-align:left; border-bottom:1px solid #f5d5d0; }
        th { background:#fdf6f5; font-weight:600; }
        .total { text-align:right; font-size:1.3rem; font-weight:700; color:#e8857d; margin-top:1rem; }
        .btn { display:inline-block; padding:.8rem 1.6rem; background:linear-gradient(135deg,#e8857d,#f4a8a0);
               color:#fff; border-radius:50px; text-decoration:none; font-weight:600; margin-top:1.5rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <span class="check">🌸</span>
        <h1>Obrigado pela sua compra!</h1>
        <p class="sub">Pedido <strong>#<?= $pedido['id'] ?></strong> registrado em <?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></p>

        <table>
            <thead>
                <tr><th>Produto</th><th>Qtd</th><th>Preço</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                <?php foreach ($pedido['itens'] as $i): ?>
                    <tr>
                        <td><?= e($i['nome_produto']) ?></td>
                        <td><?= (int)$i['quantidade'] ?></td>
                        <td>R$ <?= number_format($i['preco_unitario'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($i['subtotal'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="total">Total: R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>

        <div style="text-align:center;">
            <a href="produtos.php" class="btn">continuar comprando</a>
        </div>
    </div>
</div>
</body>
</html>