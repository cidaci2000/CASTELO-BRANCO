// ============================================
// PAINEL DO REPRESENTANTE
// ============================================
async function repCarregarEstatisticas() {
    if (!document.getElementById('repStatTotal')) return;
    const fd = new FormData();
    fd.append('action', 'estatisticas_representante');
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    if (d.success) {
        document.getElementById('repStatTotal').textContent = d.stats.total;
        document.getElementById('repStatAvaliados').textContent = d.stats.avaliados;
        document.getElementById('repStatPendentes').textContent = d.stats.pendentes;
        document.getElementById('repStatMedia').textContent = d.stats.media;
    }
}

async function repCarregarSkatistas() {
    const c = document.getElementById('listaSkatersRep');
    if (!c) return;
    const fd = new FormData();
    fd.append('action', 'listar_skatistas_representante');
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    
    if (d.success && d.skatistas.length > 0) {
        c.innerHTML = d.skatistas.map(s => {
            const av = parseInt(s.ja_avaliou) > 0;
            return `
                <div class="skater-item" style="cursor:pointer;" 
                     data-id="${s.id}" data-nome="${escapeHtml(s.nome)}" 
                     data-pais="${escapeHtml(s.pais)}" data-idade="${s.idade}" 
                     onclick="repSelecionar(this)">
                    <div>
                        <div style="color:white; font-weight:600;">🛹 ${escapeHtml(s.nome)}</div>
                        <div style="color:var(--light-blue); font-size:0.8rem;">${escapeHtml(s.pais)} • ${s.idade} anos</div>
                    </div>
                    <span class="status-badge ${av ? 'com-notas' : 'sem-notas'}">
                        ${av ? '✅ Avaliado' : '⏳ Pendente'}
                    </span>
                </div>`;
        }).join('');
    } else {
        c.innerHTML = '<div class="empty-state"><span class="icon">🛹</span><p>Nenhum skatista</p></div>';
    }
}

async function repCarregarRanking() {
    const l = document.getElementById('repListaRanking');
    if (!l) return;
    const fd = new FormData();
    fd.append('action', 'listar_skaters');
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    if (!d.success) return;
    
    const sk = d.skaters;
    const t3 = sk.slice(0, 3);
    document.getElementById('repPodium1').innerHTML = t3[0] ? `<strong>${escapeHtml(t3[0].nome)}</strong><br>Média: ${t3[0].media_geral}` : '—';
    document.getElementById('repPodium2').innerHTML = t3[1] ? `<strong>${escapeHtml(t3[1].nome)}</strong><br>Média: ${t3[1].media_geral}` : '—';
    document.getElementById('repPodium3').innerHTML = t3[2] ? `<strong>${escapeHtml(t3[2].nome)}</strong><br>Média: ${t3[2].media_geral}` : '—';
    
    if (sk.length > 0) {
        l.innerHTML = sk.map((s,i) => {
            const tn = parseFloat(s.media_geral) > 0;
            return `
                <div class="skater-item">
                    <span style="font-family:'Barlow Condensed';font-size:1.5rem;font-weight:900;color:#5C65C0;">#${i+1}</span>
                    <div style="flex:1;margin-left:15px;">
                        <div style="color:white;font-weight:bold;">${escapeHtml(s.nome)}</div>
                        <div style="color:#6F95FF;font-size:0.8rem;">${escapeHtml(s.pais)} • ${s.idade} anos</div>
                    </div>
                    <span class="status-badge ${tn ? 'com-notas' : 'sem-notas'}">${tn ? '✅' : '⏳'}</span>
                    <span style="font-family:'Barlow Condensed';font-size:1.6rem;font-weight:900;color:#6F95FF;margin-left:10px;">${s.media_geral}</span>
                </div>`;
        }).join('');
    }
    
    const tot = sk.length;
    const med = sk.map(s => parseFloat(s.media_geral)||0);
    document.getElementById('repTotalStats').textContent = tot;
    document.getElementById('repMediaStats').textContent = tot > 0 ? (med.reduce((a,b)=>a+b,0)/tot).toFixed(1) : '0.0';
    document.getElementById('repMaiorStats').textContent = tot > 0 ? Math.max(...med).toFixed(1) : '0.0';
}

