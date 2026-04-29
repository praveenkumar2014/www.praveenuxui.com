<?php
/**
 * auth.php — Authentication logic for Praveen's Portfolio Admin
 */
if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define Admin credentials (Super Admin)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'Praveen@2026'); // Example password

function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_login() {
    // Skip login check during static site build (CLI mode)
    if (php_sapi_name() === 'cli') {
        return;
    }
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}
?>
