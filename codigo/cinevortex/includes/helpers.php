<?php
declare(strict_types=1);

if (!function_exists('e')) {
    function e(?string $v): string {
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('jsonResponse')) {
    function jsonResponse(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if (!function_exists('requireLogin')) {
    function requireLogin(): void {
        if (empty($_SESSION['usuario_id'])) {
            jsonResponse(['success' => false, 'message' => 'Você precisa estar logado'], 401);
        }
    }
}