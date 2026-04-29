<?php
declare(strict_types=1);

/**
 * Vercel entrypoint.
 *
 * Vercel's PHP runtime expects Serverless Functions under `api/`.
 * This file routes clean URLs to existing PHP/HTML pages in the repo root.
 */

$root = dirname(__DIR__);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = $uri ?: '/';

// Normalize "/foo/" -> "/foo"
$path = rtrim($path, '/');
if ($path === '') {
    $path = '/';
}

// Admin routes
if ($path === '/admin') {
    require $root . '/admin/index.php';
    return;
}
if (str_starts_with($path, '/admin/')) {
    $adminRelative = substr($path, strlen('/admin/'));
    $adminFile = $root . '/admin/' . $adminRelative;
    if (is_file($adminFile)) {
        require $adminFile;
        return;
    }
}

// Map clean route to a PHP page when available, else fall back to generated HTML.
$slug = $path === '/' ? 'index' : ltrim($path, '/');
$phpFile = $root . '/' . $slug . '.php';
$htmlFile = $root . '/' . $slug . '.html';

if (is_file($phpFile)) {
    $_SERVER['PHP_SELF'] = '/' . $slug . '.php';
    $_SERVER['SCRIPT_NAME'] = '/' . $slug . '.php';
    require $phpFile;
    return;
}

if (is_file($htmlFile)) {
    header('Content-Type: text/html; charset=utf-8');
    readfile($htmlFile);
    return;
}

http_response_code(404);
header('Content-Type: text/plain; charset=utf-8');
echo "404 Not Found\n";

