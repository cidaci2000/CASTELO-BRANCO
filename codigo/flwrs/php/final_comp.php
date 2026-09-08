<?php
// finalizar_compra.php - Versão com banco de dados
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}

$erro = '';
$sucesso = '';

// Buscar dados do usuário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

// Buscar endereços do usuário
$stmt = $pdo->prepare("SELECT * FROM enderecos WHERE usuario_id = ? ORDER BY principal DESC");
$stmt->execute([$_SESSION['usuario_id']]);
$enderecos = $stmt->fetchAll();

// Buscar itens do carrinho
$carrinho = $_SESSION['carrinho'] ?? [];

if (empty($carrinho)) {
    header('Location: carrinho.php');
    exit;
}

// Calcular total
$total = 0;
foreach ($carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// Processar finalização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $endereco_id = $_POST['endereco_id'] ?? 0;
    $metodo_pagamento = $_POST['pagamento'] ?? '';
    $recado = trim($_POST['recado'] ?? '');
    
    if (!$endereco_id) {
        $erro = "Selecione um endereço de entrega";
    } elseif (!$metodo_pagamento) {
        $erro = "Selecione um método de pagamento";
    } else {
        try {
            // Iniciar transação
            $pdo->beginTransaction();
            
            // Buscar endereço
            $stmt = $pdo->prepare("SELECT * FROM enderecos WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$endereco_id, $_SESSION['usuario_id']]);
            $endereco = $stmt->fetch();
            
            if (!$endereco) {
                throw new Exception("Endereço inválido");
            }
            
            // Inserir pedido
            $sql = "INSERT INTO pedidos (usuario_id, endereco_id, metodo_pagamento, recado, total, status, data_pedido) 
                    VALUES (?, ?, ?, ?, ?, 'pendente', NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_SESSION['usuario_id'],
                $endereco_id,
                $metodo_pagamento,
                $recado,
                $total
            ]);
            $pedido_id = $pdo->lastInsertId();
            
            // Inserir itens do pedido
            foreach ($carrinho as $item) {
                $sql = "INSERT INTO pedido_itens (pedido_id, produto_nome, produto_preco, quantidade, subtotal) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $pedido_id,
                    $item['nome'],
                    $item['preco'],
                    $item['quantidade'],
                    $item['preco'] * $item['quantidade']
                ]);
            }
            
            // Confirmar transação
            $pdo->commit();
            
            // LIMPAR CARRINHO
            unset($_SESSION['carrinho']);
            
            // Mensagem de sucesso
            $_SESSION['pedido_id'] = $pedido_id;
            header('Location: pedido_confirmado.php');
            exit;
            
        } catch(Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao finalizar pedido: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flwrs · finalizar compra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Seu CSS aqui -->
</head>
<body>
    <!-- Seu HTML com os dados do PHP -->
    <?php if ($erro): ?>
        <div class="error">⚠️ <?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <!-- Campos do formulário com valores preenchidos -->
        
        <!-- Exemplo de endereços -->
        <select name="endereco_id" required>
            <option value="">Selecione um endereço</option>
            <?php foreach ($enderecos as $endereco): ?>
                <option value="<?php echo $endereco['id']; ?>">
                    <?php echo htmlspecialchars($endereco['logradouro'] . ', ' . $endereco['numero'] . ' - ' . $endereco['cidade'] . '/' . $endereco['estado']); ?>
                    <?php if ($endereco['principal']): ?> (Principal)<?php endif; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <!-- Métodos de pagamento -->
        <div class="payment-methods">
            <label>
                <input type="radio" name="pagamento" value="pix" required>
                PIX
            </label>
            <label>
                <input type="radio" name="pagamento" value="cartao">
                Cartão
            </label>
            <label>
                <input type="radio" name="pagamento" value="boleto">
                Boleto
            </label>
        </div>
        
        <!-- Recado -->
        <textarea name="recado" placeholder="Recado personalizado (opcional)"></textarea>
        
        <button type="submit">Finalizar Pedido</button>
    </form>
</body>
</html>