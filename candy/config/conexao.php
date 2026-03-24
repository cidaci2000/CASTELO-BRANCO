<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'candy_loves');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            // Primeiro, conectar sem banco de dados para criar se necessário
            $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Criar banco de dados se não existir
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Conectar ao banco de dados
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                )
            );
            
            // Criar tabelas se não existirem
            $this->criarTabelas();
            
        } catch(PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }
    
    private function criarTabelas() {
        // Criar tabela de usuários
        $sql_usuarios = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            tipo ENUM('cliente', 'funcionario', 'admin') DEFAULT 'cliente',
            status ENUM('ativo', 'inativo') DEFAULT 'ativo',
            data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ultimo_acesso TIMESTAMP NULL,
            INDEX idx_email (email),
            INDEX idx_tipo (tipo)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->conn->exec($sql_usuarios);
        
        // Criar tabela de logs
        $sql_logs = "
        CREATE TABLE IF NOT EXISTS logs_acesso (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT,
            data_acesso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ip_acesso VARCHAR(45),
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
            INDEX idx_usuario (usuario_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->conn->exec($sql_logs);
        
        // Inserir usuários padrão se não existirem
        $this->inserirUsuariosPadrao();
    }
    
    private function inserirUsuariosPadrao() {
        // Verificar se já existem usuários
        $stmt = $this->conn->query("SELECT COUNT(*) FROM usuarios");
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            $usuarios_padrao = [
                ['Administrador', 'admin@candyloves.com', md5('admin123'), 'admin'],
                ['Funcionário Demo', 'funcionario@candyloves.com', md5('func123'), 'funcionario'],
                ['Cliente Demo', 'cliente@candyloves.com', md5('cliente123'), 'cliente']
            ];
            
            $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            
            foreach ($usuarios_padrao as $usuario) {
                $stmt->execute($usuario);
            }
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Funções auxiliares
function iniciarSessao() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function estaLogado() {
    iniciarSessao();
    return isset($_SESSION['usuario_id']);
}

function temPermissao($tiposPermitidos = []) {
    if (!estaLogado()) return false;
    if (empty($tiposPermitidos)) return true;
    return in_array($_SESSION['usuario_tipo'], $tiposPermitidos);
}

function redirecionar($url) {
    header("Location: $url");
    exit();
}

// Inicializar banco de dados
Database::getInstance();
?>