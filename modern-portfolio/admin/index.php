<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Helpers\Security;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

session_start();
Security::setSecurityHeaders();

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Security validation failed. Please try again.";
    } else {
        $db = new SQLite3($_ENV['DB_PATH']);
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :user');
        $stmt->bindValue(':user', Security::sanitizeInput($_POST['username']), SQLITE3_TEXT);
        $res = $stmt->execute();
        $user = $res->fetchArray(SQLITE3_ASSOC);

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_role'] = $user['role'] ?? 'admin';
            $_SESSION['admin_username'] = $user['username'];
            // Regenerate session ID to prevent fixation
            session_regenerate_id(true);
            header('Location: dashboard.php');
        } else {
            $error = "Invalid credentials";
        }
    }
}
$csrf_token = Security::generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PraveenUXUI — Madmin Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fontawsome/css/all.min.css">
    <link rel="stylesheet" href="../css/glassy.css">
    <style>
        body { min-height: 100vh; overflow-x: hidden; }

        .admin-auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
        }

        @media (max-width: 992px) {
            .admin-auth-shell { grid-template-columns: 1fr; }
            .admin-auth-left { display: none; }
            .admin-auth-right { padding: 24px; }
        }

        .admin-auth-left {
            position: relative;
            padding: 56px;
            display: flex;
            align-items: center;
        }

        .admin-auth-left::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(900px 500px at 20% 20%, rgba(71, 112, 255, 0.20), transparent 60%),
                radial-gradient(700px 450px at 80% 70%, rgba(168, 85, 247, 0.18), transparent 60%);
            pointer-events: none;
        }

        .admin-auth-left-inner {
            position: relative;
            max-width: 560px;
            width: 100%;
            padding: 48px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            backdrop-filter: blur(18px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.35);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 900;
            letter-spacing: -0.02em;
            font-size: 22px;
            margin-bottom: 18px;
        }
        .brand .primary { color: var(--accent); }
        .admin-auth-left h1 {
            font-size: 44px;
            line-height: 1.08;
            font-weight: 900;
            margin: 0 0 14px;
        }
        .admin-auth-left p {
            color: var(--text-dim);
            margin: 0 0 24px;
            line-height: 1.7;
            font-size: 15px;
        }
        .feature-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 12px; }
        .feature-list li {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            color: rgba(240,242,245,0.85);
            font-weight: 600;
        }
        .feature-list i { color: var(--accent); margin-top: 2px; }

        .admin-auth-right {
            padding: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 36px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(18px);
            box-shadow: 0 24px 48px rgba(0,0,0,0.35);
        }

        .login-title {
            font-size: 26px;
            font-weight: 900;
            margin: 0 0 6px;
        }
        .login-subtitle {
            color: var(--text-dim);
            margin: 0 0 18px;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-label { color: rgba(240,242,245,0.70); font-size: 12px; font-weight: 700; letter-spacing: 0.02em; }
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #fff;
            border-radius: 14px;
            padding: 12px 14px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: var(--accent);
            color: #fff;
            box-shadow: 0 0 0 4px rgba(71, 112, 255, 0.12);
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 14px;
            padding: 12px 14px;
            font-weight: 800;
        }
        .btn-primary:hover { filter: brightness(1.05); }

        .back-link {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            text-decoration: none;
            color: rgba(240,242,245,0.8);
            font-weight: 700;
            font-size: 13px;
        }
        .back-link:hover { color: #fff; }
    </style>
</head>

<body class="glassy-theme">
    <div id="background-canvas"></div>
    <div class="glow-orb" id="orb-1"></div>
    <div class="glow-orb" id="orb-2" style="background: rgba(168, 85, 247, 0.2); animation-delay: -10s;"></div>

    <div class="admin-auth-shell">
        <section class="admin-auth-left">
            <div class="admin-auth-left-inner">
                <div class="brand">
                    <span>Praveen<span class="primary">UXUI</span></span>
                    <span class="text-secondary" style="font-weight:800;">·</span>
                    <span style="opacity:0.85;">M<span class="primary">admin</span></span>
                </div>
                <h1>Content & Portfolio Control.</h1>
                <p>
                    Log in to manage projects, blogs, skills, and site settings for <strong>praveenuxui.com</strong>.
                    This admin is styled to match the frontend experience.
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-shield-alt"></i> CSRF-protected login and session hardening</li>
                    <li><i class="fas fa-briefcase"></i> Add projects with image, link, and description</li>
                    <li><i class="fas fa-magic"></i> Modern glass UI consistent with the public site</li>
                </ul>
            </div>
        </section>

        <section class="admin-auth-right">
            <div class="login-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="login-title">Admin Login</div>
                        <div class="login-subtitle">Authorized access only. Use your CMS credentials.</div>
                    </div>
                    <a class="back-link" href="/index" title="Back to site">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>

                <?php if (isset($error) && $error): ?>
                    <div class="alert alert-danger" style="background: rgba(255,0,0,0.08); border: 1px solid rgba(255,0,0,0.18); color: #ffb4b4; border-radius: 14px;">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control shadow-none" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        Login to Dashboard
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
        </section>
    </div>
</body>

</html>