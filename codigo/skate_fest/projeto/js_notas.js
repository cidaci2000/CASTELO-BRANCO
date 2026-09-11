async function carregarNotas() {
    const tbody = document.getElementById('tabelaNotasBody');
    if (!tbody) return;
    const filtro = document.getElementById('filtroSkatistaNotas')?.value || '';
    
    const fd = new FormData();
    fd.append('action', 'listar_notas_detalhadas');
    fd.append('skatista_id', filtro);
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    
    if (!d.success) return;
    
    document.getElementById('notasTotalSkatistas').textContent = d.estatisticas.total_skatistas;
    document.getElementById('notasTotalJuizes').textContent = d.estatisticas.total_juizes;
    document.getElementById('notasMediaGeral').textContent = d.estatisticas.media_geral;
    document.getElementById('notasMaiorNota').textContent = d.estatisticas.maior_nota;
    
    const sel = document.getElementById('filtroSkatistaNotas');
    if (sel && sel.options.length <= 1) {
        d.skatistas.forEach(s => {
            const o = document.createElement('option');
            o.value = s.id; o.textContent = s.nome;
            sel.appendChild(o);
        });
    }
    
    if (d.skatistas.length > 0) {
        tbody.innerHTML = d.skatistas.map((s,i) => {
            const av = parseFloat(s.media_geral) > 0;
            return `
                <tr style="cursor:pointer;" onclick="verDetalhes(${s.id}, '${escapeHtml(s.nome)}')">
                    <td><strong style="color:var(--light-blue);">#${i+1}</strong></td>
                    <td><strong>${escapeHtml(s.nome)}</strong></td>
                    <td>${escapeHtml(s.pais)}</td>
                    <td>${s.idade}</td>
                    <td>${s.kickflip || '—'}</td>
                    <td>${s.heelflip || '—'}</td>
                    <td>${s.tre_flip || '—'}</td>
                    <td>${s.varial || '—'}</td>
                    <td>${s.laser || '—'}</td>
                    <td><strong style="color:var(--gold);font-size:1.2rem;">${s.media_geral}</strong></td>
                    <td><span class="status-badge ${av ? 'com-notas' : 'sem-notas'}">${av ? '✅ Avaliado' : '⏳ Pendente'}</span></td>
                </tr>`;
        }).join('');
    } else {
        tbody.innerHTML = '<tr><td colspan="11" style="text-align:center;">Nenhum skatista</td></tr>';
    }
}

async function verDetalhes(id, nome) {
    const c = document.getElementById('detalhesJuizes');
    const l = document.getElementById('listaNotasJuizes');
    const fd = new FormData();
    fd.append('action', 'detalhes_notas_skatista');
    fd.append('skatista_id', id);
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    
    if (d.success && d.notas.length > 0) {
        c.style.display = 'block';
        l.innerHTML = `
            <div style="background:rgba(111,149,255,0.1);border:1px solid rgba(111,149,255,0.3);border-radius:15px;padding:20px;margin-bottom:20px;">
                <h4 style="color:var(--light-blue);font-family:'Barlow Condensed';font-size:1.5rem;">🛹 ${escapeHtml(nome)}</h4>
                <p style="color:var(--soft-purple);font-size:0.9rem;">${d.notas.length} avaliações</p>
            </div>
            ${d.notas.map(n => `
                <div style="background:rgba(48,28,65,0.7);border:1px solid rgba(111,149,255,0.2);border-radius:15px;padding:20px;margin-bottom:15px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span style="font-size:1.5rem;">👔</span>
                            <div>
                                <div style="color:white;font-weight:700;">${escapeHtml(n.juiz_nome)}</div>
                                <div style="color:var(--soft-purple);font-size:0.75rem;">${new Date(n.data_avaliacao).toLocaleString('pt-BR')}</div>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-family:'Barlow Condensed';font-size:2rem;font-weight:900;color:var(--light-blue);">${n.media}</div>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;">
                        ${['kickflip','heelflip','tre_flip','varial','laser'].map((k,i) => `
                            <div style="text-align:center;background:rgba(28,11,43,0.5);padding:10px;border-radius:10px;">
                                <div style="color:var(--light-blue);font-size:0.7rem;text-transform:uppercase;">${['Kickflip','Heelflip','Tre Flip','Varial','Laser'][i]}</div>
                                <div style="color:white;font-weight:700;font-size:1.2rem;">${n[k]}</div>
                            </div>
                        `).join('')}
                    </div>
                    ${n.observacao ? `<div style="margin-top:12px;padding:12px;background:rgba(111,149,255,0.1);border-radius:10px;border-left:3px solid var(--light-blue);">
                        <span style="color:var(--light-blue);font-size:0.8rem;">💬 ${escapeHtml(n.observacao)}</span>
                    </div>` : ''}
                </div>
            `).join('')}
        `;
        c.scrollIntoView({ behavior:'smooth' });
    } else {
        mostrarToast('⚠️ Nenhuma nota detalhada', 'error');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tabelaNotasBody')) {
        carregarNotas();
        setInterval(carregarNotas, 20000);
    }
});