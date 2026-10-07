<?php
// ============================================================
// cadastro.php — Cadastro de novos usuários (PDO + sessão)
// ============================================================
require_once __DIR__ . '/config/auth.php';           // session + helpers
require_once __DIR__ . '/config/database.php';       // $pdo
require_once __DIR__ . '/config/usuario_functions.php';

$usuarioClass = new Usuario($pdo);

$error   = '';
$success = '';
$redirect = false;

// Já logado? Redireciona.
if (!empty($_SESSION['usuario_id'])) {
    redirect(redirecionar_por_tipo($_SESSION['usuario_tipo']));
}

// Helper local
function redirecionar_por_tipo(string $tipo): string {
    return match ($tipo) {
        'admin'     => 'admin/dashboard.php',
        'instrutor' => 'instrutor/dashboard.php',
        default     => 'aluno/dashboard.php',
    };
}

// ============================================================
// POST: cadastro
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'cadastro') {

    // -------- Coleta (nomes IGUAIS aos do HTML) --------
    $nome_completo   = trim($_POST['nome_completo']   ?? '');
    $email           = trim($_POST['email']           ?? '');
    $senha           = $_POST['senha']                ?? '';
    $confirmar_senha = $_POST['confirmar_senha']      ?? '';
    $telefone        = trim($_POST['telefone']        ?? '');
    $data_nascimento = $_POST['data_nascimento']      ?? '';
    $cpf             = trim($_POST['cpf']             ?? '');
    $modalidade      = $_POST['modalidade']           ?? '';

    // -------- Validações --------
    if ($nome_completo === '' || $email === '' || $senha === '' || $modalidade === '') {
        $error = 'Nome, E-mail, Senha e Modalidade são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $error = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmar_senha) {
        $error = 'As senhas não coincidem.';
    } elseif ($telefone !== '' && !preg_match('/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/', $telefone)) {
        $error = 'Telefone inválido. Use o formato (XX) XXXXX-XXXX';
    } elseif ($cpf !== '' && !preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf)) {
        $error = 'CPF inválido. Use o formato XXX.XXX.XXX-XX';
    } elseif ($data_nascimento !== '') {
        $dt = DateTime::createFromFormat('Y-m-d', $data_nascimento);
        if (!$dt || $dt->format('Y-m-d') !== $data_nascimento) {
            $error = 'Data de nascimento inválida.';
        }
    }

    // -------- E-mail duplicado --------
    if ($error === '') {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $error = 'Este e-mail já está cadastrado.';
    }

    // -------- CPF duplicado --------
    if ($error === '' && $cpf !== '') {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE cpf = ? LIMIT 1");
        $stmt->execute([$cpf]);
        if ($stmt->fetch()) $error = 'Este CPF já está cadastrado.';
    }

    // -------- Insere --------
    if ($error === '') {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO usuarios
                    (nome_completo, email, senha, telefone, data_nascimento, cpf, modalidade, tipo_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'usuario')
            ");
            $stmt->execute([
                $nome_completo,
                $email,
                $senha_hash,
                $telefone        !== '' ? $telefone        : null,
                $data_nascimento !== '' ? $data_nascimento : null,
                $cpf             !== '' ? $cpf             : null,
                $modalidade,
            ]);

            $success  = 'Conta criada com sucesso! Faça login para continuar.';
            $redirect = true;
            $_POST    = []; // limpa valores antigos
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = 'E-mail ou CPF já cadastrado.';
            } else {
                $error = 'Erro ao criar conta. Tente novamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | SportLife</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-glow: rgba(79, 70, 229, 0.4);
            --bg-dark: #06060a;
            --card-bg: rgba(10, 10, 15, 0.7);
            --card-border: rgba(255, 255, 255, 0.06);
            --input-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #64748b;
            --success: #22c55e;
            --danger: #ef4444;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; overflow-x: hidden;
            position: relative; padding: 20px 0;
        }
        .bg-glow-1 {
            position: absolute; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(79,70,229,0.15) 0%, rgba(0,0,0,0) 70%);
            top: -10%; left: -10%; z-index: 1;
        }
        .bg-glow-2 {
            position: absolute; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(67,56,202,0.12) 0%, rgba(0,0,0,0) 70%);
            bottom: -20%; right: -10%; z-index: 1;
        }
        .card-container {
            position: relative; z-index: 10; width: 100%;
            max-width: 480px; padding: 20px;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--card-border);
            padding: 40px 35px; border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.1);
            text-align: center;
        }
        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3.5rem; letter-spacing: 1px; line-height: 1;
            margin-bottom: 8px;
            background: linear-gradient(180deg, #ffffff 20%, #818cf8 65%, #4f46e5 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .subtitle { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 30px; }
        .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem; text-align: left; }
        .alert-error   { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .alert-success { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #86efac; }
        .input-group { margin-bottom: 18px; text-align: left; }
        .input-group label {
            display: block; font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            color: #94a3b8; margin-bottom: 8px;
        }
        .input-wrapper { position: relative; }
        input, select {
            width: 100%; padding: 14px 16px; border-radius: 12px;
            border: 1px solid var(--input-border);
            background: rgba(255,255,255,0.02); color: var(--text-main);
            font-size: 0.95rem; font-weight: 500; outline: none;
            transition: all 0.3s ease; appearance: none;
        }
        select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 16px center;
        }
        input:focus, select:focus {
            border-color: var(--primary);
            background: rgba(79,70,229,0.04);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }
        select option { background: #1a1a2e; color: white; }
        .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .btn {
            display: inline-block; padding: 14px 30px; margin-top: 15px;
            background: var(--primary); border: none; border-radius: 12px;
            color: white; font-size: 0.95rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            cursor: pointer; transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            box-shadow: 0 4px 20px rgba(79,70,229,0.3); text-decoration: none;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(79,70,229,0.5); filter: brightness(1.1); }
        button {
            width: 100%; padding: 16px; margin-top: 10px;
            background: var(--primary); border: none; border-radius: 12px;
            color: white; font-size: 0.95rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            cursor: pointer; transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            box-shadow: 0 4px 20px rgba(79,70,229,0.3);
        }
        button:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(79,70,229,0.5); filter: brightness(1.1); }
        button:active { transform: translateY(0); }
        .note { margin-top: 25px; font-size: 0.85rem; color: var(--text-muted); }
        .note a { color: #818cf8; text-decoration: none; font-weight: 600; transition: color 0.2s ease; }
        .note a:hover { color: white; }
        .success-box {
            padding: 20px; background: rgba(34,197,94,0.05);
            border: 1px solid rgba(34,197,94,0.2);
            border-radius: 12px; margin: 20px 0;
        }
        .success-box .icon { font-size: 3rem; margin-bottom: 10px; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 480px) {
            .card { padding: 35px 20px; }
            h1 { font-size: 3rem; }
            .input-row { grid-template-columns: 1fr; gap: 0; }
        }
    </style>
</head>
<body>

    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="card-container">
        <div class="card">
            <h1>Cadastre-se</h1>
            <div class="subtitle">Crie sua conta no SportLife para começar</div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="alert alert-success">
                    <div class="success-box">
                        <div class="icon">✅</div>
                        <p style="font-weight:600;margin-bottom:10px;"><?= htmlspecialchars($success) ?></p>
                        <p style="font-size:0.85rem;color:var(--text-muted);">Redirecionando para o login...</p>
                    </div>
                    <a href="login.php" class="btn">Ir para o Login agora</a>
                </div>
                <meta http-equiv="refresh" content="2;url=login.php">
                <script>
                    setTimeout(function() { window.location.href = "login.php"; }, 2000);
                </script>
            <?php endif; ?>

            <?php if ($success === ''): ?>
            <form method="POST" action="">
                <input type="hidden" name="acao" value="cadastro">

                <div class="input-group">
                    <label for="nome_completo">Nome Completo <span style="color:#ef4444;">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" id="nome_completo" name="nome_completo"
                               placeholder="Digite seu nome completo" required
                               value="<?= htmlspecialchars($_POST['nome_completo'] ?? '') ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">E-mail <span style="color:#ef4444;">*</span></label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email"
                               placeholder="seu@email.com" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="telefone">Telefone</label>
                        <div class="input-wrapper">
                            <input type="tel" id="telefone" name="telefone"
                                   placeholder="(XX) XXXXX-XXXX"
                                   value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <div class="input-wrapper">
                            <input type="date" id="data_nascimento" name="data_nascimento"
                                   value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label for="cpf">CPF</label>
                    <div class="input-wrapper">
                        <input type="text" id="cpf" name="cpf"
                               placeholder="XXX.XXX.XXX-XX"
                               value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label for="modalidade">Modalidade <span style="color:#ef4444;">*</span></label>
                    <div class="input-wrapper">
                        <select id="modalidade" name="modalidade" required>
                            <option value="">Selecione sua modalidade</option>
                            <?php
                            $mods = ['Musculação','Ciclismo','Luta','Natação','Yoga','Crossfit','Pilates','Corrida'];
                            $sel  = $_POST['modalidade'] ?? '';
                            foreach ($mods as $m):
                            ?>
                                <option value="<?= htmlspecialchars($m) ?>" <?= $sel === $m ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="senha">Senha <span style="color:#ef4444;">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="senha" name="senha"
                                   placeholder="••••••••" minlength="6" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="confirmar_senha">Confirmar Senha <span style="color:#ef4444;">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="confirmar_senha" name="confirmar_senha"
                                   placeholder="••••••••" minlength="6" required>
                        </div>
                    </div>
                </div>

                <button type="submit">Criar Conta</button>
            </form>
            <?php endif; ?>

            <div class="note">
                Já tem uma conta? <a href="login.php">Faça seu Login</a>
            </div>
        </div>
    </div>

</body>
</html>