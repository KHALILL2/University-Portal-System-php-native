<?php
$pageTitle = 'Edit News';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/News.php';
requireRole('admin');

$newsModel = new News();
$id = (int)($_GET['id'] ?? 0);
$article = $newsModel->getById($id);

if (!$article) {
    redirect('views/admin/manage_news.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($title === '' || $content === '') {
            $error = "Title and content are required.";
        } elseif ($newsModel->update($id, $title, $content)) {
            $success = "Article updated successfully!";
            $article = $newsModel->getById($id);
        } else {
            $error = "Update failed.";
        }
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="action-bar">
    <div>
        <h1 class="page-title">Edit Article</h1>
        <p class="page-subtitle">Update news content.</p>
    </div>
    <a href="manage_news.php" class="btn btn-secondary">← Back to News</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger">⚠️ <?php echo User::e($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success">✅ <?php echo User::e($success); ?></div>
<?php endif; ?>

<div class="card" style="max-width:700px;">
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo User::e($_SESSION['csrf_token']); ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo User::e($article['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" class="form-control" rows="8" required><?php echo User::e($article['content']); ?></textarea>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage_news.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
