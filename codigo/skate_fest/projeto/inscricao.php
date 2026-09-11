<?php
require_once 'config.php';
exigirLogin();

$eventos_lista = $pdo->query("SELECT DISTINCT nome_evento FROM eventos ORDER BY data_evento DESC")->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM skatistas_eventos WHERE usuario_id = ? ORDER BY data_inscricao DESC LIMIT 1");
$stmt->execute([$_SESSION['usuario_id']]);
$inscricao_atual = $stmt->fetch();
$ja_inscrito = ($inscricao_atual !== false);
?>
<?php include 'header.php'; ?>

<main class="container">
    <div class="form-container">
        <h2>📝 INSCRIÇÃO EM EVENTO</h2>
        
        <div class="info-box">
            <h4>👤 Seus Dados</h4>
            <p><span class="label">Nome:</span> <?= htmlspecialchars($_SESSION['usuario_nome']) ?></p>
            <p><span class="label">Email:</span> <?= htmlspecialchars($_SESSION['usuario_email']) ?></p>
        </div>
        
        <form id="formInscricao">
            <div class="form-group">
                <label>EVENTO</label>
                <select name="nome_evento" required>
                    <option value="">Selecione...</option>
                    <?php foreach($eventos_lista as $e): ?>
                        <option value="<?= htmlspecialchars($e['nome_evento']) ?>"><?= htmlspecialchars($e['nome_evento']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>CATEGORIA</label>
                <select name="categoria" required>
                    <option value="">Selecione...</option>
                    <option value="Iniciante">Iniciante</option>
                    <option value="Amador">Amador</option>
                    <option value="Profissional">Profissional</option>
                    <option value="Master">Master (35+)</option>
                    <option value="Mirim">Mirim (até 12)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success" style="width:100%;">REALIZAR INSCRIÇÃO</button>
        </form>
    </div>
</main>

<script>
document.getElementById('formInscricao').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    fd.append('action', 'cadastrar_skatista_evento');
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