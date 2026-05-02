<?php
// Entry point / Login
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/classes/User.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        redirect('views/admin_dashboard.php');
    } else {
        redirect('views/student_dashboard.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userCore = new User();
        $user = $userCore->login($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            if ($user['role'] === 'admin') {
                redirect('views/admin_dashboard.php');
            } else {
                redirect('views/student_dashboard.php');
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — University Portal</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="logo-block" style="text-align: center; margin-bottom: 30px;">
                <img src="<?php echo BASE_URL; ?>/assets/images/IT_logo.png" alt="BATU IT Department" style="height: 80px; margin-bottom: 15px;">
                <h1 style="font-size: 1.8rem; margin-bottom: 5px;">BATU | IT Portal</h1>
                <p style="color: #666;">Sign in to access courses & news</p>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger">⚠️ <?php echo User::e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php">
                <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@university.edu" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
            </form>

            <div class="auth-links">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </div>
</body>
</html>