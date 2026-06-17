<?php
$pageTitle = 'Departments';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/Student.php';
requireRole('student');

$student = new Student();
$departments = $student->viewDepartments();

require_once __DIR__ . '/../../includes/header.php';
?>

<h1 class="page-title">Departments</h1>
<p class="page-subtitle">Browse all university departments and what they offer.</p>

<?php if (empty($departments)): ?>
<div class="card text-center">
    <p class="text-muted">No departments have been added yet.</p>
</div>
<?php else: ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">🏛️ All Departments (<?php echo count($departments); ?>)</h3>
    </div>
    <div class="table-wrapper" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $d): ?>
                <tr>
                    <td><strong><?php echo User::e($d['name']); ?></strong></td>
                    <td><?php echo User::e($d['description'] ?? '—'); ?></td>
                    <td><?php echo User::e($d['created_at']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>