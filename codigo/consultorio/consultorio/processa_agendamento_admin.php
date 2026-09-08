<?php
session_start();
require_once 'config.php';

// Verificar se é admin
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$acao = $_GET['acao'] ?? '';
$id = $_GET['id'] ?? 0;

if ($acao == 'confirmar') {
    $stmt = $conn->prepare("UPDATE agendamentos SET status = 'confirmado' WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = 'Agendamento confirmado com sucesso!';
        $tipo = 'success';
    } else {
        $mensagem = 'Erro ao confirmar agendamento: ' . $conn->error;
        $tipo = 'erro';
    }
    $stmt->close();
    
} elseif ($acao == 'cancelar') {
    $stmt = $conn->prepare("UPDATE agendamentos SET status = 'cancelado' WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = 'Agendamento cancelado com sucesso!';
        $tipo = 'success';
    } else {
        $mensagem = 'Erro ao cancelar agendamento: ' . $conn->error;
        $tipo = 'erro';
    }
    $stmt->close();
}

header("Location: admin.php?acao=agendamentos&mensagem=$mensagem&tipo=$tipo");
exit;
?>