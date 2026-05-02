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

    /**
     * Retrieve all departments ordered by name.
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM departments ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Retrieve a single department by its ID.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM departments WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Create a new department.
     */
    public function create(string $name, string $description): bool
    {
        $stmt = $this->db->prepare("INSERT INTO departments (name, description) VALUES (:name, :description)");
        return $stmt->execute([
            ':name' => trim($name),
            ':description' => trim($description),
        ]);
    }

    /**
     * Update an existing department.
     */
    public function update(int $id, string $name, string $description): bool
    {
        $stmt = $this->db->prepare("UPDATE departments SET name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            ':name' => trim($name),
            ':description' => trim($description),
            ':id' => $id,
        ]);
    }

    /**
     * Delete a department by ID (cascades to courses).
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM departments WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Count courses belonging to a department.
     */
    public function getCourseCount(int $departmentId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(id) FROM courses WHERE department_id = :did");
        $stmt->execute([':did' => $departmentId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Search departments by keyword.
     */
    public function search(string $keyword): array
    {
        $term = '%' . $keyword . '%';
        $stmt = $this->db->prepare("SELECT * FROM departments WHERE name LIKE :q OR description LIKE :q2 ORDER BY name ASC");
        $stmt->execute([':q' => $term, ':q2' => $term]);
        return $stmt->fetchAll();
    }
}
