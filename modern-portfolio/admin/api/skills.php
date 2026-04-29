<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/db_fetch.php';

use App\Helpers\Security;

session_start();
Security::setSecurityHeaders();

header('Content-Type: application/json');

$skills = fetch_skills();

echo json_encode($skills);
