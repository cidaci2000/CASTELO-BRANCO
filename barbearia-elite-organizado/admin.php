<?php
session_start();
require_once 'config.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Verifica a conexão
if (!isset($conn) || $conn->connect_error) {
    die("Erro de conexão com o banco de dados.");
}

// Variáveis para mensagens
$mensagem = '';
$tipo_mensagem = '';

// Processar ações
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST['acao'] ?? '';
    
    // CADASTRAR SERVIÇO
    if ($acao === 'cadastrar_servico') {
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $preco = floatval($_POST['preco'] ?? 0);
        $duracao = intval($_POST['duracao'] ?? 30);
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        if (empty($nome) || empty($descricao) || $preco <= 0) {
            $mensagem = "Preencha todos os campos corretamente!";
            $tipo_mensagem = "error";
        } else {
            $sql = "INSERT INTO servicos (nome, descricao, preco, duracao, ativo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdii", $nome, $descricao, $preco, $duracao, $ativo);
            
            if ($stmt->execute()) {
                $mensagem = "Serviço cadastrado com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao cadastrar serviço: " . $conn->error;
                $tipo_mensagem = "error";
            }
            $stmt->close();
        }
    }
    
    // EDITAR SERVIÇO
    elseif ($acao === 'editar_servico') {
        $id = intval($_POST['servico_id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $preco = floatval($_POST['preco'] ?? 0);
        $duracao = intval($_POST['duracao'] ?? 30);
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        if ($id <= 0 || empty($nome) || empty($descricao) || $preco <= 0) {
            $mensagem = "Preencha todos os campos corretamente!";
            $tipo_mensagem = "error";
        } else {
            $sql = "UPDATE servicos SET nome = ?, descricao = ?, preco = ?, duracao = ?, ativo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdiii", $nome, $descricao, $preco, $duracao, $ativo, $id);
            
            if ($stmt->execute()) {
                $mensagem = "Serviço atualizado com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao atualizar serviço: " . $conn->error;
                $tipo_mensagem = "error";
            }
            $stmt->close();
        }
    }
    
    // EXCLUIR SERVIÇO
    elseif ($acao === 'excluir_servico') {
        $id = intval($_POST['servico_id'] ?? 0);
        
        if ($id <= 0) {
            $mensagem = "ID inválido!";
            $tipo_mensagem = "error";
        } else {
            $sql = "DELETE FROM servicos WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $mensagem = "Serviço excluído com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao excluir serviço: " . $conn->error;
                $tipo_mensagem = "error";
            }
            $stmt->close();
        }
    }
}

// BUSCAR DADOS
// Usuários
$sql_usuarios = "SELECT id, nome, email, tipo, created_at FROM usuarios ORDER BY id DESC";
$result_usuarios = $conn->query($sql_usuarios);

// Serviços
$sql_servicos = "SELECT * FROM servicos ORDER BY id DESC";
$result_servicos = $conn->query($sql_servicos);

// AGENDAMENTOS - CORRIGIDO com a estrutura correta da tabela
$sql_agendamentos = "
    SELECT 
        id,
        usuario_id,
        cliente_nome,
        cliente_email,
        cliente_telefone,
        servico,
        data,
        horario,
        status,
        created_at
    FROM agendamentos 
    ORDER BY data DESC, horario DESC
";
$result_agendamentos = $conn->query($sql_agendamentos);

