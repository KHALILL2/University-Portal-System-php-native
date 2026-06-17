<?php
declare(strict_types=1);

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Department.php';
require_once __DIR__ . '/Course.php';
require_once __DIR__ . '/News.php';
require_once __DIR__ . '/Enrollment.php';

// Admin inherits from User since they share login/profile features
class Admin extends User
{
    // Grab all the numbers we need for the dashboard widgets
    public function getDashboardStats(): array
    {
        $stats = [
            'total_users'        => 0,
            'total_departments'  => 0,
            'total_courses'      => 0,
            'total_enrollments'  => 0,
            'recent_enrollments' => [],
        ];

        // Count total users
        $stmt = $this->db->query("SELECT COUNT(id) FROM users");
        $stats['total_users'] = (int) $stmt->fetchColumn();

        // Count departments
        $stmt = $this->db->query("SELECT COUNT(id) FROM departments");
        $stats['total_departments'] = (int) $stmt->fetchColumn();

        // Count courses
        $stmt = $this->db->query("SELECT COUNT(id) FROM courses");
        $stats['total_courses'] = (int) $stmt->fetchColumn();

        // Count enrollments
        $stmt = $this->db->query("SELECT COUNT(id) FROM enrollments");
        $stats['total_enrollments'] = (int) $stmt->fetchColumn();

        // Get the latest 5 enrollments to show in the table
        $enrollment = new Enrollment();
        $stats['recent_enrollments'] = $enrollment->getRecent(5);

        return $stats;
    }

    // Used in the manage users page to list everyone out
    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Deletes a user, but we need to make sure the admin doesn't accidentally delete themselves
    public function deleteUser(int $id, int $currentAdminId): bool
    {
        if ($id === $currentAdminId) {
            return false; // Stop self-deletion
        }
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
