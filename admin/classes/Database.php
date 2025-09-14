<?php  
/**
 * Database connection and query handler using PDO.
 */
class Database {
    protected $pdo;
    private $host = 'localhost';
    private $db   = 'newspaper_system';  // Make sure this is your actual DB name
    private $user = 'root';               // Your DB username
    private $pass = '';                   // Your DB password (empty string if none)
    private $charset = 'utf8mb4';

    /**
     * Constructor establishes the PDO connection.
     */
    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return associative arrays
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepares
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            // Uncomment this line for testing connection (remove in production)
            // echo "Connected to database '{$this->db}' successfully.";
        } catch (PDOException $e) {
            // Better error reporting - you can log this or display a user-friendly message
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Executes a prepared statement and returns all results.
     * @param string $sql The SQL query.
     * @param array $params Parameters to bind.
     * @return array The fetched data.
     */
    public function executeQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Executes a prepared statement and returns a single row.
     * @param string $sql The SQL query.
     * @param array $params Parameters to bind.
     * @return array|null The single fetched row or null if not found.
     */
    public function executeQuerySingle($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Executes an INSERT, UPDATE, or DELETE statement.
     * @param string $sql The SQL query.
     * @param array $params Parameters to bind.
     * @return bool True on success, false on failure.
     */
    public function executeNonQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Returns the ID of the last inserted row.
     * @return string The last inserted ID.
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
?>
