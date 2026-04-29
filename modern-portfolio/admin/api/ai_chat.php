<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Helpers\Security;
use App\Helpers\RateLimiter;
use App\Services\LLMService;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

session_start();
Security::setSecurityHeaders();

if (!RateLimiter::check('ai_chat', 10, 60)) {
    header('Content-Type: application/json', true, 429);
    echo json_encode(['error' => 'Too many requests. Please try again later.']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$message = Security::sanitizeInput($input['message'] ?? '');

if (empty($message)) {
    echo json_encode(['error' => 'Message is empty']);
    exit;
}

$llm = new LLMService();
$response = $llm->getResponse($message, $_SESSION['chat_history'] ?? []);

// Store history
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}
$_SESSION['chat_history'][] = ['role' => 'user', 'content' => $message];
$_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $response];

// Keep only last 10 messages for context
if (count($_SESSION['chat_history']) > 10) {
    $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -10);
}

echo json_encode(['response' => $response]);
