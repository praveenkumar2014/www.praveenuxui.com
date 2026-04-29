<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Madmin Dashboard | Portfolio CMS</title>
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
        .nav-link i { font-size: 18px; }
        .stat-card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); padding: 25px; border-radius: 20px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); border-color: #4770FF; }
        .btn-logout { margin-top: auto; color: #ff6060 !important; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <header class="admin-header">
            <div>
                <h1 style="font-size: 28px; font-weight: 700;">Welcome back, Praveen!</h1>
                <p class="text-secondary">Control every component of your premium portfolio.</p>
            </div>
            <div class="user-profile d-flex align-items-center gap-3">
                <div class="text-end">
                    <div class="fw-bold">Administrator</div>
                    <div class="small text-secondary">Super Admin</div>
                </div>
                <img loading="lazy" src="../assets/img/images/profile.png" width="45" height="45" class="rounded-circle" style="border: 2px solid #4770FF;">
            </div>
        </header>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="text-secondary small mb-1">Total Projects</div>
                    <div class="h2 mb-0"><?php echo $db->querySingle("SELECT COUNT(*) FROM projects"); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="text-secondary small mb-1">Blog Posts</div>
                    <div class="h2 mb-0"><?php echo $db->querySingle("SELECT COUNT(*) FROM blogs"); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="text-secondary small mb-1">Service Modules</div>
                    <div class="h2 mb-0"><?php echo $db->querySingle("SELECT COUNT(*) FROM services") ?: 0; ?></div>
                </div>
            </div>
        </div>

        <div class="card bg-transparent" style="border: 1px solid rgba(255,255,255,0.05); border-radius: 20px;">
            <div class="card-body p-4">
                <h3 class="mb-4" style="font-size: 20px;">Quick Actions</h3>
                <div class="d-flex gap-3">
                    <a href="manage-projects?action=add" class="btn btn-outline-primary px-4 py-2" style="border-radius: 10px;">Add New Project</a>
                    <a href="manage-blogs?action=add" class="btn btn-outline-primary px-4 py-2" style="border-radius: 10px;">Write New Blog</a>
                    <a href="../index.php" target="_blank" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 10px;">View Live Site</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/ai-assistant.php'; ?>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
