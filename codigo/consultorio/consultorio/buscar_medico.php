<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Verificar se é admin
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true || $_SESSION['usuario_tipo'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acesso negado']);
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM medicos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $medico = $result->fetch_assoc();
    echo json_encode(['success' => true, 'medico' => $medico]);
} else {
    echo json_encode(['success' => false, 'message' => 'Médico não encontrado']);
}

$stmt->close();
?>