<?php
// Database configuration
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'lab_inventory_system');

// Application configuration
if (!defined('APP_NAME')) define('APP_NAME', 'Lab Equipment Inventory System');
if (!defined('APP_URL')) define('APP_URL', 'http://192.168.1.10/lab_management_');
if (!defined('APP_ROOT')) define('APP_ROOT', dirname(dirname(__FILE__)));
if (!defined('APP_VERSION')) define('APP_VERSION', '1.0.0');
if (!defined('APP_EMAIL')) define('APP_EMAIL', 'noreply@labmanagement.com');

// Session configuration
if (!defined('SESSION_NAME')) define('SESSION_NAME', 'lab_inventory');
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 86400); // 24 hours
if (!defined('SESSION_PATH')) define('SESSION_PATH', '/');
if (!defined('SESSION_SECURE')) define('SESSION_SECURE', false);
if (!defined('SESSION_HTTP_ONLY')) define('SESSION_HTTP_ONLY', true);

// Error reporting - Change to 0 in production
if (!defined('ERROR_REPORTING')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Timezone
if (!defined('TIMEZONE')) {
    date_default_timezone_set('Asia/Manila'); // For Philippines
}

// Hash cost for password encryption
if (!defined('PASSWORD_COST')) define('PASSWORD_COST', 12);

// Email configuration
if (!defined('SMTP_HOST')) define('SMTP_HOST', 'smtp.gmail.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_USER')) define('SMTP_USER', 'rvnmatter24@gmail.com');
if (!defined('SMTP_PASS')) define('SMTP_PASS', 'gdkhlpfwalbbqevi');
if (!defined('SMTP_FROM')) define('SMTP_FROM', 'rvnmatter24@gmail.com');
if (!defined('SMTP_FROM_NAME')) define('SMTP_FROM_NAME', 'Lab Management System');

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $conn;
    private $error;
    private static $instance = null;
    
    // Private constructor to implement Singleton pattern
    private function __construct() {
        // Set DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        );
        
        // Create PDO instance
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Database Connection Error: " . $this->error);
            // For development environment only - remove in production
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                echo "Connection Error: " . $this->error;
            } else {
                echo "A database error occurred. Please try again later.";
            }
        }
    }
    
    // Get singleton instance
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    // Get connection
    public function getConnection() {
        return $this->conn;
    }
    
    // Execute prepared statement
    public function execute($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute($params);
            
            // For debugging
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("SQL Error: " . $errorInfo[2] . " in query: " . $query);
            }
            
            return $stmt;
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Query Execution Error: " . $this->error . " in query: " . $query);
            // For development environment only - remove in production
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                echo "Query Error: " . $this->error;
            } else {
                echo "A database error occurred. Please try again later.";
            }
            return false;
        }
    }
    
    // Return a single record
    public function single($query, $params = []) {
        $stmt = $this->execute($query, $params);
        return $stmt ? $stmt->fetch() : false;
    }
    
    // Return multiple records
    public function resultSet($query, $params = []) {
        $stmt = $this->execute($query, $params);
        return $stmt ? $stmt->fetchAll() : false;
    }
    
    // Row count
    public function rowCount($query, $params = []) {
        $stmt = $this->execute($query, $params);
        return $stmt ? $stmt->rowCount() : false;
    }
    
    // Get last inserted ID
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
    
    // Begin transaction
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }
    
    // Commit transaction
    public function commit() {
        return $this->conn->commit();
    }
    
    // Rollback transaction
    public function rollback() {
        return $this->conn->rollBack();
    }
} 