<?php
require_once 'config.php';
exigirLogin();
?>
<?php include 'header.php'; ?>

<main class="container">
    <section class="hero">
        <h1>📊 VISUALIZAÇÃO DE NOTAS</h1>
        <p>Acompanhe as notas atribuídas pelos representantes</p>
    </section>
    
    <div class="form-container" style="max-width:100%; margin-bottom:30px;">
        <div style="display:grid; grid-template-columns:1fr auto; gap:15px; align-items:end;">
            <select id="filtroSkatistaNotas">
                <option value="">Todos os skatistas</option>
            </select>
            <button class="btn" onclick="carregarNotas()">🔄 Atualizar</button>
        </div>
    </div>
    
    <div class="dashboard-stats">
        <div class="dash-stat"><span class="icon">🛹</span><div class="number" id="notasTotalSkatistas">0</div><div class="label">Skatistas</div></div>
        <div class="dash-stat"><span class="icon">👔</span><div class="number" id="notasTotalJuizes">0</div><div class="label">Representantes</div></div>
        <div class="dash-stat"><span class="icon">⭐</span><div class="number" id="notasMediaGeral">0.0</div><div class="label">Média Geral</div></div>
        <div class="dash-stat"><span class="icon">🏆</span><div class="number" id="notasMaiorNota">0.0</div><div class="label">Maior Nota</div></div>
    </div>
    
    <div class="tabela-container" style="margin-top:30px;">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Skatista</th><th>País</th><th>Idade</th>
                    <th>Kickflip</th><th>Heelflip</th><th>Tre Flip</th><th>Varial</th><th>Laser</th>
                    <th>Média</th><th>Status</th>
                </tr>
            </thead>
            <tbody id="tabelaNotasBody">
                <tr><td colspan="11" style="text-align:center;">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
    
    <div id="detalhesJuizes" style="margin-top:30px; display:none;">
        <h3 class="secao-titulo">📋 Detalhamento por Representante</h3>
        <div id="listaNotasJuizes"></div>
    </div>
</main>

<script src="js_notas.js"></script>
<?php include 'footer.php'; ?>