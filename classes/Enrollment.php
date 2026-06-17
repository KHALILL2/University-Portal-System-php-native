<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class Enrollment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Try to enroll the student. If the DB throws a unique constraint error, it means they're already in it
    public function enroll(int $studentId, int $courseId): bool
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (:sid, :cid)");
            return $stmt->execute([':sid' => $studentId, ':cid' => $courseId]);
        } catch (PDOException $e) {
            // Unique constraint violation = already enrolled
            return false;
        }
    }

    // Remove a student from a course
    public function unenroll(int $studentId, int $courseId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM enrollments WHERE student_id = :sid AND course_id = :cid");
        return $stmt->execute([':sid' => $studentId, ':cid' => $courseId]);
    }

    // Get all the classes a student is taking. We join the courses and departments tables to get the actual names.
    public function getByStudent(int $studentId): array
    {
        $stmt = $this->db->prepare("
            SELECT c.id AS course_id, c.name, c.code, d.name AS department_name, e.enrolled_at
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            JOIN departments d ON c.department_id = d.id
            WHERE e.student_id = :sid
            ORDER BY e.enrolled_at DESC
        ");
        $stmt->execute([':sid' => $studentId]);
        return $stmt->fetchAll();
    }

    // Simple boolean check to see if we should show the "Enroll" or "Unenroll" button
    public function isEnrolled(int $studentId, int $courseId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE student_id = :sid AND course_id = :cid LIMIT 1");
        $stmt->execute([':sid' => $studentId, ':cid' => $courseId]);
        return (bool) $stmt->fetchColumn();
    }

    // Count for the admin dashboard stats
    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) FROM enrollments");
        return (int) $stmt->fetchColumn();
    }

    // Grabs the latest few enrollments to show in the admin dashboard activity feed
    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT e.id, u.name AS student_name, c.name AS course_name, e.enrolled_at
            FROM enrollments e
            JOIN users u ON e.student_id = u.id
            JOIN courses c ON e.course_id = c.id
            ORDER BY e.enrolled_at DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
