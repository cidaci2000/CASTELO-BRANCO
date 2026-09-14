<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Método não permitido'], 405);
}

requireLogin();

$input = $_POST ?: json_decode(file_get_contents('php://input'), true) ?? [];

$filmeId    = (int)($input['filme_id'] ?? 0);
$nota       = (int)($input['nota'] ?? 0);
$comentario = trim((string)($input['comentario'] ?? ''));

if ($filmeId <= 0) {
    jsonResponse(['success' => false, 'message' => 'Filme inválido'], 422);
}
if ($nota < 1 || $nota > 5) {
    jsonResponse(['success' => false, 'message' => 'Nota deve ser entre 1 e 5'], 422);
}

$pdo = getPDO();

$stmt = $pdo->prepare("
    INSERT INTO avaliacoes (usuario_id, filme_id, nota, comentario)
    VALUES (:uid, :fid, :nota, :com)
    ON DUPLICATE KEY UPDATE
        nota = VALUES(nota),
        comentario = VALUES(comentario),
        data_avaliacao = CURRENT_TIMESTAMP
");
$stmt->execute([
    ':uid'  => $_SESSION['usuario_id'],
    ':fid'  => $filmeId,
    ':nota' => $nota,
    ':com'  => $comentario !== '' ? $comentario : null,
]);

$stmt = $pdo->prepare("
    INSERT INTO usuarios_filmes (usuario_id, filme_id, status, nota)
    VALUES (:uid, :fid, 'assistido', :nota)
    ON DUPLICATE KEY UPDATE
        nota = VALUES(nota),
        status = IF(status = 'quero_ver', 'assistido', status)
");
$stmt->execute([
    ':uid'  => $_SESSION['usuario_id'],
    ':fid'  => $filmeId,
    ':nota' => $nota,
]);

jsonResponse(['success' => true, 'message' => 'Avaliação salva com sucesso!']);