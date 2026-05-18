<?php
session_start();
require_once '../database.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    // Pegar dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $data_nascimento = $_POST['data_nascimento'];
    $plano = $_POST['plano'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações
    if ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres!";
    } else {
        try {
            // Verificar se email já existe
            $check = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
            $check->execute([$email]);
            
            if ($check->rowCount() > 0) {
                $erro = "Este email já está cadastrado!";
            } else {
                // Gerar hash da senha
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                // Iniciar transação
                $db->beginTransaction();
                
                // Inserir na tabela usuarios
                $sql1 = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'aluno')";
                $stmt1 = $db->prepare($sql1);
                $stmt1->execute([$nome, $email, $senha_hash]);
                
                // Pegar o ID do usuário inserido
                $usuario_id = $db->lastInsertId();
                
                // Inserir na tabela alunos
                $sql2 = "INSERT INTO alunos (usuario_id, nome, email, telefone, data_nascimento, plano) 
                         VALUES (?, ?, ?, ?, ?, ?)";
                $stmt2 = $db->prepare($sql2);
                $stmt2->execute([$usuario_id, $nome, $email, $telefone, $data_nascimento, $plano]);
                
                // Commit da transação
                $db->commit();
                
                $mensagem = "✅ Cadastro realizado com sucesso! Faça login para acessar sua conta.";
                
                // Limpar formulário
                $_POST = [];
            }
        } catch(PDOException $e) {
            $db->rollBack();
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportLife - Cadastro Academia</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #4f46e5;
            --bg-black: #0a0a0f;
            --card-gray: rgba(255, 255, 255, 0.03);
            --border: rgba(79, 70, 229, 0.15);
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-black);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
            padding: 20px;
        }

        .glow {
            position: fixed;
            width: 400px;
            height: 400px;
            background: #3730a3;
            filter: blur(150px);
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
        }
        
        .glow-2 {
            position: fixed;
            width: 300px;
            height: 300px;
            background: #4f46e5;
            filter: blur(120px);
            border-radius: 50%;
            opacity: 0.08;
            z-index: -1;
            bottom: 0;
            right: 0;
        }

        .card {
            background: var(--card-gray);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            padding: 40px 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            margin-bottom: 5px;
            background: linear-gradient(to bottom, #fff, var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .input-group {
            margin: 15px 0;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        input, select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
            color: white;
            font-size: 0.9rem;
            outline: none;
            transition: 0.3s cubic-bezier(0.4,0,0.2,1);
        }

        select {
            cursor: pointer;
        }
        
        select option {
            background: var(--bg-black);
        }

        input:focus, select:focus {
            border-color: var(--primary-purple);
            background: rgba(79,70,229,0.08);
            box-shadow: 0 0 15px rgba(79,70,229,0.2);
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            background: var(--primary-purple);
            border: none;
            border-radius: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.4s;
            box-shadow: 0 4px 15px rgba(79,70,229,0.3);
            color: white;
            font-size: 0.9rem;
        }

        button:hover {
            background: white;
            color: black;
            transform: translateY(-3px);
        }

        .mensagem-sucesso {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }
        
        .mensagem-erro {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }

        .note {
            margin-top: 20px;
            font-size: 0.75rem;
            color: var(--text-muted);
            text-align: center;
        }
        
        .note a {
            color: var(--primary-purple);
            text-decoration: none;
        }
        
        .note a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px){
            .card {
                padding: 30px 20px;
                margin: 20px;
            }
            h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="glow"></div>
    <div class="glow-2"></div>
    
    <div class="card">
        <h1>SPORTLIFE</h1>
        <div class="subtitle">ACADEMIA</div>
        
        <?php if ($mensagem): ?>
            <div class="mensagem-sucesso"><?php echo $mensagem; ?></div>
        <?php endif; ?>
        
        <?php if ($erro): ?>
            <div class="mensagem-erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" id="formCadastro">
            <div class="input-group">
                <label>📝 NOME COMPLETO</label>
                <input type="text" name="nome" required 
                       value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>"
                       placeholder="Digite seu nome completo">
            </div>
            
            <div class="input-group">
                <label>📧 E-MAIL</label>
                <input type="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       placeholder="seu@email.com">
            </div>
            
            <div class="input-group">
                <label>📱 TELEFONE</label>
                <input type="tel" name="telefone" id="telefone"
                       value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>"
                       placeholder="(11) 99999-9999">
            </div>
            
            <div class="input-group">
                <label>🎂 DATA DE NASCIMENTO</label>
                <input type="date" name="data_nascimento" 
                       value="<?php echo isset($_POST['data_nascimento']) ? $_POST['data_nascimento'] : ''; ?>">
            </div>
            
            <div class="input-group">
                <label>💪 PLANO</label>
                <select name="plano">
                    <option value="Mensal" <?php echo (isset($_POST['plano']) && $_POST['plano'] == 'Mensal') ? 'selected' : ''; ?>>Mensal - R$ 89,90</option>
                    <option value="Trimestral" <?php echo (isset($_POST['plano']) && $_POST['plano'] == 'Trimestral') ? 'selected' : ''; ?>>Trimestral - R$ 239,70</option>
                    <option value="Semestral" <?php echo (isset($_POST['plano']) && $_POST['plano'] == 'Semestral') ? 'selected' : ''; ?>>Semestral - R$ 449,40</option>
                    <option value="Anual" <?php echo (isset($_POST['plano']) && $_POST['plano'] == 'Anual') ? 'selected' : ''; ?>>Anual - R$ 799,00</option>
                </select>
            </div>
            
            <div class="input-group">
                <label>🔒 SENHA</label>
                <input type="password" name="senha" id="senha" required 
                       placeholder="Mínimo 6 caracteres">
            </div>
            
            <div class="input-group">
                <label>🔒 CONFIRMAR SENHA</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha" required 
                       placeholder="Digite a senha novamente">
            </div>
            
            <button type="submit">CADASTRAR</button>
        </form>
        
        <div class="note">
            Já tem cadastro? <a href="login.php">Faça login</a><br>
            Ao se cadastrar, você concorda com nossos <a href="#">Termos de Uso</a>
        </div>
    </div>
    
    <script>
        // Máscara para telefone
        const telefoneInput = document.getElementById('telefone');
        if (telefoneInput) {
            telefoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 11) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length >= 6) {
                    value = value.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                } else if (value.length >= 2) {
                    value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
                }
                e.target.value = value;
            });
        }
        
        // Validação do formulário
        document.getElementById('formCadastro').addEventListener('submit', function(e) {
            const senha = document.getElementById('senha').value;
            const confirmar = document.getElementById('confirmar_senha').value;
            const email = document.querySelector('input[name="email"]').value;
            const nome = document.querySelector('input[name="nome"]').value;
            
            if (nome.trim().length < 3) {
                e.preventDefault();
                alert('Por favor, digite seu nome completo (mínimo 3 caracteres)');
                return false;
            }
            
            if (!email.includes('@') || !email.includes('.')) {
                e.preventDefault();
                alert('Por favor, digite um email válido');
                return false;
            }
            
            if (senha !== confirmar) {
                e.preventDefault();
                alert('As senhas não coincidem!');
                return false;
            }
            
            if (senha.length < 6) {
                e.preventDefault();
                alert('A senha deve ter no mínimo 6 caracteres!');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>