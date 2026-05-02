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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<!-- Main Navigation -->
<header class="site-header-main">
    <div class="header-inner">
        <a href="<?php echo BASE_URL; ?>/index.php" class="site-logo">
            <img src="<?php echo BASE_URL; ?>/assets/images/BATU_logo.png" alt="BATU Logo" style="height: 35px; margin-right: 10px;">
            <span>BATU Portal</span>
        </a>

        <a href="https://khalill2.github.io/BATU-Timetable/" target="_blank" class="nav-link" style="margin-left:20px; font-weight:bold; color:var(--primary-color);"><i class="fas fa-calendar-alt"></i> BATU Timetable</a>

        <button class="nav-toggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav">
            <?php if ($isLoggedIn): ?>
                <?php if ($userRole === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>/views/admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/views/admin/manage_departments.php"><i class="fas fa-university"></i> Departments</a>
                    <a href="<?php echo BASE_URL; ?>/views/admin/manage_courses.php"><i class="fas fa-book"></i> Courses</a>
                    <a href="<?php echo BASE_URL; ?>/views/admin/manage_news.php"><i class="fas fa-newspaper"></i> News</a>
                    <a href="<?php echo BASE_URL; ?>/views/admin/manage_users.php"><i class="fas fa-users"></i> Users</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/views/student_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/views/student/browse_courses.php"><i class="fas fa-book"></i> Courses</a>
                    <a href="<?php echo BASE_URL; ?>/views/student/view_news.php"><i class="fas fa-newspaper"></i> News</a>
                    <a href="<?php echo BASE_URL; ?>/views/student_profile.php"><i class="fas fa-user"></i> Profile</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/views/search.php"><i class="fas fa-search"></i> Search</a>
                <a href="<?php echo BASE_URL; ?>/logout.php" class="nav-logout"><i class="fas fa-sign-out-alt"></i> Logout (<?php echo User::e($userName); ?>)</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/index.php">Login</a>
                <a href="<?php echo BASE_URL; ?>/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="main-content">