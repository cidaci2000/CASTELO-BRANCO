<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['usuario'])) {
    echo json_encode([
        'logged_in' => true,
        'usuario' => [
            'id' => $_SESSION['usuario']['id'],
            'nome' => $_SESSION['usuario']['nome'],
            'tipo' => $_SESSION['usuario']['tipo'],
            'email' => $_SESSION['usuario']['email']
        ]
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>