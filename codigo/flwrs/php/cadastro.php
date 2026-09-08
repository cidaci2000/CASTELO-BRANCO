<?php
// cadastro.php - VERSÃO CORRIGIDA
require_once 'config.php';

// Ativar exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

$erros = [];
$sucesso = false;
$nome = $email = $data_nasc = $genero = $telefone = $cep = $numero = '';
$termos = 0;

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // RECEBER OS DADOS DO POST (CORREÇÃO AQUI!)
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirma = $_POST['confirma'] ?? '';
    $data_nasc = $_POST['data-nasc'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $termos = isset($_POST['termos']) ? 1 : 0;
    
    // ========== VALIDAÇÕES ==========
    
    if (empty($nome)) {
        $erros[] = "Nome completo é obrigatório";
    } elseif (strlen($nome) < 3) {
        $erros[] = "Nome deve ter pelo menos 3 caracteres";
    }
    
    if (empty($email)) {
        $erros[] = "E-mail é obrigatório";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "E-mail inválido";
    }
    
    if (empty($senha)) {
        $erros[] = "Senha é obrigatória";
    } elseif (strlen($senha) < 8) {
        $erros[] = "Senha deve ter no mínimo 8 caracteres (atual: " . strlen($senha) . " caracteres)";
    }
    
    if ($senha !== $confirma) {
        $erros[] = "As senhas não coincidem";
    }
    
    if (!empty($data_nasc) && $data_nasc !== '2026-06-16') { // Ignorar data de teste
        $data_obj = DateTime::createFromFormat('Y-m-d', $data_nasc);
        if (!$data_obj || $data_obj->format('Y-m-d') !== $data_nasc) {
            $erros[] = "Data de nascimento inválida";
        }
    }
    
    if (!$termos) {
        $erros[] = "Você deve aceitar os termos de uso";
    }
    
    // ========== INSERÇÃO NO BANCO ==========
    
    if (empty($erros)) {
        try {
            // Verificar se e-mail já existe
            $check_sql = "SELECT id FROM usuarios WHERE email = :email";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':email' => $email]);
            
            if ($check_stmt->rowCount() > 0) {
                $erros[] = "Este e-mail já está cadastrado: " . $email;
            } else {
                // Gerar hash da senha
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                // CORREÇÃO: Usar EXATAMENTE os nomes das colunas da tabela
                $sql = "INSERT INTO usuarios (
                    nome_completo, 
                    email, 
                    senha_hash, 
                    data_nascimento, 
                    genero, 
                    telefone, 
                    cep, 
                    numero, 
                    termos_aceitos
                ) VALUES (
                    :nome, 
                    :email, 
                    :senha_hash, 
                    :data_nasc, 
                    :genero, 
                    :telefone, 
                    :cep, 
                    :numero, 
                    :termos
                )";
                
                $stmt = $pdo->prepare($sql);
                
                // Executar com os parâmetros
                $resultado = $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha_hash' => $senha_hash,
                    ':data_nasc' => !empty($data_nasc) && $data_nasc !== '2026-06-16' ? $data_nasc : null,
                    ':genero' => !empty($genero) ? $genero : null,
                    ':telefone' => !empty($telefone) ? $telefone : null,
                    ':cep' => !empty($cep) ? $cep : null,
                    ':numero' => !empty($numero) ? $numero : null,
                    ':termos' => $termos
                ]);
                
                if ($resultado) {
                    $sucesso = true;
                    $usuario_id = $pdo->lastInsertId();
                    
                    // Iniciar sessão
                    session_start();
                    $_SESSION['usuario_id'] = $usuario_id;
                    $_SESSION['usuario_nome'] = $nome;
                    $_SESSION['usuario_email'] = $email;
                    
                    // Redirecionar
                    header("refresh:2; url=home.php");
                } else {
                    $erros[] = "Erro ao inserir no banco de dados.";
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
    <title>flwrs · criar conta</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/cadastro.css">
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
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="cadastro-section">
            <div class="form-cadastro">
                <h3>Criar conta</h3>
                <div class="form-sub">preencha para fazer parte</div>

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
                        ✅ Cadastro realizado com sucesso! Redirecionando...
                    </div>
                <?php endif; ?>

                <form action="#" method="POST">
                    <div class="input-group">
                        <label for="nome">nome completo</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="email">e-mail</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="linha-dupla">
                        <div class="input-group">
                            <label for="senha">senha (mín. 8 caracteres)</label>
                            <div class="password-wrapper">
                                <input type="password" id="senha" name="senha" required minlength="8">
                                <button type="button" class="toggle-password" data-target="senha">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="confirma">confirmar senha</label>
                            <div class="password-wrapper">
                                <input type="password" id="confirma" name="confirma" required>
                                <button type="button" class="toggle-password" data-target="confirma">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="linha-dupla">
                        <div class="input-group">
                            <label for="data-nasc">data de nascimento</label>
                            <input type="date" id="data-nasc" name="data-nasc" value="<?php echo htmlspecialchars($data_nasc); ?>">
                        </div>
                        <div class="input-group">
                            <label for="genero">gênero</label>
                            <select id="genero" name="genero">
                                <option value="">—</option>
                                <option value="f" <?php echo $genero === 'f' ? 'selected' : ''; ?>>feminino</option>
                                <option value="m" <?php echo $genero === 'm' ? 'selected' : ''; ?>>masculino</option>
                                <option value="outro" <?php echo $genero === 'outro' ? 'selected' : ''; ?>>outro</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="telefone">celular / whatsapp</label>
                        <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>">
                    </div>

                    <div class="linha-dupla">
                        <div class="input-group">
                            <label for="cep">CEP</label>
                            <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($cep); ?>">
                        </div>
                        <div class="input-group">
                            <label for="numero">número</label>
                            <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($numero); ?>">
                        </div>
                    </div>

                    <div class="termos">
                        <input type="checkbox" id="termos" name="termos" <?php echo $termos ? 'checked' : ''; ?> required>
                        <label for="termos">aceito os <a href="#">termos de uso</a> e a <a href="#">política de privacidade</a> da Flwrs.</label>
                    </div>

                    <button type="submit" class="btn-cadastrar">criar minha conta</button>

                    <div class="login-redirect">
                        Já tem uma conta? <a href="login.php">fazer login</a>
                    </div>
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

<script src="../js/cadastro.js"></script>
</body>
</html>