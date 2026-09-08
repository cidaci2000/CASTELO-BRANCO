<?php
require_once 'usuario_functions.php';

$usuarioClass = new Usuario($pdo);
$erro = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if(empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos';
    } else {
        $resultado = $usuarioClass->login($email, $senha);
        
        if(!$resultado['erro']) {
            $_SESSION['usuario_id'] = $resultado['usuario']['id'];
            $_SESSION['usuario_nome'] = $resultado['usuario']['nome'];
            $_SESSION['usuario_tipo'] = $resultado['usuario']['tipo'];
            redirect('dashboard.php');
        } else {
            $erro = $resultado['mensagem'];
        }
    }
}
?>