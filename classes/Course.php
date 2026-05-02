<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class Course
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve all courses with their department name.
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT c.*, d.name AS department_name
            FROM courses c
            JOIN departments d ON c.department_id = d.id
            ORDER BY c.name ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Retrieve courses filtered by department.
     */
    public function getByDepartment(int $departmentId): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, d.name AS department_name
            FROM courses c
            JOIN departments d ON c.department_id = d.id
            WHERE c.department_id = :did
            ORDER BY c.name ASC
        ");
        $stmt->execute([':did' => $departmentId]);
        return $stmt->fetchAll();
    }

    /**
     * Retrieve a single course by ID.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, d.name AS department_name
            FROM courses c
            JOIN departments d ON c.department_id = d.id
            WHERE c.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Create a new course.
     */
    public function create(string $name, string $code, string $description, int $departmentId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO courses (name, code, description, department_id) VALUES (:name, :code, :desc, :did)");
        return $stmt->execute([
            ':name' => trim($name),
            ':code' => strtoupper(trim($code)),
            ':desc' => trim($description),
            ':did'  => $departmentId,
        ]);
    }

    /**
     * Update an existing course.
     */
    public function update(int $id, string $name, string $code, string $description, int $departmentId): bool
    {
        $stmt = $this->db->prepare("UPDATE courses SET name = :name, code = :code, description = :desc, department_id = :did WHERE id = :id");
        return $stmt->execute([
            ':name' => trim($name),
            ':code' => strtoupper(trim($code)),
            ':desc' => trim($description),
            ':did'  => $departmentId,
            ':id'   => $id,
        ]);
    }

    /**
     * Delete a course by ID.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Search courses by name or code.
     */
    public function search(string $keyword): array
    {
        $term = '%' . $keyword . '%';
        $stmt = $this->db->prepare("
            SELECT c.*, d.name AS department_name
            FROM courses c
            JOIN departments d ON c.department_id = d.id
            WHERE c.name LIKE :q1 OR c.code LIKE :q2
            ORDER BY c.name ASC
        ");
        $stmt->execute([':q1' => $term, ':q2' => $term]);
        return $stmt->fetchAll();
    }
}
