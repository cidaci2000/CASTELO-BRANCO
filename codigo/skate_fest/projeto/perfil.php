<?php
require_once 'config.php';
exigirLogin();
?>
<?php include 'header.php'; ?>

<main class="container">
    <div class="form-container">
        <h2>👤 MEU PERFIL</h2>
        <div class="perfil-avatar-placeholder">
            <?= isAdmin() ? '👑' : (isRepresentante() ? '👔' : '🛹') ?>
        </div>
        <form id="formPerfil">
            <div class="form-group">
                <label>NOME</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($_SESSION['usuario_nome']) ?>" required>
            </div>
            <div class="form-group">
                <label>EMAIL</label>
                <input type="email" value="<?= htmlspecialchars($_SESSION['usuario_email']) ?>" disabled>
            </div>
            <div class="form-group">
                <label>TELEFONE</label>
                <input type="tel" name="telefone" value="<?= htmlspecialchars($_SESSION['usuario_telefone'] ?? '') ?>">
            </div>
            <hr style="border-color:rgba(111,149,255,0.2);margin:25px 0;">
            <h3 style="color:white;margin-bottom:20px;">🔒 ALTERAR SENHA</h3>
            <div class="form-group">
                <label>SENHA ATUAL</label>
                <input type="password" name="senha_atual">
            </div>
            <div class="form-group">
                <label>NOVA SENHA</label>
                <input type="password" name="nova_senha">
            </div>
            <button type="submit" class="btn" style="width:100%;">ATUALIZAR PERFIL</button>
        </form>
    </div>
</main>

<script>
document.getElementById('formPerfil').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    fd.append('action', 'atualizar_perfil');
    const r = await fetch('ajax.php', { method:'POST', body:fd });
    const d = await r.json();
    if (d.success) {
        mostrarToast('✅ ' + d.message, 'success');
        setTimeout(() => location.reload(), 1500);
    } else {
        mostrarToast('❌ ' + d.errors.join(', '), 'error');
    }
});
</script>

<?php include 'footer.php'; ?>