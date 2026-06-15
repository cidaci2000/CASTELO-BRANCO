<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Teste do Banco de Dados</h2>";

require_once 'config.php';

// Testar conexão
if ($pdo) {
    echo "✅ Conexão OK<br>";
    
    // Listar tabelas
    $tables = $pdo->query("SHOW TABLES");
    echo "<h3>Tabelas no banco:</h3>";
    while($row = $tables->fetch()) {
        echo "- " . $row[0] . "<br>";
    }
    
    // Verificar usuarios
    $check = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $count = $check->fetch();
    echo "<h3>Total de usuários: " . $count['total'] . "</h3>";
    
    // Listar usuários
    $users = $pdo->query("SELECT id, nome_completo, email, tipo FROM usuarios");
    echo "<h3>Usuários cadastrados:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Tipo</th></tr>";
    while($user = $users->fetch()) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['nome_completo']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['tipo']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} else {
    echo "❌ Falha na conexão<br>";
}
?>