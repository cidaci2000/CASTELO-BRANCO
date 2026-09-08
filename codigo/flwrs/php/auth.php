<?php
// auth.php - Funções de autenticação (CORRIGIDO)
// ATENÇÃO: Este arquivo deve ser incluído APÓS session_start()

// Verificar se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está logado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/**
 * Verifica se o usuário logado é administrador
 * @return bool
 */
function isAdmin() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin';
}

/**
 * Verifica se o usuário logado é cliente comum
 * @return bool
 */
function isUsuario() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'usuario';
}

/**
 * Verifica se o usuário está logado, senão redireciona para o login
 * @param string $redirectTo Página para redirecionar (padrão: login.php)
 */
function requireLogin($redirectTo = 'login.php') {
    if (!isLoggedIn()) {
        // Salvar a URL que o usuário tentou acessar para redirecionar após o login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Verifica se o usuário é administrador, senão redireciona
 * @param string $redirectTo Página para redirecionar se não for admin (padrão: home.php)
 */
function requireAdmin($redirectTo = 'home.php') {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Verifica se o usuário é cliente comum, senão redireciona
 * @param string $redirectTo Página para redirecionar se não for usuario (padrão: admin/dashboard.php)
 */
function requireUsuario($redirectTo = 'admin/dashboard.php') {
    requireLogin();
    if (!isUsuario()) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Retorna os dados do usuário logado atualmente
 * @return array|null
 */
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['usuario_id'] ?? null,
            'nome' => $_SESSION['usuario_nome'] ?? null,
            'email' => $_SESSION['usuario_email'] ?? null,
            'tipo' => $_SESSION['usuario_tipo'] ?? null
        ];
    }
    return null;
}

/**
 * Faz logout do usuário
 * @param bool $redirect Se deve redirecionar após logout
 * @param string $redirectTo Página para redirecionar (padrão: login.php)
 */
function logout($redirect = true, $redirectTo = 'login.php') {
    // Limpar todas as variáveis de sessão
    $_SESSION = array();
    
    // Destruir o cookie de sessão se existir
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destruir a sessão
    session_destroy();
    
    // Redirecionar se solicitado
    if ($redirect) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Redireciona baseado no tipo de usuário após login
 */
function redirectAfterLogin() {
    // Verificar se tem URL específica para redirecionar
    if (isset($_SESSION['redirect_after_login'])) {
        $redirect = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirect);
        exit;
    }
    
    // Redirecionar baseado no tipo
    if (isAdmin()) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: home.php');
    }
    exit;
}

/**
 * Verifica se o usuário tem permissão para acessar um recurso específico
 * @param string $permission Nome da permissão
 * @return bool
 */
function hasPermission($permission) {
    // Se não estiver logado, não tem permissão
    if (!isLoggedIn()) {
        return false;
    }
    
    // Admin tem todas as permissões
    if (isAdmin()) {
        return true;
    }
    
    // Definir permissões específicas para usuários comuns
    $userPermissions = [
        'view_products',
        'add_to_cart',
        'checkout',
        'view_orders',
        'edit_profile'
    ];
    
    return in_array($permission, $userPermissions);
}

/**
 * Exige uma permissão específica
 * @param string $permission Nome da permissão
 * @param string $redirectTo Página para redirecionar
 */
function requirePermission($permission, $redirectTo = 'home.php') {
    requireLogin();
    if (!hasPermission($permission)) {
        header('Location: ' . $redirectTo);
        exit;
    }
}
?>