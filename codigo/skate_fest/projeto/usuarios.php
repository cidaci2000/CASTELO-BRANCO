<?php
require_once 'config.php';
exigirAdmin();
?>
<?php include 'header.php'; ?>

<main class="container">
    <section class="hero">
        <h1>👥 GERENCIAR USUÁRIOS</h1>
    </section>
    
    <div class="tabela-container">
        <table>
            <thead>
                <tr><th>ID</th><th>Nome</th><th>Email</th><th>Tipo</th><th>Status</th><th>Cadastro</th><th>Ações</th></tr>
            </thead>
            <tbody id="tabelaUsuariosBody">
                <tr><td colspan="7" style="text-align:center;">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</main>

<script>
async function carregarUsuarios() {
    const fd = new FormData();
    fd.append('action', 'listar_usuarios');
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    const tb = document.getElementById('tabelaUsuariosBody');
    
    if (d.success && d.usuarios.length > 0) {
        tb.innerHTML = d.usuarios.map(u => `
            <tr>
                <td>#${u.id}</td>
                <td><strong>${escapeHtml(u.nome)}</strong></td>
                <td>${escapeHtml(u.email)}</td>
                <td>
                    <span class="tipo-badge ${u.tipo}">${u.tipo_label}</span>
                    ${u.tipo !== 'admin' ? `
                        <select onchange="mudarTipo(${u.id}, this.value)" style="margin-left:5px;">
                            <option value="competidor" ${u.tipo === 'competidor' ? 'selected' : ''}>Competidor</option>
                            <option value="representante" ${u.tipo === 'representante' ? 'selected' : ''}>Representante</option>
                        </select>
                    ` : ''}
                </td>
                <td><span class="status-badge ${u.status}">${u.status}</span></td>
                <td>${new Date(u.data_cadastro).toLocaleDateString('pt-BR')}</td>
                <td>
                    ${u.tipo !== 'admin' ? `
                        <select onchange="mudarStatus(${u.id}, this.value)">
                            <option value="ativo" ${u.status === 'ativo' ? 'selected' : ''}>Ativo</option>
                            <option value="pendente" ${u.status === 'pendente' ? 'selected' : ''}>Pendente</option>
                            <option value="inativo" ${u.status === 'inativo' ? 'selected' : ''}>Inativo</option>
                        </select>
                        <button onclick="excluir(${u.id})" class="btn btn-danger" style="padding:4px 10px;">🗑️</button>
                    ` : '👑'}
                </td>
            </tr>
        `).join('');
    }
}

async function mudarStatus(id, status) {
    const fd = new FormData();
    fd.append('action', 'atualizar_status_usuario');
    fd.append('id', id); fd.append('status', status);
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    if (d.success) { mostrarToast('✅ Atualizado!', 'success'); carregarUsuarios(); }
}

async function mudarTipo(id, tipo) {
    const fd = new FormData();
    fd.append('action', 'atualizar_tipo_usuario');
    fd.append('id', id); fd.append('tipo', tipo);
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    if (d.success) { mostrarToast('✅ Tipo atualizado!', 'success'); carregarUsuarios(); }
}

async function excluir(id) {
    if (!confirm('⚠️ Excluir usuário?')) return;
    const fd = new FormData();
    fd.append('action', 'excluir_usuario');
    fd.append('id', id);
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    if (d.success) { mostrarToast('✅ Excluído!', 'success'); carregarUsuarios(); }
}

document.addEventListener('DOMContentLoaded', carregarUsuarios);
</script>

<?php include 'footer.php'; ?>