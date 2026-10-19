<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
exigir_login(['usuario', 'admin']);

$user     = usuario_logado();
$aluno_id = $user['id'];

$msg = ''; $msg_tipo = 'success';

// -------- POST: agendar / cancelar --------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'agendar') {
        $aula_id = (int)($_POST['aula_id'] ?? 0);
        if ($aula_id <= 0) {
            $msg = 'Aula inválida.'; $msg_tipo = 'error';
        } else {
            // Procedure devolve uma linha com "mensagem"
            $stmt = $pdo->prepare("CALL sp_inscrever_aula(?, ?)");
            $stmt->execute([$aula_id, $aluno_id]);
            $res = $stmt->fetch();
            $stmt->closeCursor(); // libera o resultset do CALL

            $mensagem = $res['mensagem'] ?? '';
            if (stripos($mensagem, 'sucesso') !== false) {
                $msg = 'Aula agendada com sucesso!';
            } else {
                $msg = $mensagem ?: 'Não foi possível agendar.';
                $msg_tipo = 'error';
            }
        }
    } elseif ($acao === 'cancelar') {
        $inscricao_id = (int)($_POST['inscricao_id'] ?? 0);
        $stmt = $pdo->prepare("
            UPDATE inscricoes_aulas
            SET status = 'cancelada'
            WHERE id = ? AND aluno_id = ? AND status = 'confirmada'
        ");
        $stmt->execute([$inscricao_id, $aluno_id]);
        if ($stmt->rowCount() > 0) {
            $msg = 'Inscrição cancelada.';
        } else {
            $msg = 'Não foi possível cancelar.'; $msg_tipo = 'error';
        }
    }
}

