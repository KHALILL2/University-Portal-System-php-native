<?php
$pageTitle = 'Manage Courses';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/Course.php';
require_once __DIR__ . '/../../classes/Department.php';
requireRole('admin');

$courseModel = new Course();
$deptModel = new Department();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        if ($_POST['action'] === 'create') {
            $name = trim($_POST['name'] ?? '');
            $code = trim($_POST['code'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $deptId = (int)($_POST['department_id'] ?? 0);
            if ($name === '' || $code === '' || $deptId === 0) {
                $error = "Name, code, and department are required.";
            } elseif ($courseModel->create($name, $code, $description, $deptId)) {
                $success = "Course created successfully!";
            } else {
                $error = "Failed to create course. Code might already exist.";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0 && $courseModel->delete($id)) {
                $success = "Course deleted successfully.";
            } else {
                $error = "Failed to delete course.";
            }
        }
    }
}

$courses = $courseModel->getAll();
$departments = $deptModel->getAll();
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">Manage Courses</h1>
        <p class="page-subtitle">Create, edit, and delete courses.</p>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger">⚠️ <?php echo User::e($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success">✅ <?php echo User::e($success); ?></div>
<?php endif; ?>

<!-- Create Form -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">➕ Add New Course</h3>
    </div>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="create">
        <div class="form-inline" style="flex-wrap:wrap;">
            <div class="form-group">
                <label for="c-name">Name</label>
                <input type="text" id="c-name" name="name" class="form-control" placeholder="e.g. Web Development II" required>
            </div>
            <div class="form-group" style="flex:0.5; min-width:120px;">
                <label for="c-code">Code</label>
                <input type="text" id="c-code" name="code" class="form-control" placeholder="CS402" required>
            </div>
            <div class="form-group">
                <label for="c-dept">Department</label>
                <select id="c-dept" name="department_id" class="form-control" required>
                    <option value="">Select...</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?php echo $d['id']; ?>"><?php echo User::e($d['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group mt-2">
            <label for="c-desc">Description</label>
            <input type="text" id="c-desc" name="description" class="form-control" placeholder="Brief description (optional)">
        </div>
        <button type="submit" class="btn btn-primary">Create Course</button>
    </form>
</div>

<!-- Courses Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Courses (<?php echo count($courses); ?>)</h3>
    </div>
    <div class="table-wrapper" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($courses)): ?>
                    <tr><td colspan="5" class="table-empty">No courses yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><span class="badge badge-student"><?php echo User::e($c['code']); ?></span></td>
                        <td><strong><?php echo User::e($c['name']); ?></strong></td>
                        <td><?php echo User::e($c['department_name']); ?></td>
                        <td><?php echo User::e($c['created_at']); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit_course.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