async function repSelecionar(el) {
    document.querySelectorAll('#listaSkatersRep .skater-item').forEach(i => {
        i.style.borderColor = ''; i.style.background = '';
    });
    el.style.borderColor = 'var(--light-blue)';
    el.style.background = 'rgba(111,149,255,0.1)';
    
    const id = el.dataset.id;
    document.getElementById('repSkatistaIdInput').value = id;
    document.getElementById('repNomeSkatistaSelecionado').textContent = el.dataset.nome;
    document.getElementById('repInfoSkatistaSelecionado').textContent = `${el.dataset.pais} • ${el.dataset.idade} anos`;
    document.getElementById('repSkatistaSelecionadoInfo').style.display = 'none';
    document.getElementById('formNotasRep').style.display = 'block';
    
    const fd = new FormData();
    fd.append('action', 'buscar_notas_skatista');
    fd.append('skatista_id', id);
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    const form = document.getElementById('formNotasRep');
    
    if (d.success && d.notas) {
        form.kickflip.value = d.notas.kickflip;
        form.heelflip.value = d.notas.heelflip;
        form.tre_flip.value = d.notas.tre_flip;
        form.varial.value = d.notas.varial;
        form.laser.value = d.notas.laser;
        form.observacao.value = d.notas.observacao || '';
    } else {
        form.reset();
        document.getElementById('repSkatistaIdInput').value = id;
    }
    repCalcMedia();
}

function repCalcMedia() {
    const f = document.getElementById('formNotasRep');
    if (!f) return;
    const n = ['kickflip','heelflip','tre_flip','varial','laser'].map(x => parseFloat(f[x].value)||0);
    document.getElementById('repMediaPreview').textContent = (n.reduce((a,b)=>a+b,0)/5).toFixed(1);
}

document.addEventListener('input', e => {
    if (e.target.classList.contains('input-nota-rep')) {
        let v = parseFloat(e.target.value);
        if (isNaN(v)) { e.target.value = ''; return; }
        if (v < 0) e.target.value = 0;
        if (v > 10) e.target.value = 10;
        if (e.target.value.includes('.')) {
            const p = e.target.value.split('.');
            if (p[1].length > 1) e.target.value = parseFloat(e.target.value).toFixed(1);
        }
        repCalcMedia();
    }
});

const formNotasRep = document.getElementById('formNotasRep');
if (formNotasRep) {
    formNotasRep.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(formNotasRep);
        fd.append('action', 'salvar_notas_representante');
        const r = await fetch('ajax.php', { method:'POST', body:fd });
        const d = await r.json();
        if (d.success) {
            mostrarToast('✅ ' + d.message, 'success');
            await repCarregarSkatistas();
            await repCarregarEstatisticas();
            await repCarregarRanking();
        } else {
            mostrarToast('❌ ' + d.errors.join(', '), 'error');
        }
    });
}

const formCadRep = document.getElementById('formCadastroSkatistaRep');
if (formCadRep) {
    formCadRep.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(formCadRep);
        fd.append('action', 'cadastrar_skater_competicao_rep');
        const r = await fetch('ajax.php', { method:'POST', body:fd });
        const d = await r.json();
        if (d.success) {
            mostrarToast('✅ ' + d.message, 'success');
            formCadRep.reset();
            await repCarregarSkatistas();
            await repCarregarEstatisticas();
            await repCarregarRanking();
        } else {
            mostrarToast('❌ ' + d.errors.join(', '), 'error');
        }
    });
}

const repReset = document.getElementById('repResetarCompeticao');
if (repReset) {
    repReset.addEventListener('click', async () => {
        if (!confirm('🔥 TEM CERTEZA? TODOS OS SKATISTAS SERÃO REMOVIDOS!')) return;
        const fd = new FormData();
        fd.append('action', 'resetar_competicao');
        const r = await fetch('ajax.php', { method:'POST', body:fd });
        const d = await r.json();
        if (d.success) {
            mostrarToast('🏆 ' + d.message, 'success');
            await repCarregarSkatistas();
            await repCarregarEstatisticas();
            await repCarregarRanking();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('listaSkatersRep')) {
        repCarregarSkatistas();
        repCarregarEstatisticas();
        repCarregarRanking();
        setInterval(repCarregarSkatistas, 30000);
    }
});