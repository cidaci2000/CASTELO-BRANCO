<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Método não permitido'], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$nome     = trim((string)($input['nome'] ?? ''));
$email    = trim((string)($input['email'] ?? ''));
$senha    = (string)($input['senha'] ?? '');
$confirma = (string)($input['confirmar_senha'] ?? '');

if ($nome === '' || $email === '' || $senha === '') {
    jsonResponse(['success' => false, 'message' => 'Preencha todos os campos'], 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['success' => false, 'message' => 'E-mail inválido'], 422);
}
if (strlen($senha) < 6) {
    jsonResponse(['success' => false, 'message' => 'A senha deve ter no mínimo 6 caracteres'], 422);
}
if ($senha !== $confirma) {
    jsonResponse(['success' => false, 'message' => 'As senhas não coincidem'], 422);
}

$pdo = getPDO();

$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :e LIMIT 1");
$stmt->execute([':e' => $email]);
if ($stmt->fetch()) {
    jsonResponse(['success' => false, 'message' => 'Este e-mail já está cadastrado'], 409);
}

$hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO usuarios (nome, email, senha, tipo)
    VALUES (:n, :e, :s, 'user')
");
$stmt->execute([':n' => $nome, ':e' => $email, ':s' => $hash]);

jsonResponse([
    'success' => true,
    'message' => 'Conta criada com sucesso!',
    'id'      => (int)$pdo->lastInsertId(),
]);