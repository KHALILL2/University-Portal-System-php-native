<?php
$pageTitle = 'News & Announcements';
require_once __DIR__ . '/../../includes/auth_middleware.php';
require_once __DIR__ . '/../../classes/News.php';
requireRole('student');

$newsModel = new News();
$articles = $newsModel->getAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<h1 class="page-title">News & Announcements</h1>
<p class="page-subtitle">Stay up to date with the latest university news.</p>

<div class="news-list">
    <?php if (empty($articles)): ?>
        <div class="card text-center">
            <p class="text-muted">No announcements at this time.</p>
        </div>
    <?php else: ?>
        <?php foreach ($articles as $a): ?>
        <div class="news-card">
            <h3><?php echo User::e($a['title']); ?></h3>
            <div class="news-meta">
                📅 <?php echo User::e($a['published_at']); ?> &nbsp;|&nbsp; ✍️ <?php echo User::e($a['author_name']); ?>
            </div>
            <div class="news-content">
                <?php echo nl2br(User::e($a['content'])); ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
