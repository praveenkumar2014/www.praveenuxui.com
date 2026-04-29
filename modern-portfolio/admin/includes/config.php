<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Helpers\Security;
use App\Core\ErrorHandler;
use App\Core\LoggerService;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

// Register global error handling
ErrorHandler::register();

session_start();
Security::setSecurityHeaders();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

// Role-based page protection
$current_page = basename($_SERVER['PHP_SELF']);
$user_role = $_SESSION['admin_role'] ?? 'admin';

$super_admin_pages = ['ai-job-agent.php', 'ai-asset-lab.php', 'social-connect.php', 'settings.php'];

if (in_array($current_page, $super_admin_pages) && $user_role !== 'super_admin') {
    header('Location: dashboard.php?error=unauthorized');
    exit;
}

// Database Connection Abstraction
if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
    // Production: Connect to PostgreSQL/MySQL via PDO
    try {
        $db_url = parse_url($_ENV['DATABASE_URL']);
        $dsn = "pgsql:host={$db_url['host']};port={$db_url['port']};dbname=" . ltrim($db_url['path'], '/');
        $db_conn = new PDO($dsn, $db_url['user'], $db_url['pass']);
        $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Wrap PDO to mimic SQLite3 fetchArray if needed, or refactor to use PDO globally
        $db = $db_conn;
    } catch (PDOException $e) {
        die("Production Database Connection Failed: " . $e->getMessage());
    }
} else {
    // Local: Fallback to SQLite
    $db = new SQLite3($_ENV['DB_PATH']);
}

// Global query wrapper to support both SQLite3 and PDO
function execute_query($query, $params = [])
{
    global $db;
    if ($db instanceof PDO) {
        $stmt = $db->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt;
    } else {
        $stmt = $db->prepare($query);
        foreach ($params as $key => $val) {
            $type = is_int($val) ? SQLITE3_INTEGER : SQLITE3_TEXT;
            $stmt->bindValue($key, $val, $type);
        }
        return $stmt->execute();
    }
}

function fetch_all($result)
{
    $rows = [];
    if ($result instanceof PDOStatement) {
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }
}

function query($sql, $params = [])
{
    global $db;
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    return $stmt->execute();
}
