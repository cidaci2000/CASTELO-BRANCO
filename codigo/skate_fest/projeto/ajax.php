<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isLogado()) {
    echo json_encode(['success' => false, 'errors' => ['Não autenticado']]);
    exit;
}

$action = $_POST['action'] ?? '';
$uid = $_SESSION['usuario_id'];

// ============================================
// ADMIN - USUÁRIOS
// ============================================
if ($action === 'listar_usuarios' && isAdmin()) {
    $stmt = $pdo->query("SELECT u.*, 
        CASE WHEN u.tipo = 'admin' THEN 'Administrador'
             WHEN u.tipo = 'representante' THEN 'Representante'
             WHEN u.tipo = 'competidor' THEN 'Competidor' END as tipo_label
        FROM usuarios u ORDER BY u.data_cadastro DESC");
    echo json_encode(['success' => true, 'usuarios' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

if ($action === 'atualizar_status_usuario' && isAdmin()) {
    $id = intval($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'ativo';
    $pdo->prepare("UPDATE usuarios SET status = ? WHERE id = ? AND tipo != 'admin'")->execute([$status, $id]);
    echo json_encode(['success' => true, 'message' => 'Status atualizado!']);
    exit;
}

if ($action === 'atualizar_tipo_usuario' && isAdmin()) {
    $id = intval($_POST['id'] ?? 0);
    $tipo = $_POST['tipo'] ?? 'competidor';
    $pdo->prepare("UPDATE usuarios SET tipo = ? WHERE id = ? AND tipo != 'admin'")->execute([$tipo, $id]);
    echo json_encode(['success' => true, 'message' => 'Tipo atualizado!']);
    exit;
}

if ($action === 'excluir_usuario' && isAdmin()) {
    $id = intval($_POST['id'] ?? 0);
    $pdo->prepare("DELETE FROM usuarios WHERE id = ? AND tipo != 'admin'")->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

// ============================================
// REPRESENTANTE - SKATISTAS E NOTAS
// ============================================
if ($action === 'listar_skatistas_representante' && (isRepresentante() || isAdmin())) {
    $stmt = $pdo->prepare("SELECT s.id, s.nome, s.pais, s.idade, s.media_geral,
        (SELECT COUNT(*) FROM notas_representante nr WHERE nr.skatista_id = s.id AND nr.representante_id = ?) as ja_avaliou
        FROM skatistas_competicao s ORDER BY s.nome ASC");
    $stmt->execute([$uid]);
    echo json_encode(['success' => true, 'skatistas' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

if ($action === 'buscar_notas_skatista' && (isRepresentante() || isAdmin())) {
    $sid = intval($_POST['skatista_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM notas_representante WHERE skatista_id = ? AND representante_id = ?");
    $stmt->execute([$sid, $uid]);
    $notas = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'notas' => $notas ?: null]);
    exit;
}

if ($action === 'salvar_notas_representante' && (isRepresentante() || isAdmin())) {
    $sid = intval($_POST['skatista_id'] ?? 0);
    $k = floatval(str_replace(',', '.', $_POST['kickflip'] ?? 0));
    $h = floatval(str_replace(',', '.', $_POST['heelflip'] ?? 0));
    $t = floatval(str_replace(',', '.', $_POST['tre_flip'] ?? 0));
    $v = floatval(str_replace(',', '.', $_POST['varial'] ?? 0));
    $l = floatval(str_replace(',', '.', $_POST['laser'] ?? 0));
    $obs = trim($_POST['observacao'] ?? '');
    
    $erros = [];
    if ($sid <= 0) $erros[] = 'Skatista inválido';
    foreach (['kickflip'=>$k,'heelflip'=>$h,'tre_flip'=>$t,'varial'=>$v,'laser'=>$l] as $n => $val) {
        if ($val < 0 || $val > 10) $erros[] = "Nota de $n deve ser entre 0 e 10";
    }
    
    if (!empty($erros)) { echo json_encode(['success'=>false,'errors'=>$erros]); exit; }
    
    try {
        $check = $pdo->prepare("SELECT id FROM notas_representante WHERE skatista_id = ? AND representante_id = ?");
        $check->execute([$sid, $uid]);
        
        if ($check->fetch()) {
            $pdo->prepare("UPDATE notas_representante SET kickflip=?, heelflip=?, tre_flip=?, varial=?, laser=?, observacao=?, data_avaliacao=NOW() WHERE skatista_id=? AND representante_id=?")
                ->execute([$k, $h, $t, $v, $l, $obs, $sid, $uid]);
        } else {
            $pdo->prepare("INSERT INTO notas_representante (skatista_id, representante_id, kickflip, heelflip, tre_flip, varial, laser, observacao) VALUES (?,?,?,?,?,?,?,?)")
                ->execute([$sid, $uid, $k, $h, $t, $v, $l, $obs]);
        }
        
        // Recalcular média
        $pdo->prepare("UPDATE skatistas_competicao SET 
            kickflip = (SELECT ROUND(AVG(kickflip),1) FROM notas_representante WHERE skatista_id = ?),
            heelflip = (SELECT ROUND(AVG(heelflip),1) FROM notas_representante WHERE skatista_id = ?),
            tre_flip = (SELECT ROUND(AVG(tre_flip),1) FROM notas_representante WHERE skatista_id = ?),
            varial   = (SELECT ROUND(AVG(varial),1)   FROM notas_representante WHERE skatista_id = ?),
            laser    = (SELECT ROUND(AVG(laser),1)    FROM notas_representante WHERE skatista_id = ?)
            WHERE id = ?")->execute([$sid,$sid,$sid,$sid,$sid,$sid]);
        
        echo json_encode(['success' => true, 'message' => 'Notas salvas!']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'errors' => [$e->getMessage()]]);
    }
    exit;
}

if ($action === 'estatisticas_representante' && (isRepresentante() || isAdmin())) {
    $total = $pdo->query("SELECT COUNT(*) as t FROM skatistas_competicao")->fetch()['t'];
    $stmt = $pdo->prepare("SELECT COUNT(*) as t FROM notas_representante WHERE representante_id = ?");
    $stmt->execute([$uid]);
    $av = $stmt->fetch()['t'];
    $stmt = $pdo->prepare("SELECT ROUND(AVG(media),1) as m FROM notas_representante WHERE representante_id = ?");
    $stmt->execute([$uid]);
    $med = $stmt->fetch()['m'] ?? 0;
    echo json_encode(['success' => true, 'stats' => ['total'=>$total,'avaliados'=>$av,'pendentes'=>$total-$av,'media'=>$med]]);
    exit;
}

if ($action === 'cadastrar_skater_competicao_rep' && (isRepresentante() || isAdmin())) {
    $nome = trim($_POST['nome'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $idade = intval($_POST['idade'] ?? 0);
    
    $erros = [];
    if (empty($nome)) $erros[] = 'Nome obrigatório';
    if (empty($pais)) $erros[] = 'País obrigatório';
    if ($idade < 10 || $idade > 60) $erros[] = 'Idade entre 10 e 60';
    
    if (!empty($erros)) { echo json_encode(['success'=>false,'errors'=>$erros]); exit; }
    
    $pdo->prepare("INSERT INTO skatistas_competicao (nome, pais, idade, kickflip, heelflip, tre_flip, varial, laser) VALUES (?,?,?,0,0,0,0,0)")
        ->execute([$nome, $pais, $idade]);
    echo json_encode(['success' => true, 'message' => 'Skatista cadastrado!']);
    exit;
}

if ($action === 'resetar_competicao' && (isRepresentante() || isAdmin())) {
    $pdo->exec("DELETE FROM notas_representante");
    $pdo->exec("DELETE FROM skatistas_competicao");
    echo json_encode(['success' => true, 'message' => 'Competição resetada!']);
    exit;
}

// ============================================
// LISTAR SKATERS (RANKING)
// ============================================
if ($action === 'listar_skaters') {
    $stmt = $pdo->query("SELECT id, nome, pais, idade, ROUND(media_geral, 1) as media_geral FROM skatistas_competicao ORDER BY media_geral DESC");
    echo json_encode(['success' => true, 'skaters' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

// ============================================
// VISUALIZAR NOTAS (TODOS)
// ============================================
if ($action === 'listar_notas_detalhadas') {
    $filtro = intval($_POST['skatista_id'] ?? 0);
    if ($filtro > 0) {
        $stmt = $pdo->prepare("SELECT * FROM skatistas_competicao WHERE id = ? ORDER BY media_geral DESC");
        $stmt->execute([$filtro]);
    } else {
        $stmt = $pdo->query("SELECT * FROM skatistas_competicao ORDER BY media_geral DESC");
    }
    $sk = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $st = $pdo->query("SELECT 
        (SELECT COUNT(*) FROM skatistas_competicao) as total_skatistas,
        (SELECT COUNT(DISTINCT representante_id) FROM notas_representante) as total_juizes,
        IFNULL(ROUND(AVG(media_geral), 1), 0) as media_geral,
        IFNULL(ROUND(MAX(media_geral), 1), 0) as maior_nota
        FROM skatistas_competicao")->fetch();
    
    echo json_encode([
        'success' => true,
        'skatistas' => $sk,
        'estatisticas' => [
            'total_skatistas' => $st['total_skatistas'] ?? 0,
            'total_juizes' => $st['total_juizes'] ?? 0,
            'media_geral' => $st['media_geral'] ?? 0,
            'maior_nota' => $st['maior_nota'] ?? 0
        ]
    ]);
    exit;
}

if ($action === 'detalhes_notas_skatista') {
    $sid = intval($_POST['skatista_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT nr.*, u.nome as juiz_nome FROM notas_representante nr 
        INNER JOIN usuarios u ON nr.representante_id = u.id 
        WHERE nr.skatista_id = ? ORDER BY nr.data_avaliacao DESC");
    $stmt->execute([$sid]);
    echo json_encode(['success' => true, 'notas' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

// ============================================
// EVENTOS E PERFIL
// ============================================
if ($action === 'cadastrar_evento' && (isRepresentante() || isAdmin())) {
    $pdo->prepare("INSERT INTO eventos (nome_evento, data_evento, cidade, estado, local_evento, link_evento, usuario_id) VALUES (?,?,?,?,?,?,?)")
        ->execute([$_POST['nome_evento'],$_POST['data_evento'],$_POST['cidade'],$_POST['estado'],$_POST['local_evento'],$_POST['link_evento'],$uid]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'cadastrar_skatista_evento') {
    $nome_evento = trim($_POST['nome_evento'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $check = $pdo->prepare("SELECT id FROM skatistas_eventos WHERE usuario_id = ? AND nome_evento = ?");
    $check->execute([$uid, $nome_evento]);
    if ($check->fetch()) { echo json_encode(['success'=>false,'errors'=>['Já inscrito!']]); exit; }
    
    $idade = 18;
    if (!empty($_SESSION['usuario_data_nascimento'])) {
        $idade = date('Y') - date('Y', strtotime($_SESSION['usuario_data_nascimento']));
    }
    
    $pdo->prepare("INSERT INTO skatistas_eventos (nome_skatista, email, telefone, idade, categoria, nome_evento, usuario_id) VALUES (?,?,?,?,?,?,?)")
        ->execute([$_SESSION['usuario_nome'], $_SESSION['usuario_email'], $_SESSION['usuario_telefone'] ?? '', $idade, $categoria, $nome_evento, $uid]);
    echo json_encode(['success'=>true,'message'=>'Inscrição realizada!']);
    exit;
}

if ($action === 'atualizar_perfil') {
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova = $_POST['nova_senha'] ?? '';
    
    if (!empty($nova)) {
        $check = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $check->execute([$uid]);
        $u = $check->fetch();
        if (!password_verify($senha_atual, $u['senha'])) {
            echo json_encode(['success'=>false,'errors'=>['Senha atual incorreta']]); exit;
        }
        if (strlen($nova) < 6) {
            echo json_encode(['success'=>false,'errors'=>['Nova senha muito curta']]); exit;
        }
        $hash = password_hash($nova, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE usuarios SET nome=?, telefone=?, senha=? WHERE id=?")->execute([$nome,$telefone,$hash,$uid]);
    } else {
        $pdo->prepare("UPDATE usuarios SET nome=?, telefone=? WHERE id=?")->execute([$nome,$telefone,$uid]);
    }
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_telefone'] = $telefone;
    echo json_encode(['success'=>true,'message'=>'Perfil atualizado!']);
    exit;
}

echo json_encode(['success' => false, 'errors' => ['Ação desconhecida']]);