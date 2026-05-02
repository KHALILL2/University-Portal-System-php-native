<?php
declare(strict_types=1);

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private string $host = '127.0.0.1';
    private string $dbName = 'university_portal';
    private string $username = 'root';
    private string $password = '';
    private string $charset = 'utf8mb4';

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

        $this->connection = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            throw new RuntimeException('Database connection is not initialized.');
        }

        return $this->connection;
    }

    private function __clone(): void
    {
    }

    public function __wakeup(): void
    {
        throw new RuntimeException('Cannot unserialize singleton.');
    }
}
