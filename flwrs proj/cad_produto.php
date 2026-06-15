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
    <title>Flwrs · cadastro de produtos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fefaf5;
            color: #4f4a45;
            line-height: 1.5;
        }
        
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        header {
            padding: 1.5rem 0 1rem;
            border-bottom: 1px solid rgba(183, 164, 160, 0.15);
            position: sticky;
            top: 0;
            background-color: #fefaf5f2;
            backdrop-filter: blur(12px);
            z-index: 100;
        }
        
        .header-flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .back-button {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #b2a19b;
            transition: color 0.2s;
        }
        
        .back-button:hover {
            color: #c06f8b;
        }
        
        .back-button .material-symbols-outlined {
            font-size: 2rem;
            font-weight: 300;
        }
        
        .logo-area {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }
        
        .logo-word {
            font-size: 2rem;
            font-weight: 300;
            letter-spacing: 2px;
            color: #4f4a45;
            text-transform: lowercase;
        }
        
        .logo-word strong {
            font-weight: 500;
            color: #c0859d;
        }
        
        .tagline-header {
            font-size: 0.7rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #b2a19b;
            border-left: 1px solid #f7d5e7;
            padding-left: 0.8rem;
            font-weight: 300;
        }
        
        .cadastro-section {
            display: flex;
            justify-content: center;
            padding: 3rem 0;
        }
        
        .form-cadastro {
            max-width: 600px;
            width: 100%;
            background: white;
            padding: 2rem;
            border-radius: 32px;
            border: 1px solid #f7e5df;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .form-cadastro h3 {
            font-size: 1.8rem;
            font-weight: 400;
            color: #4f4a45;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .form-sub {
            text-align: center;
            color: #b2a19b;
            margin-bottom: 2rem;
            font-size: 0.85rem;
        }
        
        .error-messages {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #c66;
        }
        
        .error-messages ul {
            margin-left: 1.5rem;
        }
        
        .success-message {
            background: #e6f7e6;
            border: 1px solid #9c9;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #484;
            text-align: center;
        }
        
        .input-group {
            margin-bottom: 1.2rem;
        }
        
        .input-group label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #b2a19b;
            margin-bottom: 0.3rem;
        }
        
        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #f7e5df;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            background: white;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        
        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: #c0859d;
            box-shadow: 0 0 0 3px rgba(192, 111, 139, 0.1);
        }
        
        .input-group input[type="file"] {
            padding: 0.5rem;
            background: #fefbf8;
            cursor: pointer;
        }
        
        .input-group input[type="file"]::-webkit-file-upload-button {
            background: #c0859d;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-right: 1rem;
            transition: background 0.2s;
        }
        
        .input-group input[type="file"]::-webkit-file-upload-button:hover {
            background: #a5657e;
        }
        
        .image-preview {
            margin-top: 0.5rem;
            display: none;
        }
        
        .image-preview img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 12px;
            border: 2px solid #f7e5df;
            padding: 0.25rem;
        }
        
        .btn-cadastrar {
            width: 100%;
            background: #c0859d;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 40px;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
        }
        
        .btn-cadastrar:hover {
            background: #a5657e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(192, 111, 139, 0.3);
        }
        
        .info-rodape {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            padding: 2rem 0;
            border-top: 1px solid #f7e5df;
            font-size: 0.75rem;
            color: #b2a19b;
        }
        
        footer {
            text-align: center;
            padding: 2rem;
            border-top: 1px solid #f7e5df;
            font-size: 0.75rem;
            color: #b2a19b;
        }
        
        footer span {
            color: #c0859d;
        }
        
        .preco-wrapper {
            position: relative;
        }
        
        .preco-wrapper::before {
            content: "R$";
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #b2a19b;
            font-size: 0.9rem;
        }
        
        .preco-wrapper input {
            padding-left: 35px;
        }
        
        .info-imagem {
            font-size: 0.7rem;
            color: #b2a19b;
            margin-top: 0.3rem;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .form-cadastro {
                padding: 1.5rem;
            }
            
            .form-cadastro h3 {
                font-size: 1.5rem;
            }
        }
    </style>
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
                        Flwrs <strong>·</strong>
                    </div>
                    <div class="tagline-header">
                        “Flowers that feel like felling”
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
        <p>Flwrs — <span>“Flowers that feel like felling”</span> — pequenos gestos, memórias eternas</p>
    </footer>

    <script>
        // Máscara para o campo de preço
        const precoInput = document.getElementById('preco');
        
        if (precoInput) {
            precoInput.addEventListener('input', function(e) {
                let valor = e.target.value;
                valor = valor.replace(/[^\d]/g, '');
                if (valor) {
                    let numero = (parseInt(valor) / 100).toFixed(2);
                    numero = numero.replace('.', ',');
                    e.target.value = numero;
                }
            });
        }
        
        // Preview da imagem
        function previewImagem(input) {
            const preview = document.getElementById('preview');
            const previewImg = document.getElementById('preview-img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
                previewImg.src = '#';
            }
        }
        
        // Auto-fade para mensagem de sucesso
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>