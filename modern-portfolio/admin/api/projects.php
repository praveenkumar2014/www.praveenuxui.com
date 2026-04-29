<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/db_fetch.php';

use App\Helpers\Security;

session_start();
Security::setSecurityHeaders();

header('Content-Type: application/json');

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$projects = fetch_projects($limit);

echo json_encode($projects);
