<?php
// definir_principal.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}

$endereco_id = $_POST['endereco_id'] ?? 0;

// Verificar se o endereço pertence ao usuário
$stmt = $pdo->prepare("SELECT * FROM enderecos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$endereco_id, $_SESSION['usuario_id']]);
$endereco = $stmt->fetch();

if ($endereco) {
    // Remover principal de todos os endereços do usuário
    $stmt = $pdo->prepare("UPDATE enderecos SET principal = 0 WHERE usuario_id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    
    // Definir este como principal
    $stmt = $pdo->prepare("UPDATE enderecos SET principal = 1 WHERE id = ?");
    $stmt->execute([$endereco_id]);
}

header('Location: cadastro_endereco.php');
exit;
?>