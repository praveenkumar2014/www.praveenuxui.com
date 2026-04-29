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

header('Content-Type: application/json');

if (!RateLimiter::check('ai_assets', 3, 300)) { // Strict limit for images
    http_response_code(429);
    echo json_encode(['error' => 'Too many requests. Image generation is resource-intensive.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$prompt = Security::sanitizeInput($input['prompt'] ?? '');
$size = Security::sanitizeInput($input['size'] ?? '1024x1024');

if (empty($prompt)) {
    echo json_encode(['error' => 'Prompt is required']);
    exit;
}

$llm = new LLMService();
$url = $llm->generateImage($prompt, $size);

if ($url) {
    echo json_encode(['url' => $url]);
} else {
    echo json_encode(['error' => 'Failed to generate image. Check your API key or quota.']);
}
