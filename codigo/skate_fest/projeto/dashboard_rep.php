<?php
require_once 'config.php';
exigirRepresentante();
?>
<?php include 'header.php'; ?>

<main class="container">
    <section class="hero">
        <h1>👔 PAINEL DO REPRESENTANTE</h1>
        <p>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>! Avalie os skatistas.</p>
        <?php if(!empty($_SESSION['usuario_empresa'])): ?>
            <div class="subtitle">🏢 <?= htmlspecialchars($_SESSION['usuario_empresa']) ?></div>
        <?php endif; ?>
    </section>
    
    <div class="dashboard-stats">
        <div class="dash-stat"><span class="icon">🛹</span><div class="number" id="repStatTotal">0</div><div class="label">Total Skatistas</div></div>
        <div class="dash-stat"><span class="icon">✅</span><div class="number" id="repStatAvaliados">0</div><div class="label">Avaliados</div></div>
        <div class="dash-stat"><span class="icon">⏳</span><div class="number" id="repStatPendentes">0</div><div class="label">Pendentes</div></div>
        <div class="dash-stat"><span class="icon">⭐</span><div class="number" id="repStatMedia">0.0</div><div class="label">Média Geral</div></div>
    </div>
    
    <h3 class="secao-titulo">➕ CADASTRAR NOVO SKATISTA</h3>
    <div class="form-container" style="max-width:100%; margin-bottom:30px;">
        <form id="formCadastroSkatistaRep" class="cadastro-form">
            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="text" name="pais" placeholder="País" required>
            <input type="number" name="idade" placeholder="Idade" min="10" max="60" required>
            <button type="submit" class="btn">CADASTRAR</button>
        </form>
    </div>
    
    <div class="grid-2col">
        <div class="card-competicao">
            <div class="card-header"><h2>🛹 SKATISTAS</h2></div>
            <div class="lista-skaters" id="listaSkatersRep" style="max-height:600px;">
                <div class="loading">⏳ Carregando...</div>
            </div>
        </div>
        
        <div class="card-competicao">
            <div class="card-header"><h2>📝 ATRIBUIR NOTAS</h2></div>
            <div class="form-competicao">
                <div id="repSkatistaSelecionadoInfo" class="empty-state">
                    <span class="icon">👈</span>
                    <p>Selecione um skatista na lista ao lado</p>
                </div>
                
                <form id="formNotasRep" style="display:none;">
                    <input type="hidden" name="skatista_id" id="repSkatistaIdInput">
                    <div class="skatista-selecionado">
                        <div class="nome" id="repNomeSkatistaSelecionado">—</div>
                        <div class="info" id="repInfoSkatistaSelecionado">—</div>
                    </div>
                    
                    <div class="notas-grid">
                        <div class="nota-item"><label>KICKFLIP</label><input type="number" name="kickflip" class="input-nota-rep" min="0" max="10" step="0.1" placeholder="0.0" required></div>
                        <div class="nota-item"><label>HEELFLIP</label><input type="number" name="heelflip" class="input-nota-rep" min="0" max="10" step="0.1" placeholder="0.0" required></div>
                        <div class="nota-item"><label>TRE FLIP</label><input type="number" name="tre_flip" class="input-nota-rep" min="0" max="10" step="0.1" placeholder="0.0" required></div>
                        <div class="nota-item"><label>VARIAL</label><input type="number" name="varial" class="input-nota-rep" min="0" max="10" step="0.1" placeholder="0.0" required></div>
                        <div class="nota-item"><label>LASER</label><input type="number" name="laser" class="input-nota-rep" min="0" max="10" step="0.1" placeholder="0.0" required></div>
                    </div>
                    
                    <textarea name="observacao" rows="2" placeholder="Observação (opcional)"></textarea>
                    
                    <div class="media-preview">
                        <div class="valor" id="repMediaPreview">0.0</div>
                        <div class="label">Sua Média</div>
                    </div>
                    
                    <button type="submit" class="btn btn-success" style="width:100%; padding:15px;">SALVAR NOTAS</button>
                </form>
            </div>
        </div>
    </div>
    
    <h3 class="secao-titulo">🏆 RANKING</h3>
    <div class="card-competicao">
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-valor" id="repTotalStats">0</div><div>PARTICIPANTES</div></div>
            <div class="stat-card"><div class="stat-valor" id="repMediaStats">0.0</div><div>MÉDIA</div></div>
            <div class="stat-card"><div class="stat-valor" id="repMaiorStats">0.0</div><div>MAIOR NOTA</div></div>
        </div>
        <div class="podium">
            <div class="podium-item"><div class="podium-rank">🥈 2º</div><div class="podium-base segundo"></div><div class="podium-info" id="repPodium2">—</div></div>
            <div class="podium-item"><div class="podium-rank">👑 1º</div><div class="podium-base primeiro"></div><div class="coroa">👑</div><div class="podium-info" id="repPodium1">—</div></div>
            <div class="podium-item"><div class="podium-rank">🥉 3º</div><div class="podium-base terceiro"></div><div class="podium-info" id="repPodium3">—</div></div>
        </div>
        <div class="lista-skaters" id="repListaRanking" style="max-height:400px;"></div>
        <button id="repResetarCompeticao" class="btn btn-danger" style="width:calc(100% - 40px); margin:0 20px 25px;">🔥 RESETAR COMPETIÇÃO</button>
    </div>
</main>

<script src="js_representante.js"></script>
<?php include 'footer.php'; ?>