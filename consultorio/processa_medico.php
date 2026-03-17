<?php
require_once 'config.php';
session_start();

// Configurações de upload
$target_dir = "uploads/";
$max_file_size = 2 * 1024 * 1024; // 2MB
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

// Criar diretório de uploads se não existir
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    $crm = $_POST['crm'];
    $descricao = $_POST['descricao'];
    $foto_nome = '';
    
    // Processar upload da foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['foto']['tmp_name']);
        finfo_close($file_info);
        
        // Validar tipo de arquivo
        if (!in_array($mime_type, $allowed_types)) {
            $_SESSION['message'] = "Erro: Tipo de arquivo não permitido. Use JPG, PNG ou GIF.";
            $_SESSION['message_type'] = "error";
            header("Location: index.php");
            exit();
        }
        
        // Validar tamanho
        if ($_FILES['foto']['size'] > $max_file_size) {
            $_SESSION['message'] = "Erro: Arquivo muito grande. Tamanho máximo: 2MB";
            $_SESSION['message_type'] = "error";
            header("Location: index.php");
            exit();
        }
        
        // Gerar nome único para o arquivo
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nome = uniqid() . '_' . date('YmdHis') . '.' . $extensao;
        $target_file = $target_dir . $foto_nome;
        
        // Mover arquivo
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $_SESSION['message'] = "Erro ao fazer upload da foto.";
            $_SESSION['message_type'] = "error";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Erro: Foto é obrigatória.";
        $_SESSION['message_type'] = "error";
        header("Location: index.php");
        exit();
    }
    
    // Inserir no banco de dados
    $sql = "INSERT INTO medicos (nome, especialidade, crm, descricao, foto, status) VALUES (?, ?, ?, ?, ?, 'ativo')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $especialidade, $crm, $descricao, $foto_nome);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Médico cadastrado com sucesso!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Erro ao cadastrar médico: " . $stmt->error;
        $_SESSION['message_type'] = "error";
    }
    
    $stmt->close();
    header("Location: index.php");
    exit();
}
?>