<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Asset Lab | Madmin</title>
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
        .prompt-box { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; padding: 30px; margin-bottom: 30px; }
        .asset-preview { aspect-ratio: 16/9; background: #111; border-radius: 20px; display: flex; align-items: center; justify-content: center; border: 2px dashed rgba(255,255,255,0.05); overflow: hidden; position: relative; }
        .btn-generate { background: linear-gradient(135deg, #4770FF, #9D47FF); border: none; padding: 15px 30px; border-radius: 15px; font-weight: 700; font-size: 18px; transition: 0.3s; }
        .btn-generate:hover { transform: scale(1.02); box-shadow: 0 0 30px rgba(71, 112, 255, 0.4); }
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
            <a href="ai-asset-lab" class="nav-link active text-warning"><i class="fas fa-wand-magic-sparkles"></i> AI Asset Lab</a>
            <a href="social-connect" class="nav-link"><i class="fas fa-share-alt"></i> Social Connect</a>
            <a href="settings" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
        </nav>
        <a href="logout" class="nav-link btn-logout text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1 class="mb-5" style="font-size: 32px; font-weight: 800;">AI Asset Lab 🎨</h1>

        <div class="row">
            <div class="col-xl-7">
                <div class="prompt-box">
                    <label class="form-label text-secondary small">Image Generation Prompt</label>
                    <textarea id="ai-prompt" class="form-control bg-transparent text-white border-0 p-0 h2 mb-4" placeholder="Describe your dream asset..." rows="3" style="resize: none;"></textarea>
                    
                    <div class="d-flex gap-3 mb-4">
                        <select class="form-select bg-dark border-secondary text-white w-auto">
                            <option>16:9 Landscape</option>
                            <option>1:1 Square</option>
                            <option>9:16 Portrait</option>
                        </select>
                        <select class="form-select bg-dark border-secondary text-white w-auto">
                            <option>Glassmorphism</option>
                            <option>3D Abstract</option>
                            <option>Cyberpunk</option>
                            <option>Minimalist</option>
                        </select>
                    </div>

                    <button class="btn btn-generate w-100" onclick="generateAsset()">
                        <i class="fas fa-sparkles me-2"></i> Generate Asset with AI
                    </button>
                </div>

                <div class="card bg-transparent" style="border: 1px solid rgba(255,255,255,0.05); border-radius: 24px;">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Recent Generations</h5>
                        <div class="row g-3">
                            <div class="col-3"><img loading="lazy" src="../assets/img/projects/project-1.png" class="img-fluid rounded-3 border border-secondary"></div>
                            <div class="col-3"><img loading="lazy" src="../assets/img/projects/project-2.png" class="img-fluid rounded-3 border border-secondary"></div>
                            <div class="col-3"><img loading="lazy" src="../assets/img/projects/project-3.png" class="img-fluid rounded-3 border border-secondary"></div>
                            <div class="col-3"><img loading="lazy" src="../assets/img/projects/project-4.png" class="img-fluid rounded-3 border border-secondary"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="asset-preview" id="asset-container">
                    <div class="text-center text-secondary">
                        <i class="fas fa-image fa-3x mb-3"></i>
                        <p>Preview will appear here</p>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-outline-primary flex-grow-1" id="btn-save" disabled>Use in Project</button>
                    <button class="btn btn-outline-secondary" id="btn-download" disabled><i class="fas fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function generateAsset() {
            const prompt = document.getElementById('ai-prompt').value;
            if(!prompt) { alert("Please enter a prompt!"); return; }
            
            const container = document.getElementById('asset-container');
            container.innerHTML = '<div class="text-center"><div class="spinner-border text-primary mb-3"></div><p>AI is dreaming up your asset...</p></div>';
            
            setTimeout(() => {
                container.innerHTML = '<img loading="lazy" src="../assets/img/projects/project-1.png" class="img-fluid w-100 h-100" style="object-fit: cover;">';
                document.getElementById('btn-save').disabled = false;
                document.getElementById('btn-download').disabled = false;
            }, 3000);
        }
    </script>
</body>
</html>
