<?php
// login.php - VERSÃO CORRIGIDA COM CSS ORIGINAL
session_start();
require_once 'config.php';

$erro = '';
$sucesso = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $senha = $_POST['senha'] ?? '';
    $lembrar = isset($_POST['lembrar']);
    
    // Validações básicas
    if (empty($email)) {
        $erro = "E-mail é obrigatório";
    } elseif (empty($senha)) {
        $erro = "Senha é obrigatória";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido";
    } else {
        
        global $pdo;
        
        try {
            // Buscar usuário pelo e-mail
            $sql = "SELECT id, nome_completo, email, senha_hash, tipo_usuario, ativo 
                    FROM usuarios 
                    WHERE email = :email";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            
            if ($stmt->rowCount() === 0) {
                $erro = "E-mail ou senha incorretos";
            } else {
                $usuario = $stmt->fetch();
                
                // Verificar senha
                if (!password_verify($senha, $usuario['senha_hash'])) {
                    $erro = "E-mail ou senha incorretos";
                } else {
                    // Login bem-sucedido
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome_completo'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'] ?? 'cliente';
                    
                    // Se "lembrar de mim" estiver marcado
                    if ($lembrar) {
                        setcookie('lembrar_email', $email, time() + (7 * 24 * 60 * 60), '/');
                    } else {
                        setcookie('lembrar_email', '', time() - 3600, '/');
                    }
                    
                    // Redirecionar baseado no tipo de usuário
                    if ($usuario['tipo_usuario'] === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: produtos.php");
                    }
                    exit();
                }
            }
        } catch(PDOException $e) {
            $erro = "Erro ao processar login. Tente novamente mais tarde.";
        }
    }
}

// Preencher email se tiver cookie
$email_cookie = isset($_COOKIE['lembrar_email']) ? $_COOKIE['lembrar_email'] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flwrs · login na sua conta</title>
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
      background-color: #fefaf5;
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

    /* ===== TELA DE LOGIN ===== */
    .login-section {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 70vh;
      padding: 3rem 0 4rem 0;
    }

    .form-login {
      background: #ffffffd9;
      backdrop-filter: blur(2px);
      border-radius: 32px 16px 32px 16px;
      padding: 2.8rem 2.5rem;
      box-shadow: 0 20px 35px -12px rgba(152, 123, 136, 0.2);
      border: 1px solid rgba(247, 213, 231, 0.6);
      width: 100%;
      max-width: 480px;
      margin: 0 auto;
    }

    .form-login h3 {
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
    .error-message {
      background-color: #ffe4e4;
      border-left: 4px solid #e74c3c;
      padding: 0.8rem 1rem;
      margin-bottom: 1.5rem;
      border-radius: 12px;
      color: #c0392b;
      font-size: 0.85rem;
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

    .password-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .password-wrapper input {
      width: 100%;
      padding: 0.8rem 3rem 0.8rem 1.2rem;
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
    }

    .toggle-password:hover {
      color: #c06f8b;
    }

    .input-group input:not(.password-wrapper input) {
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

    .input-group input:focus {
      border-color: #deef6e;
      box-shadow: 0 0 0 3px #deef6e40;
      background: white;
    }

    .input-group input::placeholder {
      color: #b9aaa2;
      font-weight: 300;
      font-size: 0.9rem;
    }

    .lembrar-esqueci {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 1rem 0 2rem;
      font-size: 0.85rem;
    }

    .lembrar {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .lembrar input {
      width: 1rem;
      height: 1rem;
      accent-color: #b2e4b3;
    }

    .btn-login {
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
      margin-bottom: 1.5rem;
    }

    .btn-login:hover {
      background: #deef6e;
      border-color: #deef6e;
      box-shadow: 0 10px 22px -8px #b2e4b3;
      color: #1e2b1e;
    }

    .cadastro-redirect {
      text-align: center;
      font-size: 0.9rem;
      color: #8e8077;
    }

    .cadastro-redirect a {
      color: #b4849a;
      text-decoration: none;
      font-weight: 500;
      border-bottom: 2px solid #f7d5e7;
    }

    .cadastro-redirect a:hover {
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
      .form-login {
        padding: 2rem 1.2rem;
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
    <section class="login-section">
      <div class="form-login">
        <h3>Acessar conta</h3>
        <div class="form-sub">faça login com seus dados</div>

        <?php if (!empty($erro)): ?>
          <div class="error-message">
            <?php echo htmlspecialchars($erro); ?>
          </div>
        <?php endif; ?>

        <form action="" method="POST">
          <div class="input-group">
            <label for="email">e-mail</label>
            <input type="email" id="email" name="email" placeholder="central.flwrs@gmail.com" value="<?php echo htmlspecialchars($email_cookie); ?>" required>
          </div>

          <div class="input-group">
            <label for="senha">senha</label>
            <div class="password-wrapper">
              <input type="password" id="senha" name="senha" placeholder="sua senha" required>
              <button type="button" class="toggle-password" onclick="togglePassword('senha', this)" aria-label="mostrar/esconder senha">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </div>

          <div class="lembrar-esqueci">
            <div class="lembrar">
              <input type="checkbox" id="lembrar" name="lembrar" <?php echo !empty($email_cookie) ? 'checked' : ''; ?>>
              <label for="lembrar">lembrar de mim</label>
            </div>
            <div class="esqueci">
              <a href="recuperar_senha.php">esqueci a senha</a>
            </div>
          </div>

          <button type="submit" class="btn-login">entrar</button>

          <div class="cadastro-redirect">
            Ainda não tem conta? <a href="cadastro.php">criar conta</a>
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
    function togglePassword(inputId, button) {
      const input = document.getElementById(inputId);
      const icon = button.querySelector('.material-symbols-outlined');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
      } else {
        input.type = 'password';
        icon.textContent = 'visibility';
      }
    }
  </script>
</body>
</html>