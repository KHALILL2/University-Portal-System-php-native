<?php
$pageTitle = 'Manage News';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/News.php';
requireRole('admin');

$newsModel = new News();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        if ($_POST['action'] === 'create') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            if ($title === '' || $content === '') {
                $error = "Title and content are required.";
            } elseif ($newsModel->create($title, $content, (int)$_SESSION['user_id'])) {
                $success = "News article published!";
            } else {
                $error = "Failed to publish news.";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0 && $newsModel->delete($id)) {
                $success = "News article deleted.";
            } else {
                $error = "Failed to delete news.";
            }
        }
    }
}

$articles = $newsModel->getAll();
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">Manage News</h1>
        <p class="page-subtitle">Publish announcements for students.</p>
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
        <h3 class="card-title">📝 Publish New Article</h3>
    </div>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="create">
        <div class="form-group">
            <label for="n-title">Title</label>
            <input type="text" id="n-title" name="title" class="form-control" placeholder="Announcement title..." required>
        </div>
        <div class="form-group">
            <label for="n-content">Content</label>
            <textarea id="n-content" name="content" class="form-control" rows="4" placeholder="Write the full announcement..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Publish</button>
    </form>
</div>

<!-- News Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Articles (<?php echo count($articles); ?>)</h3>
    </div>
    <div class="table-wrapper" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                    <tr><td colspan="4" class="table-empty">No news articles yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($articles as $a): ?>
                    <tr>
                        <td><strong><?php echo User::e($a['title']); ?></strong></td>
                        <td><?php echo User::e($a['author_name']); ?></td>
                        <td><?php echo User::e($a['published_at']); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit_news.php?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
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
