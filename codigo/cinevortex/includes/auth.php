<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';

if (empty($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}