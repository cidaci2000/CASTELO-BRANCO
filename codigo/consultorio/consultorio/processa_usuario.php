<?php
session_start();
require_once 'config.php';

// Verificar se é admin
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$acao = $_GET['acao'] ?? '';

if ($acao == 'excluir') {
    $id = $_GET['id'] ?? 0;
    
    // Não permitir excluir o próprio admin
    if ($id == $_SESSION['usuario_id']) {
        header('Location: admin.php?acao=usuarios&mensagem=Não é possível excluir seu próprio usuário&tipo=erro');
        exit;
    }
    
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = 'Usuário excluído com sucesso!';
        $tipo = 'success';
    } else {
        $mensagem = 'Erro ao excluir usuário: ' . $conn->error;
        $tipo = 'erro';
    }
    $stmt->close();
}

header("Location: admin.php?acao=usuarios&mensagem=$mensagem&tipo=$tipo");
exit;
?>