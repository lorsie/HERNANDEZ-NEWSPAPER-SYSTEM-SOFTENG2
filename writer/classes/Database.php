<?php  
/**
 * Superclass for handling all database connections and queries.
 * Uses PDO for secure database interactions.
 */
class Database {
    protected $pdo;
    private $host = 'localhost';
    private $db = 'newspaper_system';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    /**
     * Constructor establishes the PDO connection.
     */
    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Executes a query and returns all matching rows.
     * @param string $sql The SQL query.
     * @param array $params Parameters to bind.
     * @return array The result set.
     */
    public function executeQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Executes a query and returns a single row.
     * @param string $sql The SQL query.
     * @param array $params Parameters to bind.
     * @return array|null The result row or null.
     */
    public function executeQuerySingle($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Executes an insert, update, or delete statement.
     * @param string $sql The SQL statement.
     * @param array $params Parameters to bind.
     * @return bool Success status.
     */
    public function executeNonQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Returns the ID of the last inserted row.
     * @return string The last insert ID.
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
?>
