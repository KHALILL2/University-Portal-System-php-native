<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/classes/User.php';

// Make sure the request has a valid CSRF token so no one can force-logout a user
if (!isset($_GET['token']) || !verifyCsrfToken($_GET['token'])) {
    die("Invalid request.");
}

User::logout();
redirect('index.php');