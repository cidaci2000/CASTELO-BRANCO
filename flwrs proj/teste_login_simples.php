<?php
echo "<h1>Teste de Funcionamento</h1>";
echo "<p>Se você está vendo isso, o PHP está funcionando!</p>";
echo "<p>Data atual: " . date('d/m/Y H:i:s') . "</p>";

// Tentar incluir o config
if (file_exists('config.php')) {
    echo "<p style='color:green'>✅ config.php encontrado</p>";
    include 'config.php';
    echo "<p style='color:green'>✅ config.php incluído</p>";
} else {
    echo "<p style='color:red'>❌ config.php não encontrado</p>";
}

// Tentar conectar ao banco
if (isset($pdo)) {
    echo "<p style='color:green'>✅ Conexão com banco OK</p>";
    
    $sql = "SELECT COUNT(*) as total FROM usuarios";
    $stmt = $pdo->query($sql);
    $total = $stmt->fetch();
    echo "<p style='color:green'>✅ Total de usuários: " . $total['total'] . "</p>";
} else {
    echo "<p style='color:red'>❌ Falha na conexão</p>";
}

echo "<br><a href='login.php' style='background:#c0859d; color:white; padding:10px 20px; text-decoration:none; border-radius:8px;'>Ir para Login</a>";
?>