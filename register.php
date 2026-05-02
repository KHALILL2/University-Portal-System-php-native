<?php
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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Simple validation
        if (strlen($name) < 2 || strlen($password) < 6) {
            $error = "Name must be at least 2 characters and password at least 6 characters.";
        } else {
            $user = new User();
            // Students only — admin accounts are created by seed or by admins
            if ($user->register($name, $email, $password, 'student')) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Email might already exist or is invalid.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — University Portal</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="logo-block">
                <div class="logo-icon">UP</div>
                <h1>Create Account</h1>
                <p>Register as a student to access the portal</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">⚠️ <?php echo User::e($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?php echo User::e($success); ?></div>
                <div class="text-center mt-2">
                    <a href="index.php" class="btn btn-primary">Go to Login</a>
                </div>
            <?php else: ?>
                <form method="POST" action="register.php">
                    <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="you@university.edu" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                        <span class="form-hint">Must be at least 6 characters long</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
                </form>

                <div class="auth-links">
                    Already have an account? <a href="index.php">Sign in here</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>