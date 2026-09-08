<?php
// Caminho: barbearia/views/admin/admin.php

// 1. Inicialização e Segurança
session_start();
require_once '../../app/config/config.php';

// Verificar se a conexão existe
if (!isset($conn) || $conn->connect_error) {
    die("Erro de conexão com o banco de dados.");
}

// Verifica se está logado
if (empty($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica se é admin
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../cliente/app.php");
    exit();
}

// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$nome_usuario = $_SESSION['usuario_nome'] ?? 'Administrador';
$mensagem = '';
$tipo_mensagem = '';

// Processar formulários
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao'])) {
    // Validação CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $mensagem = "Sessão expirada. Recarregue a página.";
        $tipo_mensagem = "error";
    } else {
        try {
            // CADASTRAR BARBEIRO
            if ($_POST['acao'] === 'cadastrar_barbeiro') {
                $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
                $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS));
                $especialidade = trim(filter_input(INPUT_POST, 'especialidade', FILTER_SANITIZE_SPECIAL_CHARS));
                
                if (empty($nome) || empty($telefone)) {
                    $mensagem = "Preencha todos os campos obrigatórios!";
                    $tipo_mensagem = "error";
                } else {
                    $sql = "INSERT INTO barbeiros (nome, telefone, especialidade, ativo) VALUES (?, ?, ?, 1)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $nome, $telefone, $especialidade);
                    
                    if ($stmt->execute()) {
                        $mensagem = "Barbeiro cadastrado com sucesso!";
                        $tipo_mensagem = "success";
                    } else {
                        $mensagem = "Erro ao cadastrar barbeiro: " . $stmt->error;
                        $tipo_mensagem = "error";
                    }
                    $stmt->close();
                }
            }
            
            // CADASTRAR SERVIÇO
            if ($_POST['acao'] === 'cadastrar_servico') {
                $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
                $descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS));
                $preco = str_replace(',', '.', trim($_POST['preco'] ?? ''));
                $preco = floatval($preco);
                $duracao = intval($_POST['duracao'] ?? 0);
                
                if (empty($nome) || $preco <= 0 || $duracao <= 0) {
                    $mensagem = "Preencha todos os campos corretamente!";
                    $tipo_mensagem = "error";
                } else {
                    $sql = "INSERT INTO servicos (nome, descricao, preco, duracao, ativo) VALUES (?, ?, ?, ?, 1)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssdi", $nome, $descricao, $preco, $duracao);
                    
                    if ($stmt->execute()) {
                        $mensagem = "Serviço cadastrado com sucesso!";
                        $tipo_mensagem = "success";
                    } else {
                        $mensagem = "Erro ao cadastrar serviço: " . $stmt->error;
                        $tipo_mensagem = "error";
                    }
                    $stmt->close();
                }
            }
            
            // ATUALIZAR BARBEIRO
            if ($_POST['acao'] === 'atualizar_barbeiro') {
                $id = intval($_POST['id'] ?? 0);
                $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
                $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS));
                $especialidade = trim(filter_input(INPUT_POST, 'especialidade', FILTER_SANITIZE_SPECIAL_CHARS));
                $ativo = isset($_POST['ativo']) ? 1 : 0;
                
                if ($id <= 0 || empty($nome)) {
                    $mensagem = "Dados inválidos!";
                    $tipo_mensagem = "error";
                } else {
                    $sql = "UPDATE barbeiros SET nome = ?, telefone = ?, especialidade = ?, ativo = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssii", $nome, $telefone, $especialidade, $ativo, $id);
                    
                    if ($stmt->execute()) {
                        $mensagem = "Barbeiro atualizado com sucesso!";
                        $tipo_mensagem = "success";
                    } else {
                        $mensagem = "Erro ao atualizar barbeiro: " . $stmt->error;
                        $tipo_mensagem = "error";
                    }
                    $stmt->close();
                }
            }
            
            // ATUALIZAR SERVIÇO
            if ($_POST['acao'] === 'atualizar_servico') {
                $id = intval($_POST['id'] ?? 0);
                $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
                $descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS));
                $preco = str_replace(',', '.', trim($_POST['preco'] ?? ''));
                $preco = floatval($preco);
                $duracao = intval($_POST['duracao'] ?? 0);
                $ativo = isset($_POST['ativo']) ? 1 : 0;
                
                if ($id <= 0 || empty($nome) || $preco <= 0 || $duracao <= 0) {
                    $mensagem = "Dados inválidos!";
                    $tipo_mensagem = "error";
                } else {
                    $sql = "UPDATE servicos SET nome = ?, descricao = ?, preco = ?, duracao = ?, ativo = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssdiii", $nome, $descricao, $preco, $duracao, $ativo, $id);
                    
                    if ($stmt->execute()) {
                        $mensagem = "Serviço atualizado com sucesso!";
                        $tipo_mensagem = "success";
                    } else {
                        $mensagem = "Erro ao atualizar serviço: " . $stmt->error;
                        $tipo_mensagem = "error";
                    }
                    $stmt->close();
                }
            }
            
            // DELETAR BARBEIRO
            if ($_POST['acao'] === 'deletar_barbeiro') {
                $id = intval($_POST['id'] ?? 0);
                
                if ($id <= 0) {
                    $mensagem = "ID inválido!";
                    $tipo_mensagem = "error";
                } else {
                    $sql = "DELETE FROM barbeiros WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $id);
                    
                    if ($stmt->execute()) {
                        $mensagem = "Barbeiro removido com sucesso!";
                        $tipo_mensagem = "success";
                    } else {
                        $mensagem = "Erro ao remover barbeiro: " . $stmt->error;
                        $tipo_mensagem = "error";
                    }
                    $stmt->close();
                }
            }
            
            // DELETAR SERVIÇO
            if ($_POST['acao'] === 'deletar_servico') {
                $id = intval($_POST['id'] ?? 0);
                
                if ($id <= 0) {
                    $mensagem = "ID inválido!";
                    $tipo_mensagem = "error";
                } else {
                    $sql = "DELETE FROM servicos WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $id);
                    
                    if ($stmt->execute()) {
                        $mensagem = "Serviço removido com sucesso!";
                        $tipo_mensagem = "success";
                    } else {
                        $mensagem = "Erro ao remover serviço: " . $stmt->error;
                        $tipo_mensagem = "error";
                    }
                    $stmt->close();
                }
            }
            
        } catch (Exception $e) {
            $mensagem = "Erro: " . $e->getMessage();
            $tipo_mensagem = "error";
        }
    }
}

