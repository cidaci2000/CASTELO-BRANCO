<?php
require_once 'conexao.php';

function verificarAcesso($tiposPermitidos = []) {
    if (!estaLogado()) {
        redirecionar('login.php');
    }
    
    if (!empty($tiposPermitidos) && !temPermissao($tiposPermitidos)) {
        // Redirecionar para dashboard apropriado
        if ($_SESSION['usuario_tipo'] === 'admin') {
            redirecionar('admin-dashboard.php');
        } elseif ($_SESSION['usuario_tipo'] === 'funcionario') {
            redirecionar('funcionario-dashboard.php');
        } else {
            redirecionar('cliente-dashboard.php');
        }
    }
}
?>