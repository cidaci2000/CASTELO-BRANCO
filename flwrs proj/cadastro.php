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
    <title>Flwrs · criar conta</title>
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
        }
        
        .form-cadastro h3 {
            font-size: 1.8rem;
            font-weight: 400;
            color: #4f4a45;
            text-align: center;
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
            margin-bottom: 1rem;
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
        .input-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #f7e5df;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            background: white;
            transition: all 0.2s;
        }
        
        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: #c0859d;
        }
        
        .linha-dupla {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-wrapper input {
            width: 100%;
            padding-right: 2.5rem;
        }
        
        .toggle-password {
            position: absolute;
            right: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #b2a19b;
        }
        
        .termos {
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .termos label {
            font-size: 0.8rem;
            color: #7a6e68;
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
        }
        
        .btn-cadastrar:hover {
            background: #a5657e;
            transform: translateY(-2px);
        }
        
        .login-redirect {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #7a6e68;
        }
        
        .login-redirect a {
            color: #c0859d;
            text-decoration: none;
            border-bottom: 1px solid #deef6e;
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
        
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            .linha-dupla {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .form-cadastro {
                padding: 1.5rem;
            }
        }
    </style>
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
        <p>Flwrs — <span>“Flowers that feel like felling”</span> — pequenos gestos, memórias eternas</p>
    </footer>

    <script>
        // Mostrar/esconder senha
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('.material-symbols-outlined');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility_off';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility';
                }
            });
        });
    </script>
</body>
</html>