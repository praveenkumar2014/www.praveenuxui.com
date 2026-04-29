<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Helpers\Security;
use App\Services\PaymentService;

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

$paymentService = new PaymentService();

if ($action === 'create_order') {
    $amount = (int)($input['amount'] ?? 0);
    if ($amount <= 0) {
        echo json_encode(['error' => 'Invalid amount']);
        exit;
    }

    $receiptId = 'rcpt_' . uniqid();
    try {
        $order = $paymentService->createOrder($amount, $receiptId);
        echo json_encode([
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'key' => $_ENV['RAZORPAY_KEY_ID']
        ]);
    } catch (\Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($action === 'verify_payment') {
    $orderId = $input['razorpay_order_id'] ?? '';
    $paymentId = $input['razorpay_payment_id'] ?? '';
    $signature = $input['razorpay_signature'] ?? '';

    if ($paymentService->verifySignature($orderId, $paymentId, $signature)) {
        // Handle success (e.g., update database)
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['error' => 'Invalid signature']);
    }
} else {
    echo json_encode(['error' => 'Invalid action']);
}
