<?php

include_once 'config.php';

// Inicia a sessão para usar mensagens flash (opcional)
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Verifica se os campos existem no POST
    if (isset($_POST['nome']) && isset($_POST['idade'])) {
        
        $nome = trim($_POST['nome']);
        $idade = $_POST['idade'];

        if (empty($nome) || empty($idade) || !is_numeric($idade)) {
            // Armazena mensagem de erro na sessão
            $_SESSION['mensagem'] = "Dados inválidos";
            $_SESSION['tipo'] = "erro";
            
            // Redireciona para evitar reenvio do formulário
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
            
        } else {
            $idade = (int)$idade;

            // Prepara a query
            $stmt = $conexao->prepare("INSERT INTO usuarios (nome, idade) VALUES (?, ?)");
            
            if ($stmt) {
                $stmt->bind_param("si", $nome, $idade);

                if ($stmt->execute()) {
                    $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
                    $_SESSION['tipo'] = "sucesso";
                } else {
                    $_SESSION['mensagem'] = "Erro ao cadastrar: " . $stmt->error;
                    $_SESSION['tipo'] = "erro";
                }

                $stmt->close();
            } else {
                $_SESSION['mensagem'] = "Erro na preparação da consulta: " . $conexao->error;
                $_SESSION['tipo'] = "erro";
            }
            
            // Fecha a conexão com o banco
            $conexao->close();
            
            // Redireciona para evitar reenvio do formulário
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        $_SESSION['mensagem'] = "Campos não preenchidos corretamente";
        $_SESSION['tipo'] = "erro";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fecha a conexão se ainda estiver aberta (para requisições GET)
if (isset($conexao)) {
    $conexao->close();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/form.css">
    <title>Formulário de Cadastro</title>
   
</head>


<body>

<?php
// Exibe mensagem da sessão se existir
if (isset($_SESSION['mensagem'])) {
    echo "<div class='mensagem " . $_SESSION['tipo'] . "'>" . $_SESSION['mensagem'] . "</div>";
    
    // Limpa a mensagem após exibir
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo']);
}
?>

<form action="" method="POST">
    <h2 style="text-align: center; color: #333;">Formulário de Cadastro</h2>
    
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>
    
    <label for="idade">Idade:</label>
    <input type="number" id="idade" name="idade" required>
    
    <button type="submit">Cadastrar</button>
</form>

</body>
</html>


