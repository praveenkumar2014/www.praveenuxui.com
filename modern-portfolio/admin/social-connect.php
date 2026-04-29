<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Social Connect | Madmin</title>
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
        .social-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 25px; margin-bottom: 20px; }
        .status-badge { font-size: 11px; padding: 4px 10px; border-radius: 10px; font-weight: 700; text-transform: uppercase; }
        .status-active { background: rgba(0,255,100,0.1); color: #00ff64; }
        .status-inactive { background: rgba(255,255,255,0.05); color: #888; }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column">
        <div class="logo-text mb-5" style="font-size: 24px; font-weight: 800;">M<span>admin</span></div>
        <nav class="flex-grow-1">
            <a href="dashboard" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="manage-projects" class="nav-link"><i class="fas fa-briefcase"></i> Projects</a>
            <a href="manage-blogs" class="nav-link"><i class="fas fa-blog"></i> Blogs</a>
            <a href="manage-skills" class="nav-link"><i class="fas fa-tools"></i> Skills & Icons</a>
            <a href="ai-job-agent" class="nav-link text-info"><i class="fas fa-robot"></i> AI Job Agent</a>
            <a href="ai-asset-lab" class="nav-link text-warning"><i class="fas fa-wand-magic-sparkles"></i> AI Asset Lab</a>
            <a href="social-connect" class="nav-link active"><i class="fas fa-share-alt"></i> Social Connect</a>
            <a href="settings" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
        </nav>
        <a href="logout" class="nav-link btn-logout text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1 class="mb-5" style="font-size: 32px; font-weight: 800;">Social Connect 🔗</h1>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="social-card">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fab fa-instagram fa-2x text-danger"></i>
                            <div>
                                <h3 class="h5 mb-0">Instagram</h3>
                                <span class="status-badge status-active">Connected</span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">Disconnect</button>
                    </div>
                    <p class="text-secondary small mb-4">Automate Reels and Posts showcasing your latest projects.</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm flex-grow-1">Schedule Post</button>
                        <button class="btn btn-outline-info btn-sm">View Analytics</button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="social-card">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fab fa-linkedin fa-2x text-primary"></i>
                            <div>
                                <h3 class="h5 mb-0">LinkedIn</h3>
                                <span class="status-badge status-active">Connected</span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">Disconnect</button>
                    </div>
                    <p class="text-secondary small mb-4">Share thought leadership articles and professional milestones.</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm flex-grow-1">Auto-Post Blog</button>
                        <button class="btn btn-outline-info btn-sm">Career Insights</button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="social-card">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fab fa-youtube fa-2x text-danger"></i>
                            <div>
                                <h3 class="h5 mb-0">YouTube</h3>
                                <span class="status-badge status-inactive">Disconnected</span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary">Connect</button>
                    </div>
                    <p class="text-secondary small mb-4">Upload UX Case Study videos and Design Tutorials.</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="social-card">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fab fa-facebook fa-2x text-primary"></i>
                            <div>
                                <h3 class="h5 mb-0">Facebook</h3>
                                <span class="status-badge status-active">Connected</span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">Disconnect</button>
                    </div>
                    <p class="text-secondary small mb-4">Sync portfolio updates to your professional page.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