// Verificar se as tabelas existem, se não, criar
$conn->query("
    CREATE TABLE IF NOT EXISTS servicos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        duracao INT DEFAULT 30,
        ativo TINYINT(1) DEFAULT 1,
        data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// Verifica se a tabela agendamentos existe com a estrutura correta
$check_table = $conn->query("SHOW TABLES LIKE 'agendamentos'");
if ($check_table->num_rows == 0) {
    $conn->query("
        CREATE TABLE agendamentos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT NOT NULL,
            cliente_nome VARCHAR(100) NOT NULL,
            cliente_email VARCHAR(100) NOT NULL,
            cliente_telefone VARCHAR(20) NOT NULL,
            servico VARCHAR(50) NOT NULL,
            data DATE NOT NULL,
            horario TIME NOT NULL,
            status ENUM('agendado','cancelado','concluido') DEFAULT 'agendado',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
        )
    ");
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
            --danger-bg: #ffebee;
            --card-bg: #ffffff;
            --input-border: #dfe6e9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
        }

        /* Header */
        .admin-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: #fff;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .admin-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .admin-header h1 {
            font-size: 24px;
            color: var(--primary);
        }

        .admin-header .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-header .user-info span {
            color: #ecf0f1;
        }

        .btn-logout {
            background: var(--danger);
            color: #fff;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: opacity 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-logout:hover {
            opacity: 0.8;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 30px;
            border-top: 4px solid var(--primary);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-header h2 {
            color: #2c3e50;
            font-size: 20px;
        }

        .card-header .badge {
            background: var(--primary);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Mensagens */
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .alert-error {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid #ffcccb;
        }

        /* Formulários */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 60px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        /* Botões */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            background: #e03131;
        }

        .btn-success {
            background: #2ecc71;
            color: #fff;
        }

        .btn-success:hover {
            background: #27ae60;
        }

        .btn-warning {
            background: #f39c12;
            color: #fff;
        }

        .btn-warning:hover {
            background: #e67e22;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
        }

        /* Tabelas */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table th {
            background: #f8f9fa;
            color: #2c3e50;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid var(--primary);
            font-weight: 600;
        }

        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ecf0f1;
        }

        table tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        .status-active {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-inactive {
            background: #ffebee;
            color: #c62828;
        }

        .status-agendado {
            background: #e3f2fd;
            color: #0d47a1;
        }

        .status-concluido {
            background: #e8f5e9;
            color: #1b5e20;
        }

        .status-cancelado {
            background: #ffebee;
            color: #b71c1c;
        }

        .status-cliente {
            background: #e3f2fd;
            color: #0d47a1;
        }

        .status-admin {
            background: #fce4ec;
            color: #880e4f;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 25px;
            border: none;
            background: #ecf0f1;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-weight: bold;
            color: #7f8c8d;
            transition: all 0.3s;
        }

        .tab-btn:hover {
            background: #d5dbdb;
        }

        .tab-btn.active {
            background: var(--primary);
            color: #fff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            border-top: 4px solid var(--primary);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h3 {
            color: #2c3e50;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #7f8c8d;
        }

        .modal-close:hover {
            color: #2c3e50;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .admin-header .container {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .card-header {
                flex-direction: column;
                text-align: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                justify-content: center;
            }

            .tabs {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<header class="admin-header">
    <div class="container">
        <h1>💈 Barbearia Elite - Admin</h1>
        <div class="user-info">
            <span>👋 Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
            <a href="logout.php" class="btn-logout">Sair</a>
        </div>
    </div>
</header>

<div class="container">

    <!-- Mensagens -->
    <?php if (!empty($mensagem)): ?>
        <div class="alert alert-<?php echo $tipo_mensagem; ?>">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" data-tab="tab-servicos">📋 Serviços</button>
        <button class="tab-btn" data-tab="tab-usuarios">👥 Usuários</button>
        <button class="tab-btn" data-tab="tab-agendamentos">📅 Agendamentos</button>
    </div>

    <!-- TAB 1: SERVIÇOS -->
    <div id="tab-servicos" class="tab-content active">
        <div class="card">
            <div class="card-header">
                <h2>📋 Gerenciar Serviços</h2>
                <button class="btn btn-primary" onclick="abrirModal('modalServico')">+ Novo Serviço</button>
            </div>

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
                        <?php if ($result_servicos && $result_servicos->num_rows > 0): ?>
                            <?php while ($servico = $result_servicos->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $servico['id']; ?></td>
                                    <td><?php echo htmlspecialchars($servico['nome']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($servico['descricao'], 0, 50)) . (strlen($servico['descricao']) > 50 ? '...' : ''); ?></td>
                                    <td>R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></td>
                                    <td><?php echo $servico['duracao']; ?> min</td>
                                    <td>
                                        <span class="status-badge <?php echo $servico['ativo'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $servico['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editarServico(<?php echo htmlspecialchars(json_encode($servico)); ?>)">✏️</button>
                                        <button class="btn btn-danger btn-sm" onclick="excluirServico(<?php echo $servico['id']; ?>, '<?php echo htmlspecialchars($servico['nome']); ?>')">🗑️</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px; color: #7f8c8d;">
                                    Nenhum serviço cadastrado ainda.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: USUÁRIOS -->
    <div id="tab-usuarios" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h2>👥 Usuários Cadastrados</h2>
                <span class="badge">Total: <?php echo $result_usuarios ? $result_usuarios->num_rows : 0; ?></span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Data Cadastro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_usuarios && $result_usuarios->num_rows > 0): ?>
                            <?php while ($usuario = $result_usuarios->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $usuario['id']; ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $usuario['tipo'] == 'admin' ? 'status-admin' : 'status-cliente'; ?>">
                                            <?php echo $usuario['tipo'] == 'admin' ? '👑 Admin' : '👤 Cliente'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($usuario['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: #7f8c8d;">
                                    Nenhum usuário cadastrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: AGENDAMENTOS - CORRIGIDO -->
    <div id="tab-agendamentos" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h2>📅 Agendamentos</h2>
                <span class="badge">Total: <?php echo $result_agendamentos ? $result_agendamentos->num_rows : 0; ?></span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                            <th>Data Agendamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_agendamentos && $result_agendamentos->num_rows > 0): ?>
                            <?php while ($agendamento = $result_agendamentos->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $agendamento['id']; ?></td>
                                    <td><?php echo htmlspecialchars($agendamento['cliente_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($agendamento['cliente_email']); ?></td>
                                    <td><?php echo htmlspecialchars($agendamento['cliente_telefone']); ?></td>
                                    <td><?php echo htmlspecialchars($agendamento['servico']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($agendamento['data'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($agendamento['horario'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $agendamento['status']; ?>">
                                            <?php 
                                                $status_labels = [
                                                    'agendado' => '📌 Agendado',
                                                    'concluido' => '✅ Concluído',
                                                    'cancelado' => '❌ Cancelado'
                                                ];
                                                echo $status_labels[$agendamento['status']] ?? $agendamento['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($agendamento['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 30px; color: #7f8c8d;">
                                    Nenhum agendamento realizado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL: Cadastro/Edição de Serviço -->
<div id="modalServico" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalServicoTitle">Cadastrar Serviço</h3>
            <button class="modal-close" onclick="fecharModal('modalServico')">&times;</button>
        </div>
        <form method="POST" id="formServico">
            <input type="hidden" name="acao" id="servicoAcao" value="cadastrar_servico">
            <input type="hidden" name="servico_id" id="servicoId" value="0">
            
            <div class="form-group">
                <label for="servicoNome">Nome do Serviço *</label>
                <input type="text" id="servicoNome" name="nome" class="form-control" required placeholder="Ex: Corte de Cabelo">
            </div>
            
            <div class="form-group">
                <label for="servicoDescricao">Descrição *</label>
                <textarea id="servicoDescricao" name="descricao" class="form-control" required placeholder="Descreva o serviço..."></textarea>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="servicoPreco">Preço (R$) *</label>
                    <input type="number" id="servicoPreco" name="preco" class="form-control" required step="0.01" min="0" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label for="servicoDuracao">Duração (minutos) *</label>
                    <input type="number" id="servicoDuracao" name="duracao" class="form-control" required min="5" step="5" value="30">
                </div>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="ativo" checked>
                    Serviço Ativo
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-danger" onclick="fecharModal('modalServico')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
// Tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        this.classList.add('active');
        document.getElementById(this.dataset.tab).classList.add('active');
    });
});

// Modal
function abrirModal(id) {
    document.getElementById(id).classList.add('show');
    document.getElementById('servicoAcao').value = 'cadastrar_servico';
    document.getElementById('servicoId').value = 0;
    document.getElementById('modalServicoTitle').textContent = 'Cadastrar Serviço';
    document.getElementById('formServico').reset();
    document.querySelector('input[name="ativo"]').checked = true;
}

function fecharModal(id) {
    document.getElementById(id).classList.remove('show');
}

function editarServico(servico) {
    document.getElementById('modalServico').classList.add('show');
    document.getElementById('servicoAcao').value = 'editar_servico';
    document.getElementById('servicoId').value = servico.id;
    document.getElementById('modalServicoTitle').textContent = 'Editar Serviço';
    document.getElementById('servicoNome').value = servico.nome;
    document.getElementById('servicoDescricao').value = servico.descricao;
    document.getElementById('servicoPreco').value = servico.preco;
    document.getElementById('servicoDuracao').value = servico.duracao;
    document.querySelector('input[name="ativo"]').checked = servico.ativo == 1;
}

function excluirServico(id, nome) {
    if (confirm(`Tem certeza que deseja excluir o serviço "${nome}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="acao" value="excluir_servico">
            <input type="hidden" name="servico_id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Fechar modal clicando fora
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('show');
    }
});
</script>

</body>
</html>