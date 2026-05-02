<?php
$pageTitle = 'Edit Department';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/Department.php';
requireRole('admin');

$dept = new Department();
$id = (int)($_GET['id'] ?? 0);
$department = $dept->getById($id);

if (!$department) {
    redirect('views/admin/manage_departments.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($name === '') {
            $error = "Department name is required.";
        } elseif ($dept->update($id, $name, $description)) {
            $success = "Department updated successfully!";
            $department = $dept->getById($id); // Refresh data
        } else {
            $error = "Update failed.";
        }
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">Edit Department</h1>
        <p class="page-subtitle">Update department information.</p>
    </div>
    <a href="manage_departments.php" class="btn btn-secondary">← Back to Departments</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger">⚠️ <?php echo User::e($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success">✅ <?php echo User::e($success); ?></div>
<?php endif; ?>

<div class="card" style="max-width:600px;">
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
        
        <div class="form-group">
            <label for="name">Department Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo User::e($department['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control"><?php echo User::e($department['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage_departments.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