// Buscar barbeiros
$barbeiros = [];
try {
    $sql = "SELECT * FROM barbeiros ORDER BY nome ASC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $barbeiros[] = $row;
        }
    }
} catch (Exception $e) {
    // Tratamento silencioso
}

// Buscar serviços
$servicos = [];
try {
    $sql = "SELECT * FROM servicos ORDER BY nome ASC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $servicos[] = $row;
        }
    }
} catch (Exception $e) {
    // Tratamento silencioso
}

// Buscar agendamentos
$agendamentos = [];
try {
    $sql = "SELECT a.*, u.nome as profissional_nome 
            FROM agendamentos a 
            LEFT JOIN usuarios u ON a.usuario_id = u.id 
            WHERE a.status = 'agendado' 
            ORDER BY a.data ASC, a.horario ASC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $agendamentos[] = $row;
        }
    }
} catch (Exception $e) {
    // Tratamento silencioso
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Barbearia Elite</title>
    <style>
        :root {
            --primary: #d4af37;
            --primary-hover: #b5952f;
            --bg-color: #f4f7f6;
            --text-color: #333;
            --danger: #ff4757;
            --success: #2ed573;
            --card-bg: #ffffff;
            --input-border: #dfe6e9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
        }
        header h1 { margin: 0; font-size: 24px; color: var(--primary); }
        header p { margin: 5px 0 0; font-size: 14px; opacity: 0.8; }
        
        .btn-logout {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            text-decoration: none;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .btn-logout:hover { background: rgba(255,255,255,0.1); border-color: white; }

        .btn-voltar {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            text-decoration: none;
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .btn-voltar:hover { background: rgba(255,255,255,0.1); border-color: white; }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary);
        }
        .card h2 { 
            margin-top: 0; 
            color: #2c3e50; 
            font-size: 20px; 
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }
        .form-control:focus { border-color: var(--primary); }
        .form-control:disabled { background: #f5f5f5; }

        .grid-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-submit:hover { background-color: var(--primary-hover); }
        .btn-submit:disabled { background-color: #bdc3c7; cursor: not-allowed; }

        .btn-danger {
            background-color: var(--danger);
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: 0.3s;
        }
        .btn-danger:hover { opacity: 0.8; }

        .btn-success {
            background-color: var(--success);
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: 0.3s;
        }
        .btn-success:hover { opacity: 0.8; }

        /* Tabela */
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid var(--primary);
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #f1f2f6;
        }
        tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-agendado { background: #e3f2fd; color: #1976d2; }
        .status-cancelado { background: #ffebee; color: #d32f2f; }
        .status-concluido { background: #e8f5e9; color: #388e3c; }
        .badge-ativo { background: #e8f5e9; color: #388e3c; }
        .badge-inativo { background: #ffebee; color: #d32f2f; }

        .empty-state {
            text-align: center;
            color: #95a5a6;
            padding: 30px 0;
            font-style: italic;
        }

        .mensagem {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .mensagem.success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .mensagem.error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

        .acao-botoes {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        @media (max-width: 992px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            .grid-form {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .btn-logout, .btn-voltar { 
                position: static; 
                display: inline-block; 
                margin-top: 10px; 
            }
            header { padding: 15px; }
            .btn-logout { margin-left: 10px; }
            .container { padding: 0 10px; }
        }
    </style>
</head>
<body>

<header>
    <a href="../cliente/app.php" class="btn-voltar">← Voltar</a>
    <h1>💈 Barbearia Elite - Admin</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($nome_usuario); ?>!</p>
    <a href="../auth/logout.php" class="btn-logout">Sair do sistema</a>
</header>

<main class="container">
    <?php if (!empty($mensagem)): ?>
        <div class="mensagem <?php echo $tipo_mensagem; ?>">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <div class="grid-2">
        <!-- Formulário Cadastrar Barbeiro -->
        <div class="card">
            <h2>👤 Cadastrar Barbeiro</h2>
            <form method="POST" action="">
                <input type="hidden" name="acao" value="cadastrar_barbeiro">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <input type="text" name="nome" class="form-control" placeholder="Nome do barbeiro" required>
                <input type="text" name="telefone" class="form-control" placeholder="Telefone" required>
                <input type="text" name="especialidade" class="form-control" placeholder="Especialidade (ex: Cortes, Barba, etc)">
                
                <button type="submit" class="btn-submit">Cadastrar Barbeiro</button>
            </form>
        </div>

        <!-- Formulário Cadastrar Serviço -->
        <div class="card">
            <h2>✂️ Cadastrar Serviço</h2>
            <form method="POST" action="">
                <input type="hidden" name="acao" value="cadastrar_servico">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <input type="text" name="nome" class="form-control" placeholder="Nome do serviço" required>
                <input type="text" name="descricao" class="form-control" placeholder="Descrição do serviço">
                
                <div class="grid-form">
                    <input type="text" name="preco" class="form-control" placeholder="Preço (ex: 40.00)" required>
                    <input type="number" name="duracao" class="form-control" placeholder="Duração (minutos)" required>
                </div>
                
                <button type="submit" class="btn-submit">Cadastrar Serviço</button>
            </form>
        </div>
    </div>

    <!-- Lista de Barbeiros -->
    <div class="card" style="margin-bottom: 30px;">
        <h2>📋 Barbeiros Cadastrados</h2>
        
        <?php if (empty($barbeiros)): ?>
            <div class="empty-state">Nenhum barbeiro cadastrado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Especialidade</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($barbeiros as $barbeiro): ?>
                            <tr>
                                <td>#<?php echo $barbeiro['id']; ?></td>
                                <td><?php echo htmlspecialchars($barbeiro['nome']); ?></td>
                                <td><?php echo htmlspecialchars($barbeiro['telefone']); ?></td>
                                <td><?php echo htmlspecialchars($barbeiro['especialidade'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $barbeiro['ativo'] ? 'badge-ativo' : 'badge-inativo'; ?>">
                                        <?php echo $barbeiro['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="acao-botoes">
                                        <button onclick="editarBarbeiro(<?php echo htmlspecialchars(json_encode($barbeiro)); ?>)" class="btn-success">Editar</button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este barbeiro?')">
                                            <input type="hidden" name="acao" value="deletar_barbeiro">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="id" value="<?php echo $barbeiro['id']; ?>">
                                            <button type="submit" class="btn-danger">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Lista de Serviços -->
    <div class="card" style="margin-bottom: 30px;">
        <h2>📋 Serviços Cadastrados</h2>
        
        <?php if (empty($servicos)): ?>
            <div class="empty-state">Nenhum serviço cadastrado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Duração</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicos as $servico): ?>
                            <tr>
                                <td>#<?php echo $servico['id']; ?></td>
                                <td><?php echo htmlspecialchars($servico['nome']); ?></td>
                                <td><?php echo htmlspecialchars($servico['descricao'] ?? 'N/A'); ?></td>
                                <td>R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></td>
                                <td><?php echo $servico['duracao']; ?> min</td>
                                <td>
                                    <span class="status-badge <?php echo $servico['ativo'] ? 'badge-ativo' : 'badge-inativo'; ?>">
                                        <?php echo $servico['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="acao-botoes">
                                        <button onclick="editarServico(<?php echo htmlspecialchars(json_encode($servico)); ?>)" class="btn-success">Editar</button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este serviço?')">
                                            <input type="hidden" name="acao" value="deletar_servico">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="id" value="<?php echo $servico['id']; ?>">
                                            <button type="submit" class="btn-danger">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Lista de Agendamentos -->
    <div class="card">
        <h2>📅 Todos os Agendamentos</h2>
        
        <?php if (empty($agendamentos)): ?>
            <div class="empty-state">Nenhum agendamento encontrado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Profissional</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $agenda): ?>
                            <tr>
                                <td>#<?php echo $agenda['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($agenda['cliente_nome']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($agenda['cliente_telefone']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($agenda['profissional_nome'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($agenda['servico']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($agenda['data'])); ?></td>
                                <td><?php echo htmlspecialchars($agenda['horario']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $agenda['status']; ?>">
                                        <?php echo ucfirst($agenda['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal de Edição - Barbeiro -->
<div id="modalBarbeiro" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h2 style="margin-top: 0;">Editar Barbeiro</h2>
        <form method="POST" action="">
            <input type="hidden" name="acao" value="atualizar_barbeiro">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" id="edit_barbeiro_id">
            
            <input type="text" name="nome" id="edit_barbeiro_nome" class="form-control" placeholder="Nome" required>
            <input type="text" name="telefone" id="edit_barbeiro_telefone" class="form-control" placeholder="Telefone" required>
            <input type="text" name="especialidade" id="edit_barbeiro_especialidade" class="form-control" placeholder="Especialidade">
            
            <div style="margin-bottom: 15px;">
                <label>
                    <input type="checkbox" name="ativo" id="edit_barbeiro_ativo" value="1">
                    Ativo
                </label>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-submit" style="flex: 1;">Salvar</button>
                <button type="button" onclick="fecharModal('modalBarbeiro')" class="btn-submit" style="flex: 1; background: #95a5a6;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Edição - Serviço -->
<div id="modalServico" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h2 style="margin-top: 0;">Editar Serviço</h2>
        <form method="POST" action="">
            <input type="hidden" name="acao" value="atualizar_servico">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" id="edit_servico_id">
            
            <input type="text" name="nome" id="edit_servico_nome" class="form-control" placeholder="Nome" required>
            <input type="text" name="descricao" id="edit_servico_descricao" class="form-control" placeholder="Descrição">
            
            <div class="grid-form">
                <input type="text" name="preco" id="edit_servico_preco" class="form-control" placeholder="Preço" required>
                <input type="number" name="duracao" id="edit_servico_duracao" class="form-control" placeholder="Duração (min)" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>
                    <input type="checkbox" name="ativo" id="edit_servico_ativo" value="1">
                    Ativo
                </label>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-submit" style="flex: 1;">Salvar</button>
                <button type="button" onclick="fecharModal('modalServico')" class="btn-submit" style="flex: 1; background: #95a5a6;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div id="toast"></div>

<script>
const csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";

// Funções para editar
function editarBarbeiro(barbeiro) {
    document.getElementById('edit_barbeiro_id').value = barbeiro.id;
    document.getElementById('edit_barbeiro_nome').value = barbeiro.nome;
    document.getElementById('edit_barbeiro_telefone').value = barbeiro.telefone;
    document.getElementById('edit_barbeiro_especialidade').value = barbeiro.especialidade || '';
    document.getElementById('edit_barbeiro_ativo').checked = barbeiro.ativo == 1;
    document.getElementById('modalBarbeiro').style.display = 'flex';
}

function editarServico(servico) {
    document.getElementById('edit_servico_id').value = servico.id;
    document.getElementById('edit_servico_nome').value = servico.nome;
    document.getElementById('edit_servico_descricao').value = servico.descricao || '';
    document.getElementById('edit_servico_preco').value = servico.preco;
    document.getElementById('edit_servico_duracao').value = servico.duracao;
    document.getElementById('edit_servico_ativo').checked = servico.ativo == 1;
    document.getElementById('modalServico').style.display = 'flex';
}

function fecharModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Fechar modal ao clicar fora
window.onclick = function(event) {
    if (event.target === document.getElementById('modalBarbeiro')) {
        fecharModal('modalBarbeiro');
    }
    if (event.target === document.getElementById('modalServico')) {
        fecharModal('modalServico');
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'show';
    if (type === 'error') toast.classList.add('error');
    
    setTimeout(() => {
        toast.className = toast.className.replace('show', '');
    }, 3000);
}

async function logout() {
    if (!confirm('Tem certeza que deseja sair?')) return;
    
    const formData = new FormData();
    formData.append('acao', 'logout');
    formData.append('csrf_token', csrfToken);
    
    try {
        const response = await fetch('../cliente/app.php', { method: 'POST', body: formData });
        const data = await response.json();
        window.location.href = '../auth/login.php';
    } catch (error) {
        window.location.href = '../auth/login.php';
    }
}
</script>

<style>
#toast {
    visibility: hidden;
    min-width: 250px;
    background-color: #2ed573;
    color: #fff;
    text-align: center;
    border-radius: 8px;
    padding: 16px;
    position: fixed;
    z-index: 1000;
    left: 50%;
    bottom: 30px;
    transform: translateX(-50%);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    font-weight: bold;
    font-size: 14px;
    opacity: 0;
    transition: opacity 0.3s, bottom 0.3s;
}
#toast.show {
    visibility: visible;
    opacity: 1;
    bottom: 50px;
}
#toast.error {
    background-color: #ff4757;
}
</style>

</body>
</html>