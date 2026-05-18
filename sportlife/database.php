<?php
/**
 * Database configuration and connection class
 * Database: sportlife
 */

class Database {
    // Database configuration parameters
    private $host = 'localhost';     // Database server (default: localhost)
    private $db_name = 'sportlife1';  // Database name
    private $username = 'root';      // Database username (adjust as needed)
    private $password = '';          // Database password (adjust as needed)
    private $charset = 'utf8mb4';    // Charset for proper encoding
    private $conn;
    
    /**
     * Get database connection
     * @return PDO|null PDO connection object or null on failure
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            // DSN (Data Source Name) string
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            
            // PDO options for better security and performance
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays by default
                PDO::ATTR_EMULATE_PREPARES => false,                    // Use native prepared statements
                PDO::ATTR_STRINGIFY_FETCHES => false,                   // Don't convert numbers to strings
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}" // Set charset
            ];
            
            // Create PDO connection
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $e) {
            // Log error (in production, you might want to log to file instead)
            error_log("Connection Error: " . $e->getMessage());
            
            // Optional: Display user-friendly message (disable in production)
            die("Database connection failed. Please try again later.");
        }
        
        return $this->conn;
    }
    
    /**
     * Close database connection
     */
    public function closeConnection() {
        $this->conn = null;
    }
    
    /**
     * Test if connection is active
     * @return bool
     */
    public function isConnected() {
        return $this->conn !== null;
    }
}

// Optional: Global function for quick database access
function getDB() {
    static $db = null;
    if ($db === null) {
        $database = new Database();
        $db = $database->getConnection();
    }
    return $db;
}

// Example usage (commented out):
/*
try {
    $db = (new Database())->getConnection();
    echo "Connected successfully to sportlife database!";
    
    // Example query
    $stmt = $db->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    echo "MySQL Version: " . $result['version'];
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/
?>