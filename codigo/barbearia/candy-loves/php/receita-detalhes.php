<?php
require_once 'config/db_connection.php';

$receita_id = $_GET['id'] ?? null;

if (!$receita_id) {
    header("Location: receitas-dinamica.php");
    exit;
}

// Buscar receita
$stmt = $pdo->prepare("
    SELECT r.*, c.nome as categoria_nome, c.icone as categoria_icone
    FROM receitas r
    LEFT JOIN categorias c ON r.categoria_id = c.id
    WHERE r.id = ? AND r.ativo = TRUE
");
$stmt->execute([$receita_id]);
$receita = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$receita) {
    header("Location: receitas-dinamica.php");
    exit;
}

// Buscar ingredientes
$stmt = $pdo->prepare("
    SELECT * FROM ingredientes WHERE receita_id = ? ORDER BY id ASC
");
$stmt->execute([$receita_id]);
$ingredientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar modo de preparo
$stmt = $pdo->prepare("
    SELECT * FROM modo_preparo WHERE receita_id = ? ORDER BY passo ASC
");
$stmt->execute([$receita_id]);
$modo_preparo = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($receita['titulo']); ?> - Candy's Love's</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f9f5f2 0%, #f5f0ed 100%);
            color: #3b2c2c;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fff;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 8px 24px rgba(184, 59, 94, 0.15);
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            cursor: pointer;
        }

        .header h1:hover {
            text-decoration: underline;
        }

        .back-btn {
            background: linear-gradient(135deg, #fcd5ce 0%, #fcc0b0 100%);
            color: #b83b5e;
            border: none;
            padding: 12px 22px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(184, 59, 94, 0.2);
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

        .recipe-image {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(90, 70, 70, 0.15);
            height: fit-content;
        }

        .recipe-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .recipe-info {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .recipe-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: #b83b5e;
            margin-bottom: 10px;
        }

        .recipe-header .categoria {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            padding: 10px 15px;
            border-radius: 25px;
            width: fit-content;
            color: #b83b5e;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .recipe-header .categoria-icon {
            font-size: 1.3rem;
        }

        .recipe-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .meta-item {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 6px 16px rgba(90, 70, 70, 0.08);
        }

        .meta-item i {
            font-size: 1.8rem;
            color: #b83b5e;
            margin-bottom: 8px;
        }

        .meta-item .label {
            color: #8b7575;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .meta-item .value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #3b2c2c;
        }

        .recipe-description {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            padding: 25px;
            border-radius: 15px;
            line-height: 1.8;
            color: #6e5f5f;
            font-size: 1.05rem;
            box-shadow: 0 6px 16px rgba(90, 70, 70, 0.08);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: #fffaf7;
            box-shadow: 0 6px 16px rgba(184, 59, 94, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(184, 59, 94, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #fcd5ce 0%, #fcc0b0 100%);
            color: #b83b5e;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.1);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(184, 59, 94, 0.2);
        }

        .section {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 8px 24px rgba(90, 70, 70, 0.1);
        }

        .section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #b83b5e;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #b83b5e;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section h2 i {
            font-size: 1.5rem;
        }

        .ingredientes-list {
            list-style: none;
        }

        .ingredientes-list li {
            padding: 15px;
            margin-bottom: 10px;
            background: rgba(184, 59, 94, 0.05);
            border-radius: 10px;
            border-left: 4px solid #b83b5e;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
        }

        .ingredientes-list li:before {
            content: "✓";
            color: #b83b5e;
            font-weight: bold;
            font-size: 1.3rem;
            width: 24px;
            text-align: center;
        }

        .ingredientes-list li strong {
            color: #3b2c2c;
        }

        .ingredientes-list li span {
            color: #8b7575;
        }

        .modo-preparo-list {
            list-style: none;
            counter-reset: step-counter;
        }

        .modo-preparo-list li {
            padding: 20px;
            margin-bottom: 15px;
            background: rgba(184, 59, 94, 0.05);
            border-radius: 10px;
            border-left: 4px solid #b83b5e;
            position: relative;
            padding-left: 50px;
        }

        .modo-preparo-list li:before {
            counter-increment: step-counter;
            content: counter(step-counter);
            position: absolute;
            left: 10px;
            top: 15px;
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .modo-preparo-list li p {
            line-height: 1.8;
            color: #6e5f5f;
            font-size: 1.05rem;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }

            .recipe-header h1 {
                font-size: 2rem;
            }

            .recipe-meta {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        footer {
            text-align: center;
            padding: 40px 30px;
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fffaf7;
            border-radius: 20px 20px 0 0;
            margin-top: 80px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <header class="header">
        <h1 onclick="window.location.href='cliente-dashboard.php'">🍓 Candy's Love's</h1>
        <a href="receitas-dinamica.php" class="back-btn">
            <i class="fas fa-chevron-left"></i> Voltar
        </a>
    </header>

    <div class="main-content">
        <div class="recipe-image">
            <img src="<?php echo htmlspecialchars($receita['imagem_url'] ?? 'https://via.placeholder.com/500x500'); ?>" 
                 alt="<?php echo htmlspecialchars($receita['titulo']); ?>"
                 onerror="this.src='https://via.placeholder.com/500x500'">
        </div>

        <div class="recipe-info">
            <div class="recipe-header">
                <h1><?php echo htmlspecialchars($receita['titulo']); ?></h1>
                <?php if ($receita['categoria_nome']): ?>
                    <div class="categoria">
                        <span class="categoria-icon"><?php echo $receita['categoria_icone']; ?></span>
                        <span><?php echo htmlspecialchars($receita['categoria_nome']); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="recipe-meta">
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <div class="label">Tempo de Preparo</div>
                    <div class="value"><?php echo intval($receita['tempo_preparo']); ?> min</div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-fire"></i>
                    <div class="label">Tempo de Cozimento</div>
                    <div class="value"><?php echo intval($receita['tempo_cozimento']); ?> min</div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-chart-pie"></i>
                    <div class="label">Dificuldade</div>
                    <div class="value"><?php echo htmlspecialchars($receita['dificuldade']); ?></div>
                </div>
            </div>

            <?php if ($receita['descricao']): ?>
                <div class="recipe-description">
                    <strong>Sobre esta receita:</strong><br><br>
                    <?php echo htmlspecialchars($receita['descricao']); ?>
                </div>
            <?php endif; ?>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="alert('Funcionalidade de impressão em desenvolvimento!')">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button class="btn btn-secondary" onclick="guardarReceita(<?php echo $receita['id']; ?>)">
                    <i class="fas fa-bookmark"></i> Guardar
                </button>
            </div>
        </div>
    </div>

    <?php if (!empty($ingredientes)): ?>
        <section class="section">
            <h2><i class="fas fa-list-check"></i> Ingredientes</h2>
            <ul class="ingredientes-list">
                <?php foreach ($ingredientes as $ing): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($ing['quantidade']); ?></strong>
                        <span><?php echo htmlspecialchars($ing['unidade']); ?></span> 
                        <strong><?php echo htmlspecialchars($ing['nome']); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (!empty($modo_preparo)): ?>
        <section class="section">
            <h2><i class="fas fa-chef"></i> Modo de Preparo</h2>
            <ol class="modo-preparo-list">
                <?php foreach ($modo_preparo as $passo): ?>
                    <li>
                        <p><?php echo htmlspecialchars($passo['descricao']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </section>
    <?php endif; ?>

    <footer>
        <p>&copy; 2026 Candy's Love's. Todos os direitos reservados. ❤️</p>
    </footer>
</div>

<script>
function guardarReceita(receitaId) {
    // Guardar receita nos favoritos (localStorage como exemplo)
    let receitas_salvas = JSON.parse(localStorage.getItem('receitas_salvas') || '[]');
    
    if (!receitas_salvas.includes(receitaId)) {
        receitas_salvas.push(receitaId);
        localStorage.setItem('receitas_salvas', JSON.stringify(receitas_salvas));
        alert('✓ Receita guardada com sucesso!');
    } else {
        alert('ℹ Esta receita já está nos seus favoritos!');
    }
}
</script>

</body>
</html>
