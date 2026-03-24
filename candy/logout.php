<?php
require_once 'config/conexao.php';

iniciarSessao();

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Destruir a sessão
session_destroy();

// Redirecionar para login
redirecionar('login.php');
?>