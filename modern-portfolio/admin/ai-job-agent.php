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
        :root {
            --sidebar-width: 260px;
        }

        body {
            background: #080808;
            color: #fff;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            height: 100vh;
            background: rgba(255, 255, 255, 0.02);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px 20px;
            backdrop-filter: blur(20px);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
        }

        .nav-link {
            color: #888;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(71, 112, 255, 0.1);
            color: #4770FF;
        }

        .job-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .job-card:hover {
            border-color: #4770FF;
            background: rgba(71, 112, 255, 0.05);
        }

        .salary-badge {
            background: rgba(0, 255, 100, 0.1);
            color: #00ff64;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .ai-status {
            font-size: 14px;
            color: #4770FF;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pulsing {
            width: 10px;
            height: 10px;
            background: #4770FF;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(71, 112, 255, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(71, 112, 255, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(71, 112, 255, 0);
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="admin-header d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 style="font-size: 32px; font-weight: 800;">AI Career Agent ✦</h1>
                <p class="text-secondary">Hunting for high-value UX Architect roles (60L - 1Cr range).</p>
            </div>
            <div class="ai-status">
                <div class="pulsing"></div>
                Agent Live: <span id="agent-text">Searching Global Markets...</span>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div id="job-results">
                    <!-- Loaded via JS -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-3 text-secondary">AI Agent is analyzing current market trends...</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card bg-transparent" style="border: 1px solid rgba(255,255,255,0.05); border-radius: 20px;">
                    <div class="card-body p-4">
                        <h4 class="h5 mb-4">AI Optimizer Settings</h4>
                        <div class="mb-3">
                            <label class="form-label small text-secondary">Target Salary Range</label>
                            <select class="form-select bg-dark border-secondary text-white" id="salary-range">
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
                        <button class="btn btn-outline-info w-100" id="btn-refresh-jobs" onclick="fetchJobs()">
                            <i class="fas fa-search me-2"></i> Refresh AI Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', fetchJobs);

        async function fetchJobs() {
            const container = document.getElementById('job-results');
            const btn = document.getElementById('btn-refresh-jobs');
            const agentText = document.getElementById('agent-text');

            btn.disabled = true;
            agentText.textContent = "Analyzing Global Markets...";

            try {
                const res = await fetch('api/ai_jobs.php');
                const data = await res.json();

                if (data.error) {
                    container.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                } else {
                    container.innerHTML = data.jobs.map(job => `
                        <div class="job-card d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <h3 class="h5 mb-0">${job.title}</h3>
                                    <span class="salary-badge">${job.salary}</span>
                                </div>
                                <div class="text-secondary small mb-3">${job.company} • ${job.location} • Posted ${job.posted}</div>
                                <p class="small text-light">${job.requirements}</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-primary px-3" onclick="optimizeResume('${job.title} @ ${job.company}')">Optimize & Apply</button>
                                    <button class="btn btn-sm btn-outline-secondary px-3">View Details</button>
                                </div>
                            </div>
                            <img loading="lazy" src="${job.logo_url}" width="40" class="rounded" onerror="this.src='https://via.placeholder.com/40'">
                        </div>
                    `).join('');
                }
            } catch (err) {
                container.innerHTML = `<div class="alert alert-danger">Failed to connect to AI Agent.</div>`;
            } finally {
                btn.disabled = false;
                agentText.textContent = "Agent Live: Idle";
            }
        }

        function optimizeResume(jobTitle) {
            alert("✦ AI Agent is optimizing your resume for: " + jobTitle + "\n\n1. Analyzing JD keywords...\n2. Modifying Architect_M.docx sections...\n3. Ready for download!");
            window.location.href = "../assets/Praveen_Kumar_K_Resume.pdf";
        }
    </script>
</body>

</html>