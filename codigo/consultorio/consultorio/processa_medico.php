<?php
session_start();
require_once 'config.php';

// Verificar se é admin
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if ($acao == 'cadastrar' || $acao == 'editar') {
    $id = $_POST['id'] ?? null;
    $nome = trim($_POST['nome'] ?? '');
    $especialidade = trim($_POST['especialidade'] ?? '');
    $crm = trim($_POST['crm'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $status = $_POST['status'] ?? 'ativo';
    
    if (empty($nome) || empty($especialidade) || empty($crm)) {
        header('Location: admin.php?acao=medicos&mensagem=Preencha todos os campos obrigatórios&tipo=erro');
        exit;
    }
    
    // Processar upload da foto
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = uniqid() . '_' . date('YmdHis') . '.' . $ext;
        $destino = $upload_dir . $foto;
        
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            header('Location: admin.php?acao=medicos&mensagem=Erro ao fazer upload da foto&tipo=erro');
            exit;
        }
    }
    
    if ($acao == 'cadastrar') {
        // Inserir novo médico
        $sql = "INSERT INTO medicos (nome, especialidade, crm, descricao, foto, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $nome, $especialidade, $crm, $descricao, $foto, $status);
        
        if ($stmt->execute()) {
            $mensagem = 'Médico cadastrado com sucesso!';
            $tipo = 'success';
        } else {
            $mensagem = 'Erro ao cadastrar médico: ' . $conn->error;
            $tipo = 'erro';
        }
        $stmt->close();
    } else {
        // Editar médico
        if ($foto) {
            // Buscar foto antiga para deletar
            $stmt = $conn->prepare("SELECT foto FROM medicos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $old = $result->fetch_assoc();
            if ($old && $old['foto'] && file_exists('uploads/' . $old['foto'])) {
                unlink('uploads/' . $old['foto']);
            }
            $stmt->close();
            
            $sql = "UPDATE medicos SET nome = ?, especialidade = ?, crm = ?, descricao = ?, foto = ?, status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $nome, $especialidade, $crm, $descricao, $foto, $status, $id);
        } else {
            $sql = "UPDATE medicos SET nome = ?, especialidade = ?, crm = ?, descricao = ?, status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $nome, $especialidade, $crm, $descricao, $status, $id);
        }
        
        if ($stmt->execute()) {
            $mensagem = 'Médico atualizado com sucesso!';
            $tipo = 'success';
        } else {
            $mensagem = 'Erro ao atualizar médico: ' . $conn->error;
            $tipo = 'erro';
        }
        $stmt->close();
    }
    
} elseif ($acao == 'excluir') {
    $id = $_GET['id'] ?? 0;
    
    // Buscar foto para deletar
    $stmt = $conn->prepare("SELECT foto FROM medicos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $medico = $result->fetch_assoc();
    if ($medico && $medico['foto'] && file_exists('uploads/' . $medico['foto'])) {
        unlink('uploads/' . $medico['foto']);
    }
    $stmt->close();
    
    $stmt = $conn->prepare("DELETE FROM medicos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = 'Médico excluído com sucesso!';
        $tipo = 'success';
    } else {
        $mensagem = 'Erro ao excluir médico: ' . $conn->error;
        $tipo = 'erro';
    }
    $stmt->close();
}

header("Location: admin.php?acao=medicos&mensagem=$mensagem&tipo=$tipo");
exit;
?>