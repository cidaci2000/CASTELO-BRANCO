<?php
session_start();
require_once 'config.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    
    // Validações
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter no mínimo 6 caracteres.';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem.';
    } else {
        // Verificar se e-mail já existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {
            $stmt->close();
            
            // Hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            // Inserir usuário
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, telefone, cpf, data_nascimento) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nome, $email, $senha_hash, $telefone, $cpf, $data_nascimento);
            
            if ($stmt->execute()) {
                $sucesso = 'Cadastro realizado com sucesso! Faça login para continuar.';
                // Limpar campos
                $_POST = [];
            } else {
                $erro = 'Erro ao cadastrar: ' . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Clínica Saúde Total</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ebf4ff, #e2e8f0);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .container {
            background: white;
            border-radius: 24px;
            padding: 3rem 2.5rem;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo i {
            font-size: 3rem;
            color: #2b6cb0;
            background: #ebf4ff;
            padding: 1rem;
            border-radius: 50%;
        }

        .logo h1 {
            font-size: 1.75rem;
            color: #1a202c;
            margin-top: 1rem;
        }

        .logo p {
            color: #718096;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .form-group label .required {
            color: #e53e3e;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #edf2f7;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2b6cb0;
            box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-cadastrar {
            width: 100%;
            background: linear-gradient(135deg, #2b6cb0, #2c5282);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-cadastrar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(43, 108, 176, 0.3);
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fff5f5;
            color: #9b2c2c;
            border: 1px solid #feb2b2;
        }

        .alert-success {
            background: #f0fff4;
            color: #276749;
            border: 1px solid #c6f6d5;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #4a5568;
        }

        .login-link a {
            color: #2b6cb0;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                padding: 2rem 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <i class="fas fa-user-plus"></i>
            <h1>Criar Conta</h1>
            <p>Cadastre-se para agendar consultas</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nome completo <span class="required">*</span></label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>E-mail <span class="required">*</span></label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Senha <span class="required">*</span></label>
                    <input type="password" name="senha" required>
                </div>
                <div class="form-group">
                    <label>Confirmar senha <span class="required">*</span></label>
                    <input type="password" name="confirmar_senha" required>
                </div>
            </div>

            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>" placeholder="(00) 00000-0000">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>CPF</label>
                    <input type="text" name="cpf" id="cpf" value="<?php echo htmlspecialchars($_POST['cpf'] ?? ''); ?>" placeholder="000.000.000-00">
                </div>
                <div class="form-group">
                    <label>Data de Nascimento</label>
                    <input type="date" name="data_nascimento" value="<?php echo htmlspecialchars($_POST['data_nascimento'] ?? ''); ?>">
                </div>
            </div>

            <button type="submit" class="btn-cadastrar">
                <i class="fas fa-user-plus"></i> Cadastrar
            </button>
        </form>

        <div class="login-link">
            Já tem uma conta? <a href="login.php">Faça login</a>
        </div>
    </div>

    <script>
        // Máscara para telefone
        document.getElementById('telefone')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 6) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d{0,5}).*/, '($1) $2');
            } else if (value.length > 0) {
                value = value.replace(/^(\d*)/, '($1');
            }
            
            e.target.value = value;
        });

        // Máscara para CPF
        document.getElementById('cpf')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 9) {
                value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{3})(\d{3})(\d{0,3}).*/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/^(\d{3})(\d{0,3}).*/, '$1.$2');
            }
            
            e.target.value = value;
        });
    </script>
</body>
</html>