<?php
// teste_login.php - Para diagnosticar o erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Teste de Diagnóstico - Login</h2>";

// Teste 1: Verificar se o arquivo está sendo executado
echo "<p>✅ O arquivo está sendo executado!</p>";

// Teste 2: Verificar versão do PHP
echo "<p>PHP Version: " . phpversion() . "</p>";

// Teste 3: Tentar incluir o auth.php
if (file_exists('auth.php')) {
    echo "<p>✅ auth.php encontrado</p>";
    include 'auth.php';
    echo "<p>✅ auth.php incluído com sucesso</p>";
} else {
    echo "<p>❌ auth.php NÃO encontrado! Caminho: " . getcwd() . "/auth.php</p>";
}

// Teste 4: Tentar incluir o config.php
if (file_exists('config.php')) {
    echo "<p>✅ config.php encontrado</p>";
    include 'config.php';
    echo "<p>✅ config.php incluído com sucesso</p>";
} else {
    echo "<p>❌ config.php NÃO encontrado!</p>";
}

// Teste 5: Verificar se a tabela usuarios existe
if (isset($pdo)) {
    try {
        $result = $pdo->query("SHOW TABLES LIKE 'usuarios'");
        if ($result->rowCount() > 0) {
            echo "<p>✅ Tabela 'usuarios' existe</p>";
        } else {
            echo "<p>❌ Tabela 'usuarios' NÃO existe!</p>";
        }
    } catch(PDOException $e) {
        echo "<p>❌ Erro ao verificar tabela: " . $e->getMessage() . "</p>";
    }
}

echo "<br><a href='login.php'>Ir para página de login</a>";
?>