'use strict';

const state = { nota: 0, filtro: 'todos' };
const $  = (s, ctx = document) => ctx.querySelector(s);
const $$ = (s, ctx = document) => Array.from(ctx.querySelectorAll(s));

function exigirLogin() {
    if (!window.CINEVORTEX?.logado) {
        alert('Você precisa estar logado.');
        window.location.href = 'login.php';
        return false;
    }
    return true;
}

/* =========================================================
   ESTRELAS
   ========================================================= */
function pintarEstrelas(n) {
    $$('.estrela').forEach((el, i) => {
        el.textContent = i < n ? '★' : '☆';
        el.classList.toggle('ativa', i < n);
    });
    const textos = ['Clique nas estrelas', 'Ruim', 'Regular', 'Bom', 'Muito bom', 'Obra-prima'];
    const txt = document.getElementById('notaTexto');
    if (txt) txt.textContent = textos[n] || textos[0];
}

function initEstrelas() {
    $$('.estrela').forEach(el => {
        el.addEventListener('click', () => {
            state.nota = Number(el.dataset.nota);
            pintarEstrelas(state.nota);
        });
    });
    pintarEstrelas(0);
}

/* =========================================================
   AVALIAÇÃO
   ========================================================= */
async function enviarAvaliacao() {
    if (!exigirLogin()) return;

    const filmeId = Number($('#filme').value || 0);
    const comentario = $('#comentario').value.trim();

    if (!filmeId)     { alert('Escolha um filme.'); return; }
    if (state.nota < 1) { alert('Escolha uma nota de 1 a 5.'); return; }

    const btn = $('#btnEnviarAvaliacao');
    const orig = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Enviando...';

    const fd = new FormData();
    fd.append('filme_id', filmeId);
    fd.append('nota', state.nota);
    fd.append('comentario', comentario);

    try {
        const r = await fetch('api/avaliacoes_enviar.php', { method: 'POST', body: fd });
        const j = await r.json();
        if (!j.success) throw new Error(j.message || 'Erro');
        alert('Avaliação salva! 🎉');
        location.reload();
    } catch (err) {
        alert(err.message);
    } finally {
        btn.disabled = false;
        btn.textContent = orig;
    }
}

/* =========================================================
   FILTROS
   ========================================================= */
function filtrar(filtro) {
    state.filtro = filtro;
    $$('.filtro-btn').forEach(b => b.classList.toggle('active', b.dataset.filtro === filtro));

    $$('.filme-card').forEach(card => {
        const st = card.dataset.status || '';
        const mostrar =
            filtro === 'todos'      ? true :
            filtro === 'quero-ver'  ? st === 'quero_ver' :
            filtro === 'assistidos' ? st === 'assistido' :
            filtro === 'favoritos'  ? st === 'favorito' : true;
        card.style.display = mostrar ? '' : 'none';
    });
}

/* =========================================================
   STATUS (cicla quero_ver → assistido → favorito → quero_ver)
   ========================================================= */
async function alterarStatus(filmeId, status, card) {
    if (!exigirLogin()) return;

    const fd = new FormData();
    fd.append('filme_id', filmeId);
    fd.append('status', status);

    try {
        const r = await fetch('api/biblioteca_status.php', { method: 'POST', body: fd });
        const j = await r.json();
        if (!j.success) throw new Error(j.message || 'Erro');
        location.reload();
    } catch (err) {
        alert(err.message);
    }
}

/* =========================================================
   MODAL: ADICIONAR FILME
   ========================================================= */
function abrirModalAdicionar() {
    if (!exigirLogin()) return;
    const modal = $('#modalAdicionarFilme');
    const erro  = $('#modalErro');
    if (erro) erro.style.display = 'none';
    $('#formAdicionarFilme')?.reset();
    modal.style.display = 'flex';
    setTimeout(() => $('#novoTitulo').focus(), 100);
}

function fecharModalAdicionar() {
    const modal = $('#modalAdicionarFilme');
    if (modal) modal.style.display = 'none';
}

function mostrarErroModal(msg) {
    const erro = $('#modalErro');
    if (!erro) return;
    erro.textContent = msg;
    erro.style.display = 'block';
}

async function salvarFilme() {
    if (!exigirLogin()) return;

    const titulo = $('#novoTitulo').value.trim();
    const ano    = $('#novoAno').value;
    const genero = $('#novoGenero').value.trim();
    const imagem = $('#novaImagem').value.trim();
    const descricao = $('#novaDescricao').value.trim();

    if (!titulo) { return mostrarErroModal('Informe o título.'); }
    if (!ano || ano < 1888 || ano > 2100) {
        return mostrarErroModal('Ano deve estar entre 1888 e 2100.');
    }

    const btn = $('#btnSalvarFilme');
    const orig = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Salvando...';

    const fd = new FormData();
    fd.append('titulo', titulo);
    fd.append('ano', ano);
    fd.append('genero', genero);
    fd.append('url_imagem', imagem);
    fd.append('descricao', descricao);

    try {
        const r = await fetch('api/filmes_adicionar.php', { method: 'POST', body: fd });
        const j = await r.json();
        if (!j.success) throw new Error(j.message || 'Erro');

        alert('Filme cadastrado com sucesso! 🎬');
        fecharModalAdicionar();
        location.reload();
    } catch (err) {
        mostrarErroModal(err.message);
    } finally {
        btn.disabled = false;
        btn.textContent = orig;
    }
}

/* =========================================================
   INIT
   ========================================================= */
document.addEventListener('DOMContentLoaded', () => {
    initEstrelas();

    // Botões principais
    $('#btnEnviarAvaliacao')?.addEventListener('click', enviarAvaliacao);
    $('#btnAbrirModalAdicionar')?.addEventListener('click', abrirModalAdicionar);
    $('#btnFecharModalAdicionar')?.addEventListener('click', fecharModalAdicionar);
    $('#btnCancelarAdicionar')?.addEventListener('click', fecharModalAdicionar);
    $('#formAdicionarFilme')?.addEventListener('submit', e => {
        e.preventDefault();
        salvarFilme();
    });

    // Filtros (tanto os `.stat` quanto os `.filtro-btn`)
    $$('[data-filtro]').forEach(b => {
        b.addEventListener('click', () => filtrar(b.dataset.filtro));
    });

    // Delegação nos cards
    $('#todos-filmes-grid')?.addEventListener('click', e => {
        const card = e.target.closest('.filme-card');
        if (!card) return;

        if (e.target.matches('.btn-status')) {
            if (!exigirLogin()) return;
            const ciclo = ['quero_ver', 'assistido', 'favorito'];
            const atual = card.dataset.status || 'quero_ver';
            const idx = (ciclo.indexOf(atual) + 1) % ciclo.length;
            alterarStatus(Number(card.dataset.id), ciclo[idx], card);
        } else if (e.target.matches('.btn-detalhes')) {
            const c = card.dataset;
            alert(`🎬 ${c.titulo} (${c.ano})\n\n${c.descricao || 'Sem descrição.'}\n\nGênero: ${c.genero || '—'}`);
        }
    });

    // Fechar modal com ESC ou clique fora
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
        }
    });
    document.querySelectorAll('.modal').forEach(m => {
        m.addEventListener('click', e => {
            if (e.target === m) m.style.display = 'none';
        });
    });
});