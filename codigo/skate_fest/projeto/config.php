<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================
// CONEXÃO COM BANCO
// ============================================
$host = 'localhost';
$dbname = 'skate_fest_competition';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// ============================================
// FUNÇÕES DE AUTENTICAÇÃO
// ============================================
function isLogado() { return isset($_SESSION['usuario_id']); }
function getTipoUsuario() { return $_SESSION['usuario_tipo'] ?? null; }
function isAdmin() { return getTipoUsuario() === 'admin'; }
function isRepresentante() { return getTipoUsuario() === 'representante'; }
function isCompetidor() { return getTipoUsuario() === 'competidor'; }
function hasPermission($tipo) {
    if (isAdmin()) return true;
    return getTipoUsuario() === $tipo;
}

// ============================================
// REDIRECIONAR PARA DASHBOARD CORRETO
// ============================================
function redirecionarDashboard() {
    if (isAdmin()) { header("Location: dashboard_admin.php"); exit; }
    if (isRepresentante()) { header("Location: dashboard_rep.php"); exit; }
    if (isCompetidor()) { header("Location: dashboard_comp.php"); exit; }
    header("Location: index.php"); exit;
}

// ============================================
// VERIFICAÇÃO DE ACESSO POR PÁGINA
// ============================================
function exigirLogin() {
    if (!isLogado()) {
        header("Location: index.php");
        exit;
    }
}

function exigirAdmin() {
    exigirLogin();
    if (!isAdmin()) {
        header("Location: index.php?erro=acesso");
        exit;
    }
}

function exigirRepresentante() {
    exigirLogin();
    if (!isRepresentante() && !isAdmin()) {
        header("Location: index.php?erro=acesso");
        exit;
    }
}

function exigirCompetidor() {
    exigirLogin();
    if (!isCompetidor() && !isAdmin()) {
        header("Location: index.php?erro=acesso");
        exit;
    }
}