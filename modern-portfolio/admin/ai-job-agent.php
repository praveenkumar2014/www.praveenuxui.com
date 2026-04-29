<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Job Agent | Madmin</title>
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
        .job-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 25px; margin-bottom: 20px; transition: 0.3s; }
        .job-card:hover { border-color: #4770FF; background: rgba(71, 112, 255, 0.05); }
        .salary-badge { background: rgba(0, 255, 100, 0.1); color: #00ff64; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .ai-status { font-size: 14px; color: #4770FF; display: flex; align-items: center; gap: 8px; }
        .pulsing { width: 10px; height: 10px; background: #4770FF; border-radius: 50%; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(71, 112, 255, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(71, 112, 255, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(71, 112, 255, 0); } }
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
            <a href="ai-job-agent" class="nav-link active text-info"><i class="fas fa-robot"></i> AI Job Agent</a>
            <a href="ai-asset-lab" class="nav-link text-warning"><i class="fas fa-wand-magic-sparkles"></i> AI Asset Lab</a>
            <a href="social-connect" class="nav-link"><i class="fas fa-share-alt"></i> Social Connect</a>
            <a href="settings" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
        </nav>
        <a href="logout" class="nav-link btn-logout text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="admin-header d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 style="font-size: 32px; font-weight: 800;">AI Career Agent ✦</h1>
                <p class="text-secondary">Hunting for high-value UX Architect roles (60L - 1Cr range).</p>
            </div>
            <div class="ai-status">
                <div class="pulsing"></div>
                Agent Live: Searching Global Markets...
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div id="job-results">
                    <!-- Simulated Results -->
                    <div class="job-card d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <h3 class="h5 mb-0">Principal UX Architect</h3>
                                <span class="salary-badge">₹75L - ₹95L PA</span>
                            </div>
                            <div class="text-secondary small mb-3">Google India • Remote / Hyderabad • Posted 2 days ago</div>
                            <p class="small text-light">Required: 15+ years exp, HCI expertise, Generative AI design strategy, Leadership.</p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary px-3" onclick="optimizeResume('Principal UX Architect @ Google')">Optimize & Apply</button>
                                <button class="btn btn-sm btn-outline-secondary px-3">View Details</button>
                            </div>
                        </div>
                        <img loading="lazy" src="https://logo.clearbit.com/google.com" width="40" class="rounded">
                    </div>

                    <div class="job-card d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <h3 class="h5 mb-0">Design Director (Agentic AI)</h3>
                                <span class="salary-badge">₹80L - ₹1.2Cr PA</span>
                            </div>
                            <div class="text-secondary small mb-3">Microsoft • Bengaluru • Posted 5 hours ago</div>
                            <p class="small text-light">Required: AI/ML product design, Enterprise systems, Stakeholder management.</p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary px-3" onclick="optimizeResume('Design Director @ Microsoft')">Optimize & Apply</button>
                                <button class="btn btn-sm btn-outline-secondary px-3">View Details</button>
                            </div>
                        </div>
                        <img loading="lazy" src="https://logo.clearbit.com/microsoft.com" width="40" class="rounded">
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card bg-transparent" style="border: 1px solid rgba(255,255,255,0.05); border-radius: 20px;">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-4">AI Optimizer Settings</h4>
                        <div class="mb-3">
                            <label class="form-label small text-secondary">Target Salary Range</label>
                            <select class="form-select bg-dark border-secondary text-white">
                                <option>60L - 80L</option>
                                <option selected>80L - 1.2Cr</option>
                                <option>1.5Cr+</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small text-secondary">Base Resume</label>
                            <div class="p-3 rounded bg-dark border border-secondary d-flex align-items-center justify-content-between">
                                <span class="small">Praveen_Kumar_Architect.docx</span>
                                <i class="fas fa-file-word text-primary"></i>
                            </div>
                        </div>
                        <button class="btn btn-outline-info w-100" onclick="alert('AI Agent is now scanning LinkedIn, Indeed, and Top Company portals...')">
                            <i class="fas fa-search me-2"></i> Refresh AI Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function optimizeResume(jobTitle) {
            alert("✦ AI Agent is optimizing your resume for: " + jobTitle + "\n\n1. Analyzing JD keywords...\n2. Modifying Architect_M.docx sections...\n3. Ready for download!");
            window.location.href = "../assets/Praveen_Kumar_K_Resume.pdf"; // Fallback to current resume
        }
    </script>
</body>
</html>
