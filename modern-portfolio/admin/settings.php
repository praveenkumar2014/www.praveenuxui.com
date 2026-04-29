<?php include 'includes/config.php'; 

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save to a settings table (need to create it)
    $db->exec("CREATE TABLE IF NOT EXISTS settings (key TEXT PRIMARY KEY, value TEXT)");
    foreach($_POST as $key => $val) {
        $stmt = $db->prepare("INSERT OR REPLACE INTO settings (key, value) VALUES (:k, :v)");
        $stmt->bindValue(':k', $key, SQLITE3_TEXT);
        $stmt->bindValue(':v', $val, SQLITE3_TEXT);
        $stmt->execute();
    }
    $success = "Settings updated successfully!";
}

$res = $db->query("SELECT * FROM settings");
$settings = [];
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $settings[$row['key']] = $row['value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Madmin</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/glassy.css">
    <link rel="stylesheet" href="../assets/fontawsome/css/all.min.css">
    <style>
        :root { --sidebar-width: 260px; }
        body { background: #080808; color: #fff; min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); position: fixed; height: 100vh; background: rgba(255, 255, 255, 0.02); border-right: 1px solid rgba(255, 255, 255, 0.05); padding: 30px 20px; backdrop-filter: blur(20px); }
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .nav-link { color: #888; padding: 12px 15px; border-radius: 12px; margin-bottom: 8px; transition: all 0.3s; display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: rgba(71, 112, 255, 0.1); color: #4770FF; }
        .form-control { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; border-radius: 10px; padding: 12px; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1 class="mb-5" style="font-size: 32px; font-weight: 800;">Global Settings ⚙️</h1>

        <?php if(isset($success)): ?>
            <div class="alert alert-success bg-success text-white border-0 rounded-4 mb-4"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xl-8">
                <div class="card bg-transparent" style="border: 1px solid rgba(255,255,255,0.05); border-radius: 24px;">
                    <div class="card-body p-4">
                        <form method="POST">
                            <h5 class="mb-4 text-primary">Contact Information</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small text-secondary">Public Email</label>
                                    <input type="email" name="contact_email" class="form-control" value="<?php echo $settings['contact_email'] ?? 'praveenkumar.kanneganti@gmail.com'; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-secondary">Phone Number</label>
                                    <input type="text" name="contact_phone" class="form-control" value="<?php echo $settings['contact_phone'] ?? '+91 90000 00000'; ?>">
                                </div>
                            </div>

                            <h5 class="mb-4 text-primary">Social Links</h5>
                            <div class="mb-3">
                                <label class="form-label small text-secondary">LinkedIn URL</label>
                                <input type="url" name="social_linkedin" class="form-control" value="<?php echo $settings['social_linkedin'] ?? ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-secondary">GitHub URL</label>
                                <input type="url" name="social_github" class="form-control" value="<?php echo $settings['social_github'] ?? ''; ?>">
                            </div>

                            <h5 class="mb-4 mt-5 text-primary">API Integrations</h5>
                            <div class="mb-4">
                                <label class="form-label small text-secondary">Cal.com Username</label>
                                <input type="text" name="cal_username" class="form-control" value="<?php echo $settings['cal_username'] ?? 'praveenkumar-kanneganti'; ?>">
                            </div>

                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-3 mt-3">Save All Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
