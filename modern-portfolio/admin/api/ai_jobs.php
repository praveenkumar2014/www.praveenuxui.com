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

if (!RateLimiter::check('ai_jobs', 5, 60)) {
    http_response_code(429);
    echo json_encode(['error' => 'Too many requests. Please try again later.']);
    exit;
}

// In a real scenario, we might scrape or use a job API. 
// For this "Agentic" portfolio, we use the LLM to "hunt" for high-value roles.
$llm = new LLMService();
$prompt = "Generate a list of 3 real or highly realistic current high-value Senior UX/UI Architect or Design Director job openings in India/Remote. 
Format as a JSON array of objects with keys: title, salary, company, location, posted, requirements, logo_url. 
Focus on 60L-1Cr+ range. Return ONLY the JSON array.";

$response = $llm->getResponse($prompt);
// Attempt to extract JSON if LLM adds markdown
if (preg_match('/\[.*\]/s', $response, $matches)) {
    $response = $matches[0];
}

$jobs = json_decode($response, true);

if (!$jobs) {
    // Fallback if LLM fails
    $jobs = [
        [
            'title' => 'Principal UX Architect',
            'salary' => '₹75L - ₹95L PA',
            'company' => 'Google India',
            'location' => 'Remote / Hyderabad',
            'posted' => '2 days ago',
            'requirements' => '15+ years exp, HCI expertise, Generative AI design strategy.',
            'logo_url' => 'https://logo.clearbit.com/google.com'
        ]
    ];
}

echo json_encode(['jobs' => $jobs]);
