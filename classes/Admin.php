<?php
declare(strict_types=1);

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Department.php';
require_once __DIR__ . '/Course.php';
require_once __DIR__ . '/News.php';
require_once __DIR__ . '/Enrollment.php';

/**
 * Admin extends User — demonstrates Inheritance.
 * Provides dashboard statistics and delegates CRUD to entity classes.
 */
class Admin extends User
{
    /**
     * Gather all dashboard statistics.
     */
    public function getDashboardStats(): array
    {
        $stats = [
            'total_users'        => 0,
            'total_departments'  => 0,
            'total_courses'      => 0,
            'total_enrollments'  => 0,
            'recent_enrollments' => [],
        ];

        // Total users
        $stmt = $this->db->query("SELECT COUNT(id) FROM users");
        $stats['total_users'] = (int) $stmt->fetchColumn();

        // Total departments
        $stmt = $this->db->query("SELECT COUNT(id) FROM departments");
        $stats['total_departments'] = (int) $stmt->fetchColumn();

        // Total courses
        $stmt = $this->db->query("SELECT COUNT(id) FROM courses");
        $stats['total_courses'] = (int) $stmt->fetchColumn();

        // Total enrollments
        $stmt = $this->db->query("SELECT COUNT(id) FROM enrollments");
        $stats['total_enrollments'] = (int) $stmt->fetchColumn();

        // Recent enrollments (last 5)
        $enrollment = new Enrollment();
        $stats['recent_enrollments'] = $enrollment->getRecent(5);

        return $stats;
    }

    /**
     * Get all users for user management.
     */
    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Delete a user by ID (cannot delete self).
     */
    public function deleteUser(int $id, int $currentAdminId): bool
    {
        if ($id === $currentAdminId) {
            return false; // Prevent self-deletion
        }
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
