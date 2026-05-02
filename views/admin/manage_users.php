<?php
$pageTitle = 'Manage Users';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/Admin.php';
requireRole('admin');

$admin = new Admin();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && $admin->deleteUser($id, (int)$_SESSION['user_id'])) {
            $success = "User deleted successfully.";
        } else {
            $error = "Failed to delete user. You cannot delete your own account.";
        }
    }
}

$users = $admin->getAllUsers();
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">Manage Users</h1>
        <p class="page-subtitle">View and manage all registered users.</p>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger">⚠️ <?php echo User::e($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success">✅ <?php echo User::e($success); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Users (<?php echo count($users); ?>)</h3>
    </div>
    <div class="table-wrapper" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo User::e((string)$u['id']); ?></td>
                    <td><strong><?php echo User::e($u['name']); ?></strong></td>
                    <td><?php echo User::e($u['email']); ?></td>
                    <td>
                        <span class="badge <?php echo $u['role'] === 'admin' ? 'badge-admin' : 'badge-student'; ?>">
                            <?php echo User::e($u['role']); ?>
                        </span>
                    </td>
                    <td><?php echo User::e($u['created_at']); ?></td>
                    <td>
                        <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger btn-delete">Delete</button>
                        </form>
                        <?php else: ?>
                            <span class="text-muted">You</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
