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

$titulo = trim((string)($input['titulo'] ?? ''));
$ano    = (int)($input['ano'] ?? 0);

if ($titulo === '') {
    jsonResponse(['success' => false, 'message' => 'Título é obrigatório'], 422);
}
if ($ano < 1888 || $ano > 2100) {
    jsonResponse(['success' => false, 'message' => 'Ano deve estar entre 1888 e 2100'], 422);
}

// Verifica se já existe filme com mesmo título e ano
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id FROM filmes WHERE titulo = :t AND ano = :a LIMIT 1");
$stmt->execute([':t' => $titulo, ':a' => $ano]);
if ($stmt->fetch()) {
    jsonResponse(['success' => false, 'message' => 'Este filme já está cadastrado'], 409);
}

$stmt = $pdo->prepare("
    INSERT INTO filmes (titulo, ano, descricao, genero, url_imagem, id_usuario_criador)
    VALUES (:t, :a, :d, :g, :u, :uid)
");
$stmt->execute([
    ':t'   => $titulo,
    ':a'   => $ano,
    ':d'   => trim((string)($input['descricao']  ?? '')) ?: null,
    ':g'   => trim((string)($input['genero']     ?? '')) ?: null,
    ':u'   => trim((string)($input['url_imagem'] ?? '')) ?: null,
    ':uid' => $_SESSION['usuario_id'],
]);

jsonResponse([
    'success' => true,
    'message' => 'Filme cadastrado com sucesso!',
    'id'      => (int)$pdo->lastInsertId(),
]);