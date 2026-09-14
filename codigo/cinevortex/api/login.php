<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Método não permitido'], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$email = trim((string)($input['email'] ?? ''));
$senha = (string)($input['senha'] ?? '');

if ($email === '' || $senha === '') {
    jsonResponse(['success' => false, 'message' => 'Preencha e-mail e senha'], 422);
}

$pdo = getPDO();
$stmt = $pdo->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = :e LIMIT 1");
$stmt->execute([':e' => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($senha, $user['senha'])) {
    jsonResponse(['success' => false, 'message' => 'E-mail ou senha incorretos'], 401);
}

session_regenerate_id(true);
$_SESSION['usuario_id']   = (int)$user['id'];
$_SESSION['usuario_nome'] = $user['nome'];
$_SESSION['usuario_tipo'] = $user['tipo'];

jsonResponse([
    'success'  => true,
    'message'  => 'Login realizado! Redirecionando...',
    'redirect' => $user['tipo'] === 'admin' ? 'admin/index.php' : 'home.php',
]);