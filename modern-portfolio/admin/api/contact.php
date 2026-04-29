<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Helpers\Security;
use App\Services\EmailService;
use App\Core\LoggerService;
use App\Core\ErrorHandler;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

ErrorHandler::register();
Security::setSecurityHeaders();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$name    = Security::sanitizeInput($_POST['name'] ?? '');
$email   = Security::sanitizeInput($_POST['email'] ?? '');
$subject = Security::sanitizeInput($_POST['subject'] ?? '');
$message = Security::sanitizeInput($_POST['message'] ?? '');
$budget  = Security::sanitizeInput($_POST['budget'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['error' => 'Please fill in all required fields.']);
    exit;
}

try {
    $db = new SQLite3($_ENV['DB_PATH']);
    $stmt = $db->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':subject', "$subject (Budget: $budget)", SQLITE3_TEXT);
    $stmt->bindValue(':message', $message, SQLITE3_TEXT);
    $stmt->execute();

    // Send Notification Email
    $emailService = new EmailService();
    $emailBody = "
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Budget:</strong> $budget</p>
        <p><strong>Subject:</strong> $subject</p>
        <p><strong>Message:</strong><br>$message</p>
    ";
    
    $emailResult = $emailService->sendEmail($_ENV['FROM_EMAIL'], "Portfolio Inquiry: $subject", $emailBody);

    if ($emailResult === true) {
        echo json_encode(['success' => 'Successfully sent — I’ll get back to you ASAP.']);
    } else {
        // Log email failure but still report DB success
        LoggerService::error("Email failed: $emailResult");
        echo json_encode(['success' => 'Successfully saved — I’ll get back to you ASAP (email notify failed).']);
    }

} catch (\Exception $e) {
    LoggerService::error("Contact form error: " . $e->getMessage());
    echo json_encode(['error' => 'Sorry, there was an error processing your request.']);
}
