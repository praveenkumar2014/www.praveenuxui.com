<?php
session_start();
if(isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db_path = __DIR__ . '/../db/portfolio.db';
    $db = new SQLite3($db_path);
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :user');
    $stmt->bindValue(':user', $_POST['username'], SQLITE3_TEXT);
    $res = $stmt->execute();
    $user = $res->fetchArray(SQLITE3_ASSOC);
    
    if($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Madmin Login | Portfolio CMS</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/glassy.css">
    <style>
        body { height: 100vh; display: flex; align-items: center; justify-content: center; background: #0a0a0a; overflow: hidden; }
        .login-card { width: 400px; padding: 40px; border-radius: 24px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); }
        .form-control { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; border-radius: 12px; padding: 12px; }
        .form-control:focus { background: rgba(255, 255, 255, 0.08); border-color: #4770FF; color: #fff; box-shadow: none; }
        .btn-primary { background: #4770FF; border: none; border-radius: 12px; padding: 12px; font-weight: 600; }
        .logo-text { font-size: 24px; font-weight: 800; text-align: center; margin-bottom: 30px; }
        .logo-text span { color: #4770FF; }
    </style>
</head>
<body class="glassy-theme">
    <div id="background-canvas"></div>
    <div class="login-card">
        <div class="logo-text">M<span>admin</span></div>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger" style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.2); color: #ff8080;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-secondary small">Username</label>
                <input type="text" name="username" class="form-control shadow-none" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-secondary small">Password</label>
                <input type="password" name="password" class="form-control shadow-none" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login to Dashboard</button>
        </form>
    </div>
    <script>
        // Simple background particles for the login
        const canvas = document.createElement('canvas');
        canvas.id = 'background-canvas';
        document.body.prepend(canvas);
    </script>
</body>
</html>
