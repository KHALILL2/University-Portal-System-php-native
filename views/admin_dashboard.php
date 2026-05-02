<?php
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../classes/Admin.php';
requireRole('admin');

$admin = new Admin();
$stats = $admin->getDashboardStats();

require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="page-title">Admin Dashboard</h1>
<p class="page-subtitle">Welcome back, <?php echo User::e($_SESSION['user_name']); ?>! Here's your portal overview.</p>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon users">👥</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $stats['total_users']; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon departments">🏛️</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $stats['total_departments']; ?></div>
            <div class="stat-label">Departments</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon courses">📚</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $stats['total_courses']; ?></div>
            <div class="stat-label">Courses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon enrollments">📝</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $stats['total_enrollments']; ?></div>
            <div class="stat-label">Enrollments</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-links">
    <a href="<?php echo BASE_URL; ?>/views/admin/manage_departments.php" class="quick-link">
        <div class="ql-icon">🏛️</div>
        <div class="ql-label">Manage Departments</div>
    </a>
    <a href="<?php echo BASE_URL; ?>/views/admin/manage_courses.php" class="quick-link">
        <div class="ql-icon">📚</div>
        <div class="ql-label">Manage Courses</div>
    </a>
    <a href="<?php echo BASE_URL; ?>/views/admin/manage_news.php" class="quick-link">
        <div class="ql-icon">📰</div>
        <div class="ql-label">Manage News</div>
    </a>
    <a href="<?php echo BASE_URL; ?>/views/admin/manage_users.php" class="quick-link">
        <div class="ql-icon">👥</div>
        <div class="ql-label">Manage Users</div>
    </a>
</div>

<!-- Recent Enrollments -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Enrollments</h3>
    </div>
    <div class="table-wrapper" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Enrolled At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($stats['recent_enrollments'])): ?>
                    <tr><td colspan="4" class="table-empty">No recent enrollments.</td></tr>
                <?php else: ?>
                    <?php foreach ($stats['recent_enrollments'] as $e): ?>
                    <tr>
                        <td><?php echo User::e((string)$e['id']); ?></td>
                        <td><?php echo User::e($e['student_name']); ?></td>
                        <td><?php echo User::e($e['course_name']); ?></td>
                        <td><?php echo User::e($e['enrolled_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>