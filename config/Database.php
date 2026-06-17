<?php
declare(strict_types=1);

// Using the Singleton pattern here so we don't open 100 database connections at once
class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    // The constructor is private so no one can just type "new Database()"
    private function __construct()
    {
        // Pull in the database credentials (make sure env.php isn't pushed to GitHub!)
        require_once __DIR__ . '/env.php';
         
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

        $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Always return associative arrays
            PDO::ATTR_EMULATE_PREPARES => false, // Let MySQL handle the prepared statements (more secure)
        ]);
    }

    // This is how we actually get the single database instance
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    // Grabs the actual PDO connection object
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            throw new RuntimeException('Database connection is not initialized.');
        }

        return $this->connection;
    }

    // Prevent cloning and unserializing to keep the Singleton pattern strict
    private function __clone(): void
    {
    }

    public function __wakeup(): void
    {
        throw new RuntimeException('Cannot unserialize singleton.');
    }
}