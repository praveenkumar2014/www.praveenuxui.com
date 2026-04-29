<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = pathinfo($path, PATHINFO_EXTENSION);

// If the file exists and is a static asset, serve it as is
if ($ext && file_exists($_SERVER["DOCUMENT_ROOT"] . $path)) {
    return false;
}

// If no extension, try adding .php
if (!$ext) {
    $phpFile = $_SERVER["DOCUMENT_ROOT"] . $path . '.php';
    if (file_exists($phpFile)) {
        require $phpFile;
        exit;
    }
}

// Otherwise, let the built-in server handle it
return false;
