<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class User
{
    protected PDO $db;
    protected string $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register(string $name, string $email, string $password, string $role = 'student'): bool
    {
        $allowedRoles = ['admin', 'student'];
        if (!in_array($role, $allowedRoles, true)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($this->emailExists($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->table} (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name' => trim($name),
            ':email' => strtolower(trim($email)),
            ':password' => $hashedPassword,
            ':role' => $role,
        ]);
    }

    public function login(string $email, string $password): ?array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $sql = "SELECT id, name, email, password, role, created_at FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => strtolower(trim($email))]);

        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return null;
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

    public function findById(int $id): ?array
    {
        $sql = "SELECT id, name, email, role, created_at FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function updateProfile(int $id, string $name, string $email, string $password = ''): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Check if the new email belongs to another user
        $sql = "SELECT id FROM {$this->table} WHERE email = :email AND id != :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => strtolower(trim($email)), ':id' => $id]);
        if ($stmt->fetchColumn()) {
            return false;
        }

        if ($password !== '') {
            // Include password in update
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE {$this->table} SET name = :name, email = :email, password = :password WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => trim($name),
                ':email' => strtolower(trim($email)),
                ':password' => $hashedPassword,
                ':id' => $id,
            ]);
        } else {
            // Update without altering password
            $sql = "UPDATE {$this->table} SET name = :name, email = :email WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => trim($name),
                ':email' => strtolower(trim($email)),
                ':id' => $id,
            ]);
        }
    }

    protected function emailExists(string $email): bool
    {
        $sql = "SELECT id FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => strtolower(trim($email))]);

        return (bool) $stmt->fetchColumn();
    }

    public static function e(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
