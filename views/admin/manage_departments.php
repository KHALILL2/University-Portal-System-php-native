<?php
$pageTitle = 'Manage Departments';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/Department.php';
requireRole('admin');

$dept = new Department();
$error = '';
$success = '';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        if ($_POST['action'] === 'create') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if ($name === '') {
                $error = "Department name is required.";
            } elseif ($dept->create($name, $description)) {
                $success = "Department created successfully!";
            } else {
                $error = "Failed to create department. Name might already exist.";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0 && $dept->delete($id)) {
                $success = "Department deleted successfully.";
            } else {
                $error = "Failed to delete department.";
            }
        }
    }
}

$departments = $dept->getAll();
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">Manage Departments</h1>
        <p class="page-subtitle">Create, edit, and delete university departments.</p>
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
        <h3 class="card-title">➕ Add New Department</h3>
    </div>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="create">
        <div class="form-inline">
            <div class="form-group">
                <label for="dept-name">Name</label>
                <input type="text" id="dept-name" name="name" class="form-control" placeholder="e.g. Computer Science" required>
            </div>
            <div class="form-group" style="flex:2;">
                <label for="dept-desc">Description</label>
                <input type="text" id="dept-desc" name="description" class="form-control" placeholder="Brief description...">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>

<!-- Departments Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Departments (<?php echo count($departments); ?>)</h3>
    </div>
    <div class="table-wrapper" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($departments)): ?>
                    <tr><td colspan="5" class="table-empty">No departments yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($departments as $d): ?>
                    <tr>
                        <td><?php echo User::e((string)$d['id']); ?></td>
                        <td><strong><?php echo User::e($d['name']); ?></strong></td>
                        <td><?php echo User::e($d['description'] ?? '—'); ?></td>
                        <td><?php echo User::e($d['created_at']); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit_department.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
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
