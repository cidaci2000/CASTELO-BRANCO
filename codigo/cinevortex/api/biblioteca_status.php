<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Método não permitido'], 405);
}

requireLogin();

$input   = $_POST ?: json_decode(file_get_contents('php://input'), true) ?? [];
$filmeId = (int)($input['filme_id'] ?? 0);
$status  = (string)($input['status'] ?? '');

$permitidos = ['quero_ver', 'assistido', 'favorito'];
if ($filmeId <= 0 || !in_array($status, $permitidos, true)) {
    jsonResponse(['success' => false, 'message' => 'Dados inválidos'], 422);
}

$pdo = getPDO();
$stmt = $pdo->prepare("
    INSERT INTO usuarios_filmes (usuario_id, filme_id, status)
    VALUES (:uid, :fid, :st)
    ON DUPLICATE KEY UPDATE status = VALUES(status)
");
$stmt->execute([
    ':uid' => $_SESSION['usuario_id'],
    ':fid' => $filmeId,
    ':st'  => $status,
]);

jsonResponse(['success' => true, 'message' => 'Biblioteca atualizada!']);