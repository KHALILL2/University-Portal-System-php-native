<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../classes/Student.php';
requireRole('student');

$student = new Student();
$userId = (int)$_SESSION['user_id'];
$currentUser = $student->findById($userId);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? ''; // Optional

        if (strlen($name) < 2) {
            $error = "Name must be at least 2 characters.";
        } elseif ($password !== '' && strlen($password) < 6) {
            $error = "New password must be at least 6 characters long.";
        } else {
            try {
                $student->updateProfile($userId, $name, $email, $password);
                $success = "Profile updated successfully!";
                $_SESSION['user_name'] = $name;
                $currentUser['name'] = $name;
                $currentUser['email'] = $email;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">View and update your account information.</p>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger">⚠️ <?php echo User::e($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success">✅ <?php echo User::e($success); ?></div>
<?php endif; ?>

<div class="card" style="max-width:500px;">
    <form method="POST" action="student_profile.php">
        <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo User::e($currentUser['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo User::e($currentUser['email']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            <span class="form-hint">Min 6 characters if changing</span>
        </div>

        <div class="form-group">
            <label>Role</label>
            <input type="text" class="form-control" value="<?php echo User::e(ucfirst($currentUser['role'])); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Member Since</label>
            <input type="text" class="form-control" value="<?php echo User::e($currentUser['created_at']); ?>" disabled>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>