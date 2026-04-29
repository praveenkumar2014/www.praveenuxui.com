<?php
/**
 * auth.php — Authentication logic for Praveen's Portfolio Admin
 */
session_start();

// Define Admin credentials (Super Admin)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'Praveen@2026'); // Example password

function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}
?>
