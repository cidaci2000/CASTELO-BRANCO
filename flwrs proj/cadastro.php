<?php
// cadastro.php - versão completa com validações
require_once 'config.php';

$erros = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitização dos campos
    $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $senha = $_POST['senha'] ?? '';
    $confirma = $_POST['confirma'] ?? '';
    $data_nasc = $_POST['data-nasc'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING));
    $cep = trim(filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_STRING));
    $numero = trim(filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_STRING));
    $termos = isset($_POST['termos']) ? 1 : 0;
    
    // ========== VALIDAÇÕES ==========
    
    // 1. Nome
    if (empty($nome)) {
        $erros[] = "Nome completo é obrigatório";
    } elseif (strlen($nome) < 3) {
        $erros[] = "Nome deve ter pelo menos 3 caracteres";
    }
    
    // 2. Email
    if (empty($email)) {
        $erros[] = "E-mail é obrigatório";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "E-mail inválido";
    }
    
    // 3. Senha
    if (empty($senha)) {
        $erros[] = "Senha é obrigatória";
    } elseif (strlen($senha) < 8) {
        $erros[] = "Senha deve ter no mínimo 8 caracteres";
    }
    
    // 4. Confirmar senha
    if ($senha !== $confirma) {
        $erros[] = "As senhas não coincidem";
    }
    
    // 5. Data de nascimento (se fornecida, validar formato)
    if (!empty($data_nasc)) {
        $data_obj = DateTime::createFromFormat('Y-m-d', $data_nasc);
        if (!$data_obj || $data_obj->format('Y-m-d') !== $data_nasc) {
            $erros[] = "Data de nascimento inválida";
        } else {
            // Verificar idade mínima (opcional: 13 anos)
            $hoje = new DateTime();
            $idade = $hoje->diff($data_obj)->y;
            if ($idade < 13) {
                $erros[] = "Você deve ter pelo menos 13 anos para se cadastrar";
            }
        }
    }
    
    // 6. Gênero (se for fornecido, validar opção)
    if (!empty($genero) && !in_array($genero, ['f', 'm', 'outro'])) {
        $erros[] = "Opção de gênero inválida";
    }
    
    // 7. Termos de uso
    if (!$termos) {
        $erros[] = "Você deve aceitar os termos de uso e política de privacidade";
    }
    
    // ========== INSERÇÃO NO BANCO ==========
    
    if (empty($erros)) {
        // Usar a variável $pdo do config.php em vez de criar Database()
        global $pdo;
        
        try {
            // Verificar se e-mail já existe
            $check_sql = "SELECT id FROM usuarios WHERE email = :email";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':email' => $email]);
            
            if ($check_stmt->rowCount() > 0) {
                $erros[] = "Este e-mail já está cadastrado. Faça login ou use outro e-mail.";
            } else {
                // Gerar hash da senha (NUNCA salvar em texto puro!)
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                // Preparar INSERT
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
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha_hash' => $senha_hash,
                    ':data_nasc' => !empty($data_nasc) ? $data_nasc : null,
                    ':genero' => !empty($genero) ? $genero : null,
                    ':telefone' => !empty($telefone) ? $telefone : null,
                    ':cep' => !empty($cep) ? $cep : null,
                    ':numero' => !empty($numero) ? $numero : null,
                    ':termos' => $termos
                ]);
                
                $sucesso = true;
                
                // Iniciar sessão e logar automaticamente
                session_start();
                $_SESSION['usuario_id'] = $pdo->lastInsertId();
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_email'] = $email;
                
                // Redirecionar após 2 segundos
                header("refresh:2; url=home.php");
            }
            
        } catch(PDOException $e) {
            $erros[] = "Erro ao cadastrar: " . $e->getMessage();
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
  <!-- mesma fonte da home -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
  <!-- Material Symbols para ícone de olho (leve e moderno) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #fefaf5;        /* base neutra quente */
      font-family: 'Inter', sans-serif;
      color: #2a2a2a;
      line-height: 1.5;
    }

    .container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    /* ===== HEADER ===== */
    header {
      padding: 1.5rem 0 1rem 0;
      border-bottom: 1px solid rgba(183, 164, 160, 0.15);
    }

    .header-flex {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
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
      margin-right: 0.5rem;
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
      font-size: 0.75rem;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #b2a19b;
      border-left: 1px solid #f7d5e7;
      padding-left: 0.8rem;
      margin-left: 0.3rem;
      font-weight: 300;
    }

    .nav-menu {
      display: flex;
      gap: 2.5rem;
      font-size: 0.9rem;
      font-weight: 400;
      text-transform: uppercase;
      letter-spacing: 1.2px;
    }

    .nav-menu a {
      text-decoration: none;
      color: #5e5a55;
      transition: color 0.2s;
      font-size: 0.85rem;
      border-bottom: 1px solid transparent;
      padding-bottom: 4px;
    }

    .nav-menu a:hover {
      color: #c06f8b;
      border-bottom-color: #f7d5e7;
    }

    /* ===== CADASTRO ===== */
    .cadastro-section {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 70vh;
      padding: 3rem 0 4rem 0;
    }

    .form-cadastro {
      background: #ffffffd9;
      backdrop-filter: blur(2px);
      border-radius: 32px 16px 32px 16px;
      padding: 2.8rem 2.5rem;
      box-shadow: 0 20px 35px -12px rgba(152, 123, 136, 0.2);
      border: 1px solid rgba(247, 213, 231, 0.6);
      width: 100%;
      max-width: 620px;
      margin: 0 auto;
    }

    .form-cadastro h3 {
      font-size: 2rem;
      font-weight: 320;
      color: #b4849a;
      margin-bottom: 0.2rem;
      letter-spacing: -0.5px;
      text-align: center;
    }

    .form-sub {
      color: #7f7269;
      font-weight: 300;
      font-size: 0.95rem;
      margin-bottom: 2.2rem;
      border-bottom: 1px dashed #deef6e;
      padding-bottom: 0.8rem;
      text-align: center;
    }

    /* Mensagens de erro */
    .error-messages {
      background-color: #ffe4e4;
      border-left: 4px solid #e74c3c;
      padding: 1rem;
      margin-bottom: 1.5rem;
      border-radius: 12px;
    }

    .error-messages ul {
      margin: 0;
      padding-left: 1.5rem;
    }

    .error-messages li {
      color: #c0392b;
      font-size: 0.85rem;
      margin: 0.25rem 0;
    }

    .success-message {
      background-color: #d4edda;
      border-left: 4px solid #28a745;
      padding: 1rem;
      margin-bottom: 1.5rem;
      border-radius: 12px;
      color: #155724;
      text-align: center;
    }

    .input-group {
      margin-bottom: 1.5rem;
    }

    .input-group label {
      display: block;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #a5657e;
      margin-bottom: 0.3rem;
      font-weight: 400;
    }

    /* container para input com ícone */
    .password-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .password-wrapper input {
      width: 100%;
      padding: 0.8rem 3rem 0.8rem 1.2rem;  /* espaço à direita para o ícone */
      background: #fefaf5;
      border: 1px solid #f7d5e7;
      border-radius: 50px;
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      color: #2a2a2a;
      outline: none;
      transition: 0.18s;
    }

    .password-wrapper input:focus {
      border-color: #deef6e;
      box-shadow: 0 0 0 3px #deef6e40;
      background: white;
    }

    .toggle-password {
      position: absolute;
      right: 1rem;
      background: none;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #b2a19b;
      font-size: 1.5rem;
      transition: color 0.2s;
      padding: 0;
      line-height: 1;
    }

    .toggle-password:hover {
      color: #c06f8b;
    }

    /* input normal (sem ícone) */
    .input-group input:not(.password-wrapper input), 
    .input-group select {
      width: 100%;
      padding: 0.8rem 1.2rem;
      background: #fefaf5;
      border: 1px solid #f7d5e7;
      border-radius: 50px;
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      color: #2a2a2a;
      outline: none;
      transition: 0.18s;
    }

    .input-group input:focus, .input-group select:focus {
      border-color: #deef6e;
      box-shadow: 0 0 0 3px #deef6e40;
      background: white;
    }

    .input-group input::placeholder {
      color: #b9aaa2;
      font-weight: 300;
      font-size: 0.9rem;
    }

    .linha-dupla {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .termos {
      display: flex;
      align-items: center;
      gap: 0.7rem;
      margin: 2rem 0 1.8rem;
    }

    .termos input {
      width: 1.2rem;
      height: 1.2rem;
      accent-color: #b2e4b3;
      margin: 0;
    }

    .termos label {
      font-size: 0.85rem;
      color: #6b5f57;
    }

    .termos a {
      color: #c06f8b;
      text-decoration: none;
      border-bottom: 1px solid #f7d5e7;
    }

    .btn-cadastrar {
      background: #b2e4b3;
      border: none;
      padding: 1rem 2rem;
      width: 100%;
      border-radius: 60px;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 2.5px;
      font-weight: 500;
      color: #2d3b2d;
      cursor: pointer;
      transition: all 0.25s;
      box-shadow: 0 4px 12px rgba(183, 208, 80, 0.3);
      border: 2px solid #b2e4b3;
    }

    .btn-cadastrar:hover {
      background: #deef6e;
      border-color: #deef6e;
      box-shadow: 0 10px 22px -8px #b2e4b3;
      color: #1e2b1e;
    }

    .login-redirect {
      text-align: center;
      margin-top: 2rem;
      font-size: 0.9rem;
      color: #8e8077;
    }

    .login-redirect a {
      color: #b4849a;
      text-decoration: none;
      font-weight: 500;
      border-bottom: 2px solid #f7d5e7;
    }

    .login-redirect a:hover {
      color: #9f6182;
      border-bottom-color: #deef6e;
    }

    .info-rodape {
      display: flex;
      gap: 1.5rem;
      justify-content: center;
      padding: 1rem 0 3rem 0;
      flex-wrap: wrap;
      margin-top: 1rem;
    }

    .info-rodape span {
      background: #f7d5e7; 
      color:#5a4b42; 
      padding: 0.3rem 1.5rem; 
      border-radius: 40px; 
      font-size: 0.8rem;
    }

    .info-rodape span:last-child {
      background: #91b691; 
      color:#2e472f;
    }

    footer {
      text-align: center;
      padding: 2rem 0 3rem 0;
      color: #a48d84;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      border-top: 1px solid rgba(183, 164, 160, 0.1);
    }

    footer span {
      color: #91b691;
      font-weight: 400;
    }

    /* responsivo */
    @media (max-width: 600px) {
      .form-cadastro {
        padding: 2rem 1.2rem;
      }
      .linha-dupla {
        grid-template-columns: 1fr;
        gap: 0;
      }
      .header-flex {
        flex-direction: column;
        gap: 1rem;
      }
      .nav-menu {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="container header-flex">
      <div class="header-left">
        <!-- Seta de voltar -->
        <a href="home.php" class="back-button">
          <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <!-- Logo -->
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

        <!-- Exibir mensagens de erro -->
        <?php if (!empty($erros)): ?>
          <div class="error-messages">
            <ul>
              <?php foreach ($erros as $erro): ?>
                <li><?php echo htmlspecialchars($erro); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Exibir mensagem de sucesso -->
        <?php if ($sucesso): ?>
          <div class="success-message">
            ✅ Cadastro realizado com sucesso! Redirecionando...
          </div>
        <?php endif; ?>

        <form action="#" method="POST">
          <div class="input-group">
            <label for="nome">nome completo</label>
            <input type="text" id="nome" name="nome" placeholder="" value="<?php echo htmlspecialchars($nome ?? ''); ?>" required>
          </div>

          <div class="input-group">
            <label for="email">e-mail</label>
            <input type="email" id="email" name="email" placeholder="central.flwrs@gmail.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
          </div>

          <!-- campo senha com olho -->
          <div class="linha-dupla">
            <div class="input-group">
              <label for="senha">senha</label>
              <div class="password-wrapper">
                <input type="password" id="senha" name="senha" placeholder="mín. 8 caracteres" required minlength="8">
                <button type="button" class="toggle-password" data-target="senha" aria-label="mostrar/esconder senha">
                  <span class="material-symbols-outlined">visibility</span>
                </button>
              </div>
            </div>
            <div class="input-group">
              <label for="confirma">confirmar senha</label>
              <div class="password-wrapper">
                <input type="password" id="confirma" name="confirma" placeholder="digite novamente" required>
                <button type="button" class="toggle-password" data-target="confirma" aria-label="mostrar/esconder senha">
                  <span class="material-symbols-outlined">visibility</span>
                </button>
              </div>
            </div>
          </div>

          <div class="linha-dupla">
            <div class="input-group">
              <label for="data-nasc">data de nascimento</label>
              <input type="date" id="data-nasc" name="data-nasc" value="<?php echo htmlspecialchars($data_nasc ?? ''); ?>">
            </div>
            <div class="input-group">
              <label for="genero">gênero (opcional)</label>
              <select id="genero" name="genero">
                <option value="" <?php echo empty($genero) ? 'selected' : ''; ?>>—</option>
                <option value="f" <?php echo ($genero ?? '') === 'f' ? 'selected' : ''; ?>>feminino</option>
                <option value="m" <?php echo ($genero ?? '') === 'm' ? 'selected' : ''; ?>>masculino</option>
                <option value="outro" <?php echo ($genero ?? '') === 'outro' ? 'selected' : ''; ?>>outro</option>
              </select>
            </div>
          </div>

          <div class="input-group">
            <label for="telefone">celular / whatsapp</label>
            <input type="tel" id="telefone" name="telefone" placeholder="(45) 99999-9999" value="<?php echo htmlspecialchars($telefone ?? ''); ?>">
          </div>

          <div class="linha-dupla">
            <div class="input-group">
              <label for="cep">CEP (para entregas)</label>
              <input type="text" id="cep" name="cep" placeholder="00000-000" value="<?php echo htmlspecialchars($cep ?? ''); ?>">
            </div>
            <div class="input-group">
              <label for="numero">número</label>
              <input type="text" id="numero" name="numero" placeholder="ex: 42" value="<?php echo htmlspecialchars($numero ?? ''); ?>">
            </div>
          </div>

          <div class="termos">
            <input type="checkbox" id="termos" name="termos" <?php echo isset($termos) && $termos ? 'checked' : ''; ?> required>
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
    // Função para alternar visibilidade da senha
    function togglePasswordVisibility(inputId, buttonElement) {
      const input = document.getElementById(inputId);
      const iconSpan = buttonElement.querySelector('span');
      
      if (input.type === 'password') {
        input.type = 'text';
        iconSpan.textContent = 'visibility_off';
      } else {
        input.type = 'password';
        iconSpan.textContent = 'visibility';
      }
    }

    // Adicionar eventos para todos os botões de toggle
    document.addEventListener('DOMContentLoaded', function() {
      const toggleButtons = document.querySelectorAll('.toggle-password');
      
      toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
          const targetId = this.getAttribute('data-target');
          if (targetId) {
            togglePasswordVisibility(targetId, this);
          }
        });
      });
    });
  </script>
</body>
</html>