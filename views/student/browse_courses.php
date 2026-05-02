<?php
$pageTitle = 'Browse Courses';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/Course.php';
require_once __DIR__ . '/../../classes/Department.php';
require_once __DIR__ . '/../../classes/Enrollment.php';
requireRole('student');

$courseModel = new Course();
$deptModel = new Department();
$enrollmentModel = new Enrollment();
$userId = (int)$_SESSION['user_id'];

$error = '';
$success = '';

// Handle enroll / unenroll
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $courseId = (int)($_POST['course_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        if ($action === 'enroll' && $courseId > 0) {
            if ($enrollmentModel->enroll($userId, $courseId)) {
                $success = "Successfully enrolled!";
            } else {
                $error = "Already enrolled in this course.";
            }
        } elseif ($action === 'unenroll' && $courseId > 0) {
            if ($enrollmentModel->unenroll($userId, $courseId)) {
                $success = "Successfully unenrolled.";
            } else {
                $error = "Failed to unenroll.";
            }
        }
    }
}

// Filter by department
$filterDept = (int)($_GET['department'] ?? 0);
$courses = ($filterDept > 0) ? $courseModel->getByDepartment($filterDept) : $courseModel->getAll();
$departments = $deptModel->getAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">Browse Courses</h1>
        <p class="page-subtitle">Explore and enroll in available courses.</p>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger">⚠️ <?php echo User::e($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success">✅ <?php echo User::e($success); ?></div>
<?php endif; ?>

<!-- Department Filter -->
<div class="card mb-3">
    <form method="GET" class="form-inline">
        <div class="form-group">
            <label for="dept-filter">Filter by Department</label>
            <select id="dept-filter" name="department" class="form-control" onchange="this.form.submit()">
                <option value="0">All Departments</option>
                <?php foreach ($departments as $d): ?>
                    <option value="<?php echo $d['id']; ?>" <?php echo ($filterDept === (int)$d['id']) ? 'selected' : ''; ?>>
                        <?php echo User::e($d['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<!-- Courses Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Available Courses (<?php echo count($courses); ?>)</h3>
    </div>
    <div class="table-wrapper" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Course Name</th>
                    <th>Department</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($courses)): ?>
                    <tr><td colspan="5" class="table-empty">No courses found.</td></tr>
                <?php else: ?>
                    <?php foreach ($courses as $c): ?>
                    <?php $enrolled = $enrollmentModel->isEnrolled($userId, (int)$c['id']); ?>
                    <tr>
                        <td><span class="badge badge-student"><?php echo User::e($c['code']); ?></span></td>
                        <td><strong><?php echo User::e($c['name']); ?></strong></td>
                        <td><?php echo User::e($c['department_name']); ?></td>
                        <td><?php echo User::e($c['description'] ?? '—'); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
                                <input type="hidden" name="course_id" value="<?php echo $c['id']; ?>">
                                <?php if ($enrolled): ?>
                                    <input type="hidden" name="action" value="unenroll">
                                    <button type="submit" class="btn btn-sm btn-danger">Unenroll</button>
                                <?php else: ?>
                                    <input type="hidden" name="action" value="enroll">
                                    <button type="submit" class="btn btn-sm btn-success">Enroll</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
