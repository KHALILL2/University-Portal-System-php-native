<?php
$pageTitle = 'Edit Course';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/Course.php';
require_once __DIR__ . '/../../classes/Department.php';
requireRole('admin');

$courseModel = new Course();
$deptModel = new Department();
$id = (int)($_GET['id'] ?? 0);
$course = $courseModel->getById($id);

if (!$course) {
    redirect('views/admin/manage_courses.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $deptId = (int)($_POST['department_id'] ?? 0);
        if ($name === '' || $code === '' || $deptId === 0) {
            $error = "Name, code, and department are required.";
        } elseif ($courseModel->update($id, $name, $code, $description, $deptId)) {
            $success = "Course updated successfully!";
            $course = $courseModel->getById($id);
        } else {
            $error = "Update failed.";
        }
    }
}

$departments = $deptModel->getAll();
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">Edit Course</h1>
        <p class="page-subtitle">Update course information.</p>
    </div>
    <a href="manage_courses.php" class="btn btn-secondary">← Back to Courses</a>
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
            <label for="name">Course Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo User::e($course['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="code">Course Code</label>
            <input type="text" id="code" name="code" class="form-control" value="<?php echo User::e($course['code']); ?>" required>
        </div>

        <div class="form-group">
            <label for="department_id">Department</label>
            <select id="department_id" name="department_id" class="form-control" required>
                <?php foreach ($departments as $d): ?>
                    <option value="<?php echo $d['id']; ?>" <?php echo ($d['id'] == $course['department_id']) ? 'selected' : ''; ?>><?php echo User::e($d['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control"><?php echo User::e($course['description'] ?? ''); ?></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage_courses.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
