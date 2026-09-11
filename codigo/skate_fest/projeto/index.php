<?php
require_once 'config.php';

// Se já está logado, vai pro dashboard
if (isLogado()) {
    redirecionarDashboard();
}

$erro_login = $_SESSION['erro_login'] ?? null;
$sucesso_registro = $_SESSION['sucesso_registro'] ?? null;
unset($_SESSION['erro_login'], $_SESSION['sucesso_registro']);

$abrirModal = isset($_GET['login']) || $erro_login || $sucesso_registro;
?>
<?php include 'header.php'; ?>

<main class="container">
    <section class="hero">
        <h1>SKATE FEST BRASIL</h1>
        <p>O maior portal de eventos, competições e cultura do skate no Brasil</p>
        <div class="hero-stats">
            <div class="hero-stat"><div class="hero-stat-number">+50</div><div class="hero-stat-label">Eventos Realizados</div></div>
            <div class="hero-stat"><div class="hero-stat-number">+5.000</div><div class="hero-stat-label">Skatistas</div></div>
            <div class="hero-stat"><div class="hero-stat-number">+20</div><div class="hero-stat-label">Cidades</div></div>
        </div>
    </section>
    
    <div class="cards-grid">
        <div class="card">
            <div class="card-icon">🏆</div>
            <h3>Competições</h3>
            <p>Participe das melhores competições de skate do Brasil.</p>
            <button class="btn" onclick="abrirModalLogin()">Entrar →</button>
        </div>
        <div class="card">
            <div class="card-icon">📅</div>
            <h3>Eventos</h3>
            <p>Confira a agenda de eventos de skate em todo o Brasil.</p>
            <a href="eventos.php" class="btn">Ver Eventos →</a>
        </div>
        <div class="card">
            <div class="card-icon">💡</div>
            <h3>Dicas</h3>
            <p>Aprenda novas manobras e cuide do seu equipamento.</p>
            <a href="dicas.php" class="btn">Ver Dicas →</a>
        </div>
        <div class="card">
            <div class="card-icon">👔</div>
            <h3>Sou Representante</h3>
            <p>Cadastre seu evento e alcance skatistas de todo o Brasil.</p>
            <button class="btn" onclick="abrirModalLogin()">Entrar →</button>
        </div>
        <div class="card">
            <div class="card-icon">🛹</div>
            <h3>Sou Skatista</h3>
            <p>Mostre seu talento nas competições.</p>
            <button class="btn" onclick="abrirModalLogin()">Entrar →</button>
        </div>
        <div class="card">
            <div class="card-icon">⚖️</div>
            <h3>Quero ser Juiz</h3>
            <p>Avalie os skatistas nas competições oficiais.</p>
            <button class="btn" onclick="abrirModalLogin()">Entrar →</button>
        </div>
    </div>
</main>

<!-- MODAL LOGIN/CADASTRO -->
<div id="modalLogin" class="modal <?= $abrirModal ? 'active' : '' ?>">
    <div class="modal-content">
        <span class="close-modal" onclick="fecharModalLogin()">&times;</span>
        
        <div class="tabs-modal">
            <button class="tab-btn active" onclick="switchTab('login')" id="tabLogin">🔐 LOGIN</button>
            <button class="tab-btn" onclick="switchTab('register')" id="tabRegister">📝 CADASTRAR</button>
        </div>
        
        <?php if($erro_login): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro_login) ?></div>
        <?php endif; ?>
        <?php if($sucesso_registro): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso_registro) ?></div>
        <?php endif; ?>
        
        <div id="loginForm">
            <form method="POST" action="login.php">
                <input type="email" name="email" placeholder="E-mail" required>
                <div class="senha-container">
                    <input type="password" name="senha" id="senhaInput" placeholder="Senha" required>
                    <span class="toggle-senha" onclick="toggleSenha()">👁️</span>
                </div>
                <button type="submit" name="action_login" class="btn" style="width:100%;">ENTRAR</button>
            </form>
        </div>
        
        <div id="registerForm" style="display:none;">
            <form method="POST" action="login.php">
                <input type="text" name="nome" placeholder="Nome completo" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <div class="senha-container">
                    <input type="password" name="senha" id="senhaRegistro" placeholder="Senha (mín. 6 caracteres)" required>
                    <span class="toggle-senha" onclick="toggleSenhaRegistro()">👁️</span>
                </div>
                <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required>
                <select name="tipo" id="selectTipoReg" required>
                    <option value="competidor">🛹 Competidor</option>
                    <option value="representante">👔 Representante</option>
                    <option value="admin">⚖️ Administrador (requer aprovação)</option>
                </select>
                <input type="tel" name="telefone" placeholder="Telefone (obrigatório para representantes)">
                <div id="camposCompetidor">
                    <input type="date" name="data_nascimento">
                    <select name="categoria">
                        <option value="">Categoria</option>
                        <option value="Iniciante">Iniciante</option>
                        <option value="Amador">Amador</option>
                        <option value="Profissional">Profissional</option>
                        <option value="Master">Master (35+)</option>
                        <option value="Mirim">Mirim (até 12)</option>
                    </select>
                </div>
                <p class="hint">⏳ Após o cadastro, aguarde aprovação do administrador</p>
                <button type="submit" name="action_register" class="btn btn-success" style="width:100%;">CADASTRAR</button>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalLogin() { document.getElementById('modalLogin').classList.add('active'); }
function fecharModalLogin() { document.getElementById('modalLogin').classList.remove('active'); }
function switchTab(tab) {
    const l = document.getElementById('loginForm');
    const r = document.getElementById('registerForm');
    const tl = document.getElementById('tabLogin');
    const tr = document.getElementById('tabRegister');
    if (tab === 'login') {
        l.style.display = 'block'; r.style.display = 'none';
        tl.classList.add('active'); tr.classList.remove('active');
    } else {
        l.style.display = 'none'; r.style.display = 'block';
        tr.classList.add('active'); tl.classList.remove('active');
    }
}
function toggleSenha() { const i = document.getElementById('senhaInput'); i.type = i.type === 'password' ? 'text' : 'password'; }
function toggleSenhaRegistro() { const i = document.getElementById('senhaRegistro'); i.type = i.type === 'password' ? 'text' : 'password'; }
window.onclick = function(e) {
    const m = document.getElementById('modalLogin');
    if (e.target == m) m.classList.remove('active');
};
</script>

<?php include 'footer.php'; ?>