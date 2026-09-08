<?php
// cad_produto.php - Cadastro de produtos SEM redimensionamento (não precisa de GD)
require_once 'config.php';

// Ativar exibição de erros para debug (remover em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$erros = [];
$sucesso = false;
$nome = $desc = $modelo = $preco = '';
$imagem_nome = '';

// Criar diretório de uploads se não existir
$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Configurações de upload
$tipos_permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
$tamanho_maximo = 5 * 1024 * 1024; // 5MB

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // RECEBER OS DADOS DO POST
    $nome = trim($_POST['nome'] ?? '');
    $desc = trim($_POST['desc'] ?? '');
    $modelo = $_POST['modelo'] ?? '';
    $preco = $_POST['preco'] ?? '';
    
    // ========== VALIDAÇÕES ==========
    
    // Validar nome do produto
    if (empty($nome)) {
        $erros[] = "Nome do produto é obrigatório";
    } elseif (strlen($nome) < 3) {
        $erros[] = "Nome do produto deve ter pelo menos 3 caracteres";
    } elseif (strlen($nome) > 100) {
        $erros[] = "Nome do produto deve ter no máximo 100 caracteres";
    }
    
    // Validar descrição
    if (empty($desc)) {
        $erros[] = "Descrição é obrigatória";
    } elseif (strlen($desc) < 10) {
        $erros[] = "Descrição deve ter pelo menos 10 caracteres";
    }
    
    // Validar modelo
    $modelos_validos = ['ba', 'a', 'pe', 'be', 'pf'];
    if (empty($modelo)) {
        $erros[] = "Selecione um modelo de produto";
    } elseif (!in_array($modelo, $modelos_validos)) {
        $erros[] = "Modelo de produto inválido";
    }
    
    // Validar preço
    if (empty($preco)) {
        $erros[] = "Preço é obrigatório";
    } else {
        // Converter formato brasileiro para numérico
        $preco_limpo = str_replace('R$', '', $preco);
        $preco_limpo = str_replace(' ', '', $preco_limpo);
        $preco_limpo = str_replace('.', '', $preco_limpo);
        $preco_limpo = str_replace(',', '.', $preco_limpo);
        $preco = floatval($preco_limpo);
        
        if ($preco <= 0) {
            $erros[] = "Preço deve ser maior que zero";
        }
    }
    
    // ========== VALIDAÇÃO DA IMAGEM ==========
    $imagem_path = null;
    $imagem_tipo = null;
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['imagem'];
        $tipo_arquivo = $arquivo['type'];
        $tamanho_arquivo = $arquivo['size'];
        $tmp_nome = $arquivo['tmp_name'];
        
        // Verificar tipo do arquivo
        if (!in_array($tipo_arquivo, $tipos_permitidos)) {
            $erros[] = "Tipo de imagem não permitido. Use JPG, PNG, WEBP ou GIF.";
        }
        
        // Verificar tamanho do arquivo
        if ($tamanho_arquivo > $tamanho_maximo) {
            $erros[] = "Imagem muito grande. Máximo 5MB.";
        }
        
        // Se não houver erros, processar a imagem
        if (empty($erros)) {
            // Gerar nome único para a imagem
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $nome_imagem = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nome) . '.' . $extensao;
            $caminho_completo = $upload_dir . $nome_imagem;
            
            // Mover arquivo sem redimensionar
            if (move_uploaded_file($tmp_nome, $caminho_completo)) {
                $imagem_path = $caminho_completo;
                $imagem_tipo = $extensao;
            } else {
                $erros[] = "Erro ao salvar a imagem. Verifique as permissões da pasta uploads/";
            }
        }
    }
    // Imagem é opcional - não gera erro se não for enviada
    
    // ========== INSERÇÃO NO BANCO ==========
    
    if (empty($erros)) {
        try {
            // Verificar se o PDO está funcionando
            if (!$pdo) {
                $erros[] = "Erro na conexão com o banco de dados";
            } else {
                // Verificar se a tabela existe
                $check_table = $pdo->query("SHOW TABLES LIKE 'produtos'");
                if ($check_table->rowCount() == 0) {
                    $erros[] = "Tabela 'produtos' não encontrada. Execute o SQL para criar a tabela.";
                } else {
                    // Preparar INSERT com imagem
                    $sql = "INSERT INTO produtos (
                        nome, 
                        descricao, 
                        modelo, 
                        preco,
                        imagem,
                        imagem_tipo
                    ) VALUES (
                        :nome, 
                        :descricao, 
                        :modelo, 
                        :preco,
                        :imagem,
                        :imagem_tipo
                    )";
                    
                    $stmt = $pdo->prepare($sql);
                    
                    // Executar com os parâmetros
                    $resultado = $stmt->execute([
                        ':nome' => $nome,
                        ':descricao' => $desc,
                        ':modelo' => $modelo,
                        ':preco' => $preco,
                        ':imagem' => $imagem_path,
                        ':imagem_tipo' => $imagem_tipo
                    ]);
                    
                    if ($resultado) {
                        $sucesso = true;
                        $produto_id = $pdo->lastInsertId();
                        
                        // Limpar formulário após sucesso
                        $nome = $desc = $modelo = $preco = '';
                        
                        // Mensagem de sucesso
                        echo "<script>alert('✅ Produto cadastrado com sucesso! ID: $produto_id');</script>";
                    } else {
                        $erros[] = "Erro ao inserir produto no banco de dados.";
                    }
                }
            }
            
        } catch(PDOException $e) {
            $erros[] = "Erro no banco de dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flwrs · cadastro de produtos</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/cad_produto.css">
</head>
<body>
    <header>
        <div class="container header-flex">
            <div class="header-left">
                <a href="admin.php" class="back-button">
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
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="cadastro-section">
            <div class="form-cadastro">
                <h3>Cadastrar produtos</h3>
                <div class="form-sub">preencha para cadastrar seu produto</div>

                <?php if (!empty($erros)): ?>
                    <div class="error-messages">
                        <ul>
                            <?php foreach ($erros as $erro): ?>
                                <li><?php echo htmlspecialchars($erro); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($sucesso): ?>
                    <div class="success-message">
                        ✅ Produto cadastrado com sucesso!
                    </div>
                <?php endif; ?>

                <form action="#" method="POST" enctype="multipart/form-data">
                    <div class="input-group">
                        <label for="nome">nome do produto</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Rosas Suaves" value="<?php echo htmlspecialchars($nome); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="desc">descrição</label>
                        <textarea id="desc" name="desc" rows="4" placeholder="Descreva o produto..." required><?php echo htmlspecialchars($desc); ?></textarea>
                    </div>

                    <div class="input-group">
                        <label for="modelo">modelo</label>
                        <select id="modelo" name="modelo" required>
                            <option value="" <?php echo empty($modelo) ? 'selected' : ''; ?>>Selecione um modelo</option>
                            <option value="ba" <?php echo $modelo === 'ba' ? 'selected' : ''; ?>>buquê afetivo</option>
                            <option value="a" <?php echo $modelo === 'a' ? 'selected' : ''; ?>>arranjo</option>
                            <option value="pe" <?php echo $modelo === 'pe' ? 'selected' : ''; ?>>presente especial</option>
                            <option value="be" <?php echo $modelo === 'be' ? 'selected' : ''; ?>>buquê especial</option>
                            <option value="pf" <?php echo $modelo === 'pf' ? 'selected' : ''; ?>>palavras em flor</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="preco">preço</label>
                        <div class="preco-wrapper">
                            <input type="text" id="preco" name="preco" placeholder="89,90" value="<?php echo htmlspecialchars($preco); ?>" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="imagem">imagem do produto (opcional)</label>
                        <input type="file" id="imagem" name="imagem" accept="image/jpeg, image/png, image/webp, image/gif" onchange="previewImagem(this)">
                        <div class="info-imagem">
                            Formatos permitidos: JPG, PNG, WEBP, GIF. Máximo 5MB.
                        </div>
                        <div id="preview" class="image-preview">
                            <img id="preview-img" src="#" alt="Preview da imagem">
                        </div>
                    </div>

                    <button type="submit" class="btn-cadastrar">cadastrar produto</button>
                </form>
            </div>
        </section>

        <div class="info-rodape">
            <span>🚚 FAQ de delivery — atualizado</span>
            <span>📮 central.flwrs@gmail.com</span>
        </div>
    </main>

<footer>
    <p>flwrs — <span>“Flowers that feel like feeling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<script src="../js/cad_produto"></script>
</body>
</html>