// -------- Minhas próximas aulas --------
$stmt = $pdo->prepare("
    SELECT i.id AS inscricao_id, i.status,
           a.id AS aula_id, a.titulo, a.modalidade, a.data_aula,
           a.duracao, a.local, u.nome_completo AS instrutor
    FROM inscricoes_aulas i
    JOIN aulas a ON a.id = i.aula_id
    LEFT JOIN usuarios u ON u.id = a.instrutor_id
    WHERE i.aluno_id = ? AND i.status = 'confirmada' AND a.data_aula >= NOW()
    ORDER BY a.data_aula ASC
");
$stmt->execute([$aluno_id]);
$minhas = $stmt->fetchAll();

// -------- Aulas disponíveis --------
$stmt = $pdo->prepare("
    SELECT a.id, a.titulo, a.descricao, a.modalidade, a.data_aula,
           a.duracao, a.capacidade_maxima, a.vagas_disponiveis, a.local,
           u.nome_completo AS instrutor,
           (SELECT COUNT(*) FROM inscricoes_aulas i
            WHERE i.aula_id = a.id AND i.aluno_id = ? AND i.status = 'confirmada') AS ja_inscrito
    FROM aulas a
    LEFT JOIN usuarios u ON u.id = a.instrutor_id
    WHERE a.status = 'agendada' AND a.data_aula >= NOW()
    ORDER BY a.data_aula ASC
");
$stmt->execute([$aluno_id]);
$aulas_disp = $stmt->fetchAll();

// Cores por modalidade
$cores = [
    'Musculação' => '#4f46e5', 'Funcional' => '#22c55e',
    'CrossFit'   => '#ef4444', 'Yoga'      => '#eab308', 'Luta' => '#f97316',
];
function cor_mod($m, $map) { return $map[$m] ?? '#818cf8'; }

// Agrupa por data para o calendário
$por_data = [];
foreach ($aulas_disp as $a) {
    $por_data[date('Y-m-d', strtotime($a['data_aula']))][] = $a;
}
$minhas_datas = [];
foreach ($minhas as $m) {
    $minhas_datas[date('Y-m-d', strtotime($m['data_aula']))] = true;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda — SportLife</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&family=Bebas+Neue&family=Syncopate:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #4f46e5;
            --deep-purple: #1e1b4b;
            --neon-glow: #818cf8;
            --bg-black: #0a0a0f;
            --card-gray: rgba(255, 255, 255, 0.03);
            --border: rgba(79, 70, 229, 0.15);
            --text-muted: #94a3b8;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-black); color: white; line-height: 1.6; min-height: 100vh; }
        .glow { position: fixed; width: 400px; height: 400px; background: #3730a3; filter: blur(150px); border-radius: 50%; opacity: 0.12; z-index: -1; }
        .glow.top { top: -100px; left: 30%; }
        .glow.bottom { bottom: -100px; right: -100px; }

        header {
            padding: 20px 5%; display: flex; justify-content: space-between; align-items: center;
            background: rgba(10,10,15,0.6); backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 100;
        }
        .logo {
            font-family: 'Syncopate', sans-serif; font-size: 1.3rem; letter-spacing: 4px;
            background: linear-gradient(to right, #e2e8f0, var(--primary-purple));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none;
        }
        .user-area { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; justify-content: center; }
        .user-greeting { color: var(--text-muted); font-size: 0.8rem; letter-spacing: 1px; }
        .user-greeting strong { color: white; }
        .btn-logout, .btn-back {
            background: transparent; color: rgba(255,255,255,0.7);
            border: 1px solid var(--border); padding: 8px 18px; border-radius: 50px;
            font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px;
            transition: 0.3s; cursor: pointer; text-decoration: none;
        }
        .btn-logout:hover { color: #ef4444; border-color: #ef4444; }
        .btn-back:hover { color: var(--primary-purple); border-color: var(--primary-purple); }

        .container { max-width: 1200px; margin: 0 auto; padding: 50px 5%; }
        .page-title {
            font-family: 'Bebas Neue', sans-serif; font-size: clamp(2.5rem, 6vw, 4rem);
            letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;
        }
        .page-title span { color: var(--primary-purple); }
        .page-sub { color: var(--text-muted); margin-bottom: 30px; letter-spacing: 1px; font-size: 0.85rem; }

        .alert { padding: 14px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; margin-bottom: 25px; border-left: 4px solid; }
        .alert-success { background: rgba(34,197,94,0.1); border-color: #22c55e; color: #86efac; }
        .alert-error   { background: rgba(239,68,68,0.1); border-color: #ef4444; color: #fca5a5; }

        /* CALENDÁRIO */
        .calendario-wrapper { background: var(--card-gray); border: 1px solid var(--border); border-radius: 20px; padding: 30px; margin-bottom: 50px; }
        .calendario-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .calendario-titulo { font-family: 'Bebas Neue', sans-serif; font-size: 2rem; letter-spacing: 2px; text-transform: uppercase; color: var(--neon-glow); }
        .cal-nav { display: flex; gap: 10px; }
        .cal-nav button {
            background: rgba(79,70,229,0.1); border: 1px solid var(--border);
            color: white; padding: 8px 16px; border-radius: 10px;
            font-size: 0.85rem; cursor: pointer; transition: 0.3s; font-weight: 700;
        }
        .cal-nav button:hover { background: var(--primary-purple); border-color: var(--primary-purple); }

        .calendario-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
        .cal-dia-semana { text-align: center; font-size: 0.7rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: var(--text-muted); padding: 10px 0; }
        .cal-dia {
            aspect-ratio: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;
            background: rgba(255,255,255,0.02); border: 1px solid transparent;
            border-radius: 12px; font-size: 0.95rem; cursor: pointer;
            transition: 0.3s; position: relative; color: white;
        }
        .cal-dia:hover { background: rgba(79,70,229,0.1); border-color: var(--border); }
        .cal-dia.outro-mes { color: #334155; cursor: default; }
        .cal-dia.outro-mes:hover { background: rgba(255,255,255,0.02); border-color: transparent; }
        .cal-dia.hoje { border-color: var(--primary-purple); background: rgba(79,70,229,0.1); font-weight: 800; }
        .cal-dia.selecionado { background: var(--primary-purple); border-color: var(--primary-purple); color: white; box-shadow: 0 0 20px rgba(79,70,229,0.5); }
        .cal-dia .dots { display: flex; gap: 3px; }
        .cal-dia .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--neon-glow); }
        .cal-dia .dot.inscrito { background: #22c55e; }

        /* AULAS */
        .section-title {
            font-family: 'Bebas Neue', sans-serif; font-size: 1.8rem; letter-spacing: 2px;
            text-transform: uppercase; margin-bottom: 20px;
            border-left: 3px solid var(--primary-purple); padding-left: 15px;
        }
        .aulas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; margin-bottom: 50px; }
        .aula-card {
            background: var(--card-gray); border: 1px solid var(--border);
            border-radius: 16px; padding: 22px; transition: 0.4s;
            position: relative; overflow: hidden;
        }
        .aula-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
            background: var(--cor);
        }
        .aula-card:hover { border-color: var(--primary-purple); transform: translateY(-5px); }
        .aula-modalidade {
            font-family: 'Bebas Neue', sans-serif; font-size: 1.3rem;
            letter-spacing: 2px; text-transform: uppercase; color: var(--cor);
            margin-bottom: 4px;
        }
        .aula-titulo { font-size: 0.95rem; font-weight: 700; margin-bottom: 10px; }
        .aula-info { color: var(--text-muted); font-size: 0.78rem; margin-bottom: 15px; line-height: 1.7; }
        .aula-info strong { color: white; }
        .vagas-bar {
            width: 100%; height: 6px; background: rgba(255,255,255,0.05);
            border-radius: 50px; overflow: hidden; margin: 8px 0 15px;
        }
        .vagas-bar span { display: block; height: 100%; background: var(--cor); border-radius: 50px; }

        .btn-agendar {
            width: 100%; padding: 12px; border-radius: 10px; border: none;
            font-weight: 800; text-transform: uppercase; letter-spacing: 2px;
            font-size: 0.7rem; cursor: pointer; transition: 0.4s;
            background: var(--primary-purple); color: white;
        }
        .btn-agendar:hover:not(:disabled) { background: white; color: black; }
        .btn-agendar:disabled { background: #1e293b; color: #475569; cursor: not-allowed; }
        .btn-agendar.ja-inscrito { background: rgba(34,197,94,0.15); color: #86efac; border: 1px solid #22c55e; cursor: default; }

        /* LISTA MINHAS AULAS */
        .lista-agendamentos { background: var(--card-gray); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .ag-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 22px 25px; border-bottom: 1px solid var(--border); gap: 15px; flex-wrap: wrap;
        }
        .ag-item:last-child { border-bottom: none; }
        .ag-item .esquerda strong { display: block; font-size: 1rem; }
        .ag-item .esquerda span { color: var(--text-muted); font-size: 0.8rem; }
        .ag-item .esquerda span strong { color: var(--neon-glow); }
        .btn-cancelar {
            padding: 8px 18px; border-radius: 50px; border: 1px solid #ef4444;
            background: transparent; color: #fca5a5; font-weight: 700;
            text-transform: uppercase; font-size: 0.65rem; letter-spacing: 1px;
            cursor: pointer; transition: 0.3s;
        }
        .btn-cancelar:hover { background: #ef4444; color: white; }
        .vazio { padding: 40px; text-align: center; color: var(--text-muted); font-size: 0.85rem; }
        .vazio small { display: block; margin-top: 6px; font-size: 0.75rem; }

        .toast {
            position: fixed; bottom: 30px; right: 30px;
            padding: 15px 25px; border-radius: 12px;
            background: var(--deep-purple); border: 1px solid var(--primary-purple);
            color: white; font-weight: 600; font-size: 0.9rem; z-index: 9999;
            box-shadow: 0 10px 40px rgba(79,70,229,0.3);
            transform: translateY(100px); opacity: 0; transition: all 0.5s ease;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { border-color: #ef4444; }

        @media (max-width: 640px) {
            header { flex-direction: column; gap: 12px; text-align: center; }
            .calendario-grid { gap: 4px; }
            .cal-dia { font-size: 0.8rem; }
            .ag-item { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
<div class="glow top"></div>
<div class="glow bottom"></div>

<header>
    <a href="dashboard.php" class="logo">SPORTLIFE</a>
    <div class="user-area">
        <span class="user-greeting">Olá, <strong><?= htmlspecialchars($user['nome']) ?></strong></span>
        <a href="dashboard.php" class="btn-back">← Painel</a>
        <a href="../php/logout.php" class="btn-logout">Sair</a>
    </div>
</header>

<div class="container">
    <h1 class="page-title">Minha <span>Agenda</span></h1>
    <p class="page-sub">Escolha um dia no calendário para ver as aulas disponíveis</p>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msg_tipo ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- CALENDÁRIO -->
    <div class="calendario-wrapper">
        <div class="calendario-header">
            <div class="calendario-titulo" id="cal-titulo">—</div>
            <div class="cal-nav">
                <button type="button" onclick="mudarMes(-1)">‹ Anterior</button>
                <button type="button" onclick="irHoje()">Hoje</button>
                <button type="button" onclick="mudarMes(1)">Próximo ›</button>
            </div>
        </div>
        <div class="calendario-grid" id="cal-grid"></div>
    </div>

    <!-- AULAS DISPONÍVEIS -->
    <h2 class="section-title">Aulas disponíveis em <span id="data-selecionada" style="color:var(--neon-glow)">—</span></h2>

    <div class="aulas-grid" id="aulas-grid">
        <?php foreach ($aulas_disp as $a): 
            $cor = cor_mod($a['modalidade'], $cores);
            $dataKey = date('Y-m-d', strtotime($a['data_aula']));
            $hora = date('H:i', strtotime($a['data_aula']));
            $dataFmt = date('d/m/Y', strtotime($a['data_aula']));
            $ocupadas = $a['capacidade_maxima'] - $a['vagas_disponiveis'];
            $percent  = $a['capacidade_maxima'] > 0 ? ($ocupadas / $a['capacidade_maxima']) * 100 : 0;
            $esgotada = $a['vagas_disponiveis'] <= 0;
            $jaInscrito = (int)$a['ja_inscrito'] > 0;
        ?>
            <div class="aula-card" style="--cor: <?= $cor ?>" data-data="<?= $dataKey ?>">
                <div class="aula-modalidade"><?= htmlspecialchars($a['modalidade']) ?></div>
                <div class="aula-titulo"><?= htmlspecialchars($a['titulo']) ?></div>

                <div class="aula-info">
                    📅 <strong><?= $dataFmt ?></strong> às <strong><?= $hora ?></strong><br>
                    ⏱ <?= (int)$a['duracao'] ?> min · 📍 <?= htmlspecialchars($a['local'] ?? '—') ?><br>
                    🧑‍🏫 <?= htmlspecialchars($a['instrutor'] ?? 'Sem instrutor') ?><br>
                    👥 <strong><?= (int)$a['vagas_disponiveis'] ?></strong> de <?= (int)$a['capacidade_maxima'] ?> vagas
                </div>

                <div class="vagas-bar"><span style="width: <?= $percent ?>%"></span></div>

                <?php if ($jaInscrito): ?>
                    <button class="btn-agendar ja-inscrito" disabled>✓ Já inscrito</button>
                <?php elseif ($esgotada): ?>
                    <button class="btn-agendar" disabled>Esgotado</button>
                <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="acao" value="agendar">
                        <input type="hidden" name="aula_id" value="<?= (int)$a['id'] ?>">
                        <button type="submit" class="btn-agendar">Agendar</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- MINHAS AULAS -->
    <h2 class="section-title">Minhas próximas aulas</h2>
    <div class="lista-agendamentos">
        <?php if (empty($minhas)): ?>
            <div class="vazio">
                Você ainda não tem aulas agendadas.
                <small>Escolha um dia no calendário acima para agendar 💪</small>
            </div>
        <?php else: ?>
            <?php foreach ($minhas as $ag): ?>
                <div class="ag-item">
                    <div class="esquerda">
                        <strong><?= htmlspecialchars($ag['titulo']) ?> — <?= htmlspecialchars($ag['modalidade']) ?></strong>
                        <span>
                            <strong><?= date('d/m/Y H:i', strtotime($ag['data_aula'])) ?></strong> · 
                            <?= (int)$ag['duracao'] ?> min · 
                            <?= htmlspecialchars($ag['local'] ?? '—') ?> · 
                            🧑‍🏫 <?= htmlspecialchars($ag['instrutor'] ?? '—') ?>
                        </span>
                    </div>
                    <form method="POST" onsubmit="return confirm('Cancelar esta aula?')">
                        <input type="hidden" name="acao" value="cancelar">
                        <input type="hidden" name="inscricao_id" value="<?= (int)$ag['inscricao_id'] ?>">
                        <button type="submit" class="btn-cancelar">Cancelar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php if ($msg): ?>
    <div class="toast <?= $msg_tipo === 'error' ? 'error' : '' ?>" id="toast"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<script>
// ============================================================
// Calendário
// ============================================================
const grid        = document.getElementById('cal-grid');
const titulo      = document.getElementById('cal-titulo');
const dataSelEl   = document.getElementById('data-selecionada');
const diasSemana  = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
const meses       = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

// Dados vindos do PHP
const aulasPorData   = <?= json_encode($por_data, JSON_UNESCAPED_UNICODE) ?>;
const minhasDatas    = <?= json_encode(array_keys($minhas_datas), JSON_UNESCAPED_UNICODE) ?>;

let hoje     = new Date();
let mesAtual = hoje.getMonth();
let anoAtual = hoje.getFullYear();
let dataSel  = null;

function renderCalendario() {
    titulo.textContent = meses[mesAtual] + ' ' + anoAtual;
    grid.innerHTML = '';

    diasSemana.forEach(d => {
        const el = document.createElement('div');
        el.className = 'cal-dia-semana';
        el.textContent = d;
        grid.appendChild(el);
    });

    const primeiro     = new Date(anoAtual, mesAtual, 1);
    const ultimo       = new Date(anoAtual, mesAtual + 1, 0);
    const diasNoMes    = ultimo.getDate();
    const inicioSemana = primeiro.getDay();

    // Dias do mês anterior
    const ultimoAnterior = new Date(anoAtual, mesAtual, 0).getDate();
    for (let i = inicioSemana - 1; i >= 0; i--) {
        const el = document.createElement('div');
        el.className = 'cal-dia outro-mes';
        el.textContent = ultimoAnterior - i;
        grid.appendChild(el);
    }

    // Dias do mês
    for (let d = 1; d <= diasNoMes; d++) {
        const el = document.createElement('div');
        el.className = 'cal-dia';

        const dataStr = `${anoAtual}-${String(mesAtual+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const dObj = new Date(anoAtual, mesAtual, d);

        // Número + dots
        const num = document.createElement('span');
        num.textContent = d;
        el.appendChild(num);

        const temAulas = aulasPorData[dataStr] && aulasPorData[dataStr].length > 0;
        const temMinha = minhasDatas.includes(dataStr);

        if (temAulas || temMinha) {
            const dots = document.createElement('div');
            dots.className = 'dots';
            if (temAulas) {
                const dot = document.createElement('span');
                dot.className = 'dot';
                dots.appendChild(dot);
            }
            if (temMinha) {
                const dot = document.createElement('span');
                dot.className = 'dot inscrito';
                dots.appendChild(dot);
            }
            el.appendChild(dots);
        }

        if (dObj.toDateString() === hoje.toDateString()) el.classList.add('hoje');
        if (dataSel === dataStr) el.classList.add('selecionado');

        if (temAulas) {
            el.onclick = () => {
                dataSel = dataStr;
                renderCalendario();
                filtrarAulas();
            };
        } else {
            el.style.cursor = 'default';
        }

        grid.appendChild(el);
    }
}

function mudarMes(delta) {
    mesAtual += delta;
    if (mesAtual < 0)  { mesAtual = 11; anoAtual--; }
    if (mesAtual > 11) { mesAtual = 0;  anoAtual++; }
    renderCalendario();
}

function irHoje() {
    hoje = new Date();
    mesAtual = hoje.getMonth();
    anoAtual = hoje.getFullYear();
    renderCalendario();
}

// ============================================================
// Filtra aulas pela data selecionada
// ============================================================
function filtrarAulas() {
    if (!dataSel) return;

    const dObj = new Date(dataSel + 'T00:00:00');
    dataSelEl.textContent = dObj.toLocaleDateString('pt-BR', { day:'2-digit', month:'long', year:'numeric' });

    document.querySelectorAll('.aula-card').forEach(card => {
        if (card.dataset.data === dataSel) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Estado inicial: se hoje tem aulas, seleciona
const hojeStr = hoje.getFullYear() + '-' + String(hoje.getMonth()+1).padStart(2,'0') + '-' + String(hoje.getDate()).padStart(2,'0');
if (aulasPorData[hojeStr]) {
    dataSel = hojeStr;
    dataSelEl.textContent = hoje.toLocaleDateString('pt-BR', { day:'2-digit', month:'long', year:'numeric' });
    document.querySelectorAll('.aula-card').forEach(card => {
        card.style.display = (card.dataset.data === dataSel) ? '' : 'none';
    });
} else {
    document.querySelectorAll('.aula-card').forEach(card => card.style.display = 'none');
}

// Toast
const toast = document.getElementById('toast');
if (toast) {
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => toast.classList.remove('show'), 4000);
}

renderCalendario();
</script>

</body>
</html>