<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Helpers\Security;
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

function envv(string $key, string $fallback = ''): string {
  return isset($_ENV[$key]) ? trim((string)$_ENV[$key]) : $fallback;
}

function post_json(string $url, array $payload, int $timeoutSeconds = 6): array {
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => $timeoutSeconds,
  ]);
  $body = curl_exec($ch);
  $err = curl_error($ch);
  $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return ['ok' => ($err === '' && $code >= 200 && $code < 300), 'code' => $code, 'err' => $err, 'body' => $body];
}

function get_json(string $url, int $timeoutSeconds = 6): array {
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => $timeoutSeconds,
  ]);
  $body = curl_exec($ch);
  $err = curl_error($ch);
  $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return ['ok' => ($err === '' && $code >= 200 && $code < 300), 'code' => $code, 'err' => $err, 'body' => $body];
}

$name = Security::sanitizeInput($_POST['name'] ?? '');
$email = Security::sanitizeInput($_POST['email'] ?? '');
$company = Security::sanitizeInput($_POST['company'] ?? '');
$role = Security::sanitizeInput($_POST['role'] ?? '');
$phone = Security::sanitizeInput($_POST['phone'] ?? '');
$linkedin = Security::sanitizeInput($_POST['linkedin'] ?? '');
$reason = Security::sanitizeInput($_POST['reason'] ?? '');
$source = Security::sanitizeInput($_POST['source'] ?? '');

if ($name === '' || $email === '' || $company === '' || $reason === '') {
  echo json_encode(['error' => 'Please fill all required fields.']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['error' => 'Please enter a valid email.']);
  exit;
}

$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
$ref = $_SERVER['HTTP_REFERER'] ?? '';
$ts = gmdate('c');

// 1) Persist locally (best-effort)
try {
  $dbPath = envv('DB_PATH');
  if ($dbPath !== '') {
    $db = new SQLite3($dbPath);
    $db->exec("CREATE TABLE IF NOT EXISTS cv_requests (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      email TEXT NOT NULL,
      company TEXT NOT NULL,
      role TEXT,
      phone TEXT,
      linkedin TEXT,
      reason TEXT,
      source TEXT,
      ip TEXT,
      user_agent TEXT,
      referrer TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $db->prepare("INSERT INTO cv_requests (name, email, company, role, phone, linkedin, reason, source, ip, user_agent, referrer)
      VALUES (:name, :email, :company, :role, :phone, :linkedin, :reason, :source, :ip, :ua, :ref)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':company', $company, SQLITE3_TEXT);
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $phone, SQLITE3_TEXT);
    $stmt->bindValue(':linkedin', $linkedin, SQLITE3_TEXT);
    $stmt->bindValue(':reason', $reason, SQLITE3_TEXT);
    $stmt->bindValue(':source', $source, SQLITE3_TEXT);
    $stmt->bindValue(':ip', $ip, SQLITE3_TEXT);
    $stmt->bindValue(':ua', $ua, SQLITE3_TEXT);
    $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
    $stmt->execute();
  }
} catch (\Throwable $e) {
  LoggerService::error('CV request DB insert failed: ' . $e->getMessage());
}

$summary = "📄 CV Download Request\n"
  . "Name: {$name}\n"
  . "Email: {$email}\n"
  . "Company: {$company}\n"
  . ($role ? "Role: {$role}\n" : "")
  . ($phone ? "Phone: {$phone}\n" : "")
  . ($linkedin ? "LinkedIn: {$linkedin}\n" : "")
  . "Reason: {$reason}\n"
  . ($source ? "Source: {$source}\n" : "")
  . ($ip ? "IP: {$ip}\n" : "")
  . "Time(UTC): {$ts}";

// 2) Google Sheets (best-effort) via Apps Script / webhook
try {
  $sheetsUrl = envv('SHEETS_WEBAPP_URL');
  if ($sheetsUrl !== '') {
    $sheetsRes = post_json($sheetsUrl, [
      'type' => 'cv_request',
      'timestamp' => $ts,
      'name' => $name,
      'email' => $email,
      'company' => $company,
      'role' => $role,
      'phone' => $phone,
      'linkedin' => $linkedin,
      'reason' => $reason,
      'source' => $source,
      'ip' => $ip,
      'user_agent' => $ua,
      'referrer' => $ref,
    ]);
    if (!$sheetsRes['ok']) {
      LoggerService::error('Sheets logging failed: ' . ($sheetsRes['err'] ?: ('HTTP ' . $sheetsRes['code'])));
    }
  }
} catch (\Throwable $e) {
  LoggerService::error('Sheets logging error: ' . $e->getMessage());
}

// 3) Telegram notify (best-effort)
try {
  $botToken = envv('TELEGRAM_BOT_TOKEN');
  $chatId = envv('TELEGRAM_CHAT_ID');
  if ($botToken !== '' && $chatId !== '') {
    $telegramUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
    $tgRes = post_json($telegramUrl, [
      'chat_id' => $chatId,
      'text' => $summary,
      'disable_web_page_preview' => true,
    ]);
    if (!$tgRes['ok']) {
      LoggerService::error('Telegram notify failed: ' . ($tgRes['err'] ?: ('HTTP ' . $tgRes['code'])));
    }
  }
} catch (\Throwable $e) {
  LoggerService::error('Telegram notify error: ' . $e->getMessage());
}

// 4) WhatsApp notify (best-effort)
// Option A: Provide a ready-made webhook URL that accepts ?text= or JSON.
// Option B: CallMeBot (simple personal WhatsApp notifications) via env vars.
try {
  $whUrl = envv('WHATSAPP_NOTIFY_URL');
  if ($whUrl !== '') {
    $delim = (strpos($whUrl, '?') === false) ? '?' : '&';
    $res = get_json($whUrl . $delim . 'text=' . urlencode($summary));
    if (!$res['ok']) {
      LoggerService::error('WhatsApp notify failed: ' . ($res['err'] ?: ('HTTP ' . $res['code'])));
    }
  } else {
    $waPhone = envv('WHATSAPP_CMB_PHONE');
    $waKey = envv('WHATSAPP_CMB_APIKEY');
    if ($waPhone !== '' && $waKey !== '') {
      $cmbUrl = 'https://api.callmebot.com/whatsapp.php?phone=' . urlencode($waPhone)
        . '&apikey=' . urlencode($waKey)
        . '&text=' . urlencode($summary);
      $res = get_json($cmbUrl);
      if (!$res['ok']) {
        LoggerService::error('CallMeBot WhatsApp notify failed: ' . ($res['err'] ?: ('HTTP ' . $res['code'])));
      }
    }
  }
} catch (\Throwable $e) {
  LoggerService::error('WhatsApp notify error: ' . $e->getMessage());
}

echo json_encode([
  'success' => 'Verified. Download will start now.',
  'downloadUrl' => '/assets/Praveen_Kumar_K_Resume.pdf',
]);

