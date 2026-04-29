<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Helpers\Security;
use App\Services\LinkedInService;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

session_start();
Security::setSecurityHeaders();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

$linkedin = new LinkedInService();

if ($action === 'post_blog') {
    $blogId = (int)($input['blog_id'] ?? 0);
    $db = new SQLite3($_ENV['DB_PATH']);
    $stmt = $db->prepare('SELECT * FROM blogs WHERE id = :id');
    $stmt->bindValue(':id', $blogId, SQLITE3_INTEGER);
    $res = $stmt->execute();
    $blog = $res->fetchArray(SQLITE3_ASSOC);

    if (!$blog) {
        echo json_encode(['error' => 'Blog not found']);
        exit;
    }

    // In a real scenario, you'd have the access token and URN stored in DB per user
    $accessToken = $_ENV['LINKEDIN_ACCESS_TOKEN'] ?? '';
    $urn = $_ENV['LINKEDIN_PERSON_URN'] ?? '';

    if (empty($accessToken) || empty($urn)) {
        echo json_encode(['error' => 'LinkedIn not configured in .env']);
        exit;
    }

    $text = "New Blog Post: " . $blog['title'] . "\n\n" . strip_tags($blog['content']) . "\n\nRead more on my portfolio!";
    $result = $linkedin->postUpdate($accessToken, $urn, $text);

    if (isset($result['error'])) {
        echo json_encode(['error' => $result['error']]);
    } else {
        echo json_encode(['success' => 'Posted to LinkedIn successfully!', 'data' => $result]);
    }
} else {
    echo json_encode(['error' => 'Invalid action']);
}
