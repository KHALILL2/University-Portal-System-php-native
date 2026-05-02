<?php
$pageTitle = 'Student Dashboard';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../classes/Student.php';
requireRole('student');

$student = new Student();
$userId = (int)$_SESSION['user_id'];
$enrollments = $student->getEnrollments($userId);

require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="page-title">Student Dashboard</h1>
<p class="page-subtitle">Welcome back, <?php echo User::e($_SESSION['user_name']); ?>!</p>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon courses">📚</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo count($enrollments); ?></div>
            <div class="stat-label">Enrolled Courses</div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="quick-links">
    <a href="<?php echo BASE_URL; ?>/views/student/browse_courses.php" class="quick-link">
        <div class="ql-icon">📚</div>
        <div class="ql-label">Browse Courses</div>
    </a>
    <a href="<?php echo BASE_URL; ?>/views/student/view_news.php" class="quick-link">
        <div class="ql-icon">📰</div>
        <div class="ql-label">View News</div>
    </a>
    <a href="<?php echo BASE_URL; ?>/views/student_profile.php" class="quick-link">
        <div class="ql-icon">👤</div>
        <div class="ql-label">My Profile</div>
    </a>
    <a href="<?php echo BASE_URL; ?>/views/search.php" class="quick-link">
        <div class="ql-icon">🔍</div>
        <div class="ql-label">Search</div>
    </a>
</div>

<!-- Enrollments Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Enrolled Courses</h3>
        <a href="<?php echo BASE_URL; ?>/views/student/browse_courses.php" class="btn btn-sm btn-primary">Browse More</a>
    </div>
    <div class="table-wrapper" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Course Name</th>
                    <th>Department</th>
                    <th>Enrolled At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($enrollments)): ?>
                    <tr><td colspan="4" class="table-empty">You haven't enrolled in any courses yet. <a href="<?php echo BASE_URL; ?>/views/student/browse_courses.php">Browse courses</a></td></tr>
                <?php else: ?>
                    <?php foreach ($enrollments as $e): ?>
                    <tr>
                        <td><span class="badge badge-student"><?php echo User::e($e['code']); ?></span></td>
                        <td><strong><?php echo User::e($e['name']); ?></strong></td>
                        <td><?php echo User::e($e['department_name']); ?></td>
                        <td><?php echo User::e($e['enrolled_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>