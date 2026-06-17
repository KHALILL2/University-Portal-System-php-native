<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class User
{
    protected PDO $db;
    protected string $table = 'users';

    // Hook up the database connection as soon as we create a User object
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Handle user registration - throws an error if something looks wrong
    public function register(string $name, string $email, string $password, string $role = 'student'): bool
    {
        $allowedRoles = ['admin', 'student'];
        if (!in_array($role, $allowedRoles, true)) {
            throw new Exception("Invalid role specified.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address format.");
        }

        if ($this->emailExists($email)) {
            throw new Exception("Email address is already in use.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->table} (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt->execute([
            ':name' => trim($name),
            ':email' => strtolower(trim($email)),
            ':password' => $hashedPassword,
            ':role' => $role,
        ])) {
            throw new Exception("A database error occurred during registration.");
        }

        return true;
    }

    // Logs a user in by checking their email and verifying the hash
    public function login(string $email, string $password): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address format.");
        }

        $sql = "SELECT id, name, email, password, role, created_at FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => strtolower(trim($email))]);

        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password.");
        }

        unset($user['password']);

        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'created_at' => $user['created_at'],
        ];
    }

    // Quick helper to grab user info by their ID
    public function findById(int $id): ?array
    {
        $sql = "SELECT id, name, email, role, created_at FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    // Updates the profile details. Password change is optional!
    public function updateProfile(int $id, string $name, string $email, string $password = ''): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address format.");
        }

        // Check if the new email belongs to another user
        $sql = "SELECT id FROM {$this->table} WHERE email = :email AND id != :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => strtolower(trim($email)), ':id' => $id]);
        if ($stmt->fetchColumn()) {
            throw new Exception("Email address is already in use by another account.");
        }

        if ($password !== '') {
            // Include password in update
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE {$this->table} SET name = :name, email = :email, password = :password WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':name' => trim($name),
                ':email' => strtolower(trim($email)),
                ':password' => $hashedPassword,
                ':id' => $id,
            ]);
        } else {
            // Update without altering password
            $sql = "UPDATE {$this->table} SET name = :name, email = :email WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':name' => trim($name),
                ':email' => strtolower(trim($email)),
                ':id' => $id,
            ]);
        }

        if (!$success) {
            throw new Exception("Failed to update profile due to database error.");
        }

        return true;
    }

    // Simple check to make sure we don't end up with duplicate emails
    protected function emailExists(string $email): bool
    {
        $sql = "SELECT id FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => strtolower(trim($email))]);

        return (bool) $stmt->fetchColumn();
    }

    // A tiny helper to quickly escape strings so we don't get XSS'd
    public static function e(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // Wipe the session and kill the cookie so the user is fully logged out
    public static function logout(): void
    {
        session_unset();
        session_destroy();
    }
}
