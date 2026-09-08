<?php
// excluir_endereco.php
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
    $era_principal = $endereco['principal'];
    
    // Excluir
    $stmt = $pdo->prepare("DELETE FROM enderecos WHERE id = ?");
    $stmt->execute([$endereco_id]);
    
    // Se era principal, definir outro como principal
    if ($era_principal) {
        $stmt = $pdo->prepare("SELECT id FROM enderecos WHERE usuario_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$_SESSION['usuario_id']]);
        $novo_principal = $stmt->fetch();
        
        if ($novo_principal) {
            $stmt = $pdo->prepare("UPDATE enderecos SET principal = 1 WHERE id = ?");
            $stmt->execute([$novo_principal['id']]);
        }
    }
}

header('Location: cadastro_endereco.php');
exit;
?>