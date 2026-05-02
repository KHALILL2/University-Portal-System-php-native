<?php
$pageTitle = 'Search';
require_once __DIR__ . '/../includes/auth_middleware.php';
requireLogin();
require_once __DIR__ . '/../classes/Course.php';
require_once __DIR__ . '/../classes/Department.php';

$query = trim($_GET['q'] ?? '');
$courseResults = [];
$deptResults = [];

if ($query !== '') {
    $courseModel = new Course();
    $deptModel = new Department();
    $courseResults = $courseModel->search($query);
    $deptResults = $deptModel->search($query);
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="page-title">Search</h1>
<p class="page-subtitle">Find courses and departments by keyword.</p>

<div class="card mb-3">
    <form method="GET" action="search.php">
        <div class="search-box">
            <input type="text" name="q" class="form-control" value="<?php echo User::e($query); ?>" placeholder="Search by name, code, or keyword..." required>
            <button type="submit" class="btn btn-primary">🔍 Search</button>
        </div>
    </form>
</div>

<?php if ($query !== ''): ?>
    <h2 class="mb-2">Results for "<?php echo User::e($query); ?>"</h2>

    <!-- Courses -->
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">📚 Courses (<?php echo count($courseResults); ?>)</h3>
        </div>
        <div class="table-wrapper" style="border:none;">
            <table>
                <thead>
                    <tr><th>Code</th><th>Name</th><th>Department</th><th>Description</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($courseResults)): ?>
                        <tr><td colspan="4" class="table-empty">No matching courses.</td></tr>
                    <?php else: ?>
                        <?php foreach ($courseResults as $c): ?>
                        <tr>
                            <td><span class="badge badge-student"><?php echo User::e($c['code']); ?></span></td>
                            <td><strong><?php echo User::e($c['name']); ?></strong></td>
                            <td><?php echo User::e($c['department_name']); ?></td>
                            <td><?php echo User::e($c['description'] ?? '—'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Departments -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">🏛️ Departments (<?php echo count($deptResults); ?>)</h3>
        </div>
        <div class="table-wrapper" style="border:none;">
            <table>
                <thead>
                    <tr><th>Name</th><th>Description</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($deptResults)): ?>
                        <tr><td colspan="2" class="table-empty">No matching departments.</td></tr>
                    <?php else: ?>
                        <?php foreach ($deptResults as $d): ?>
                        <tr>
                            <td><strong><?php echo User::e($d['name']); ?></strong></td>
                            <td><?php echo User::e($d['description'] ?? '—'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>