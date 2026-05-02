<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Ensure BASE_URL is defined
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/app.php';
}
require_once __DIR__ . '/../classes/User.php';
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['user_role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? User::e($pageTitle) . ' — ' : ''; ?>University Portal</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
<!-- Top Header Bar -->
<div class="site-header-top">
    <div class="header-inner">
        <span>📧 info@university.edu</span>
        <span>University Portal System &copy; <?php echo date('Y'); ?></span>
    </div>
</div>

<!-- Main Navigation -->
<header class="site-header-main">
    <div class="header-inner">
        <a href="<?php echo BASE_URL; ?>/index.php" class="site-logo">
            <span class="logo-icon">UP</span>
            <span>UniPortal</span>
        </a>

        <button class="nav-toggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav">
            <?php if ($isLoggedIn): ?>
                <?php if ($userRole === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>/views/admin_dashboard.php">📊 Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/views/admin/manage_departments.php">🏛️ Departments</a>
                    <a href="<?php echo BASE_URL; ?>/views/admin/manage_courses.php">📚 Courses</a>
                    <a href="<?php echo BASE_URL; ?>/views/admin/manage_news.php">📰 News</a>
                    <a href="<?php echo BASE_URL; ?>/views/admin/manage_users.php">👥 Users</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/views/student_dashboard.php">📊 Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/views/student/browse_courses.php">📚 Courses</a>
                    <a href="<?php echo BASE_URL; ?>/views/student/view_news.php">📰 News</a>
                    <a href="<?php echo BASE_URL; ?>/views/student_profile.php">👤 Profile</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/views/search.php">🔍 Search</a>
                <a href="<?php echo BASE_URL; ?>/logout.php" class="nav-logout">Logout (<?php echo User::e($userName); ?>)</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/index.php">Login</a>
                <a href="<?php echo BASE_URL; ?>/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="main-content">