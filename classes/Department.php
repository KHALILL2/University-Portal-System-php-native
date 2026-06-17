<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class Department
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Just grabs everything in alphabetical order
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM departments ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    // Finds a single dept by ID. Returns null if it doesn't exist anymore
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM departments WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // Add a new department to the system
    public function create(string $name, string $description): bool
    {
        $stmt = $this->db->prepare("INSERT INTO departments (name, description) VALUES (:name, :description)");
        return $stmt->execute([
            ':name' => trim($name),
            ':description' => trim($description),
        ]);
    }

    // Save edits to a department
    public function update(int $id, string $name, string $description): bool
    {
        $stmt = $this->db->prepare("UPDATE departments SET name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            ':name' => trim($name),
            ':description' => trim($description),
            ':id' => $id,
        ]);
    }

    // Nuke a department. (Be careful, this will delete all courses attached to it if ON DELETE CASCADE is on!)
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM departments WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Helper to see how many courses a department currently has
    public function getCourseCount(int $departmentId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(id) FROM courses WHERE department_id = :did");
        $stmt->execute([':did' => $departmentId]);
        return (int) $stmt->fetchColumn();
    }

    // Search bar logic for departments
    public function search(string $keyword): array
    {
        $term = '%' . $keyword . '%';
        $stmt = $this->db->prepare("SELECT * FROM departments WHERE name LIKE :q OR description LIKE :q2 ORDER BY name ASC");
        $stmt->execute([':q' => $term, ':q2' => $term]);
        return $stmt->fetchAll();
    }
}
