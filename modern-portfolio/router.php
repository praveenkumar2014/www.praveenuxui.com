<?php
/**
 * Local router for `php -S` to support clean URLs.
 *
 * Usage:
 *   php -S localhost:8000 router.php
 */
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = $uri ?: '/';

// Let the built-in server handle existing files directly (assets, css, js, images, etc.)
$localFile = __DIR__ . $path;
if ($path !== '/' && is_file($localFile)) {
    return false;
}

// Normalize "/foo/" -> "/foo"
$path = rtrim($path, '/');
if ($path === '') {
    $path = '/';
}

// Support admin routes like /admin and /admin/dashboard.php
if ($path === '/admin') {
    require __DIR__ . '/admin/index.php';
    return true;
}
if (str_starts_with($path, '/admin/')) {
    $adminRelative = substr($path, strlen('/admin/'));
    $adminFile = __DIR__ . '/admin/' . $adminRelative;
    if (is_file($adminFile)) {
        require $adminFile;
        return true;
    }
}

// Map clean route to a PHP page when available, else fall back to generated HTML.
$slug = $path === '/' ? 'index' : ltrim($path, '/');
$phpFile = __DIR__ . '/' . $slug . '.php';
$htmlFile = __DIR__ . '/' . $slug . '.html';

if (is_file($phpFile)) {
    $_SERVER['PHP_SELF'] = '/' . $slug . '.php';
    $_SERVER['SCRIPT_NAME'] = '/' . $slug . '.php';
    require $phpFile;
    return true;
}

if (is_file($htmlFile)) {
    header('Content-Type: text/html; charset=utf-8');
    readfile($htmlFile);
    return true;
}

http_response_code(404);
header('Content-Type: text/plain; charset=utf-8');
echo "404 Not Found\n";
