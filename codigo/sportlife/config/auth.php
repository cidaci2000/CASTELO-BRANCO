<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Bloqueia acesso se não estiver logado ou se o tipo não estiver na lista.
 * Ex.: exigir_login(['usuario']);  exigir_login(['admin','instrutor']);
 */
function exigir_login(array $tipos_permitidos = []) {
    if (empty($_SESSION['usuario_id'])) {
        header('Location: ../cadastro.php');
        exit;
    }
    if ($tipos_permitidos && !in_array($_SESSION['usuario_tipo'], $tipos_permitidos, true)) {
        header('Location: ../index.php');
        exit;
    }
}

/**
 * Retorna os dados do usuário logado (do jeito que o resto do sistema espera).
 */
function usuario_logado(): array {
    return [
        'id'         => (int)($_SESSION['usuario_id']   ?? 0),
        'nome'       => $_SESSION['usuario_nome']       ?? '',
        'tipo'       => $_SESSION['usuario_tipo']       ?? '',
        'modalidade' => $_SESSION['usuario_modalidade'] ?? 'N/A',
        'email'      => $_SESSION['usuario_email']      ?? '',
    ];
}

/** Redireciona e encerra. */
function redirect(string $url) {
    header("Location: $url");
    exit;
}