<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

function requireLogin(): void {
    if (!isset($_SESSION['user_id'])) {
        redirect('index.php');
    }
}

function requireRole(string $role): void {
    requireLogin();
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        header('HTTP/1.0 403 Forbidden');
        echo '<!DOCTYPE html><html><head><title>403 Forbidden</title><link rel="stylesheet" href="' . BASE_URL . '/assets/css/style.css"></head>';
        echo '<body><div class="auth-wrapper"><div class="auth-card text-center">';
        echo '<h1>403 Forbidden</h1><p>You are not authorized to view this page.</p>';
        echo '<a href="' . BASE_URL . '/index.php" class="btn btn-primary mt-2">Go to Login</a>';
        echo '</div></div></body></html>';
        exit;
    }
}
