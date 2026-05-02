<?php
declare(strict_types=1);

session_start();

// ---- Path constants ----
define('ROOT_PATH', dirname(__DIR__));
// Detect the base URL dynamically from the script path
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// Normalise: strip trailing config/ or views/ etc. to get the project root URL
$baseUrl = rtrim($scriptDir, '/');
// If the script is inside a subfolder like /config, /views, /views/admin, strip back
$depth = substr_count(str_replace(ROOT_PATH, '', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']))), '/');
// Simplify: always compute from ROOT_PATH vs DOCUMENT_ROOT
$docRoot = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
$rootPath = str_replace('\\', '/', ROOT_PATH);
$baseDir = trim(str_replace($docRoot, '', $rootPath), '/');
define('BASE_URL', $baseDir ? '/' . $baseDir : '');

require_once ROOT_PATH . '/config/Database.php';

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verifyCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Redirect wrapper — prepends BASE_URL when path is relative
function redirect(string $path): void {
    if (strpos($path, 'http') === 0 || strpos($path, '/') === 0) {
        header("Location: $path");
    } else {
        header("Location: " . BASE_URL . "/$path");
    }
    exit;
}
