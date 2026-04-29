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

        .prompt-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .asset-preview {
            aspect-ratio: 16/9;
            background: #111;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed rgba(255, 255, 255, 0.05);
            overflow: hidden;
            position: relative;
        }

        .btn-generate {
            background: linear-gradient(135deg, #4770FF, #9D47FF);
            border: none;
            padding: 15px 30px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 18px;
            transition: 0.3s;
        }

        .btn-generate:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(71, 112, 255, 0.4);
        }
    </style>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1 class="mb-5" style="font-size: 32px; font-weight: 800;">AI Asset Lab 🎨</h1>

        <div class="row">
            <div class="col-xl-7">
                <div class="prompt-box">
                    <label class="form-label text-secondary small">Image Generation Prompt</label>
                    <textarea id="ai-prompt" class="form-control bg-transparent text-white border-0 p-0 h2 mb-4" placeholder="Describe your dream asset..." rows="3" style="resize: none;"></textarea>

                    <div class="d-flex gap-3 mb-4">
                        <select id="asset-size" class="form-select bg-dark border-secondary text-white w-auto">
                            <option value="1024x1024">1:1 Square</option>
                            <option value="1024x1792">9:16 Portrait</option>
                            <option value="1792x1024">16:9 Landscape</option>
                        </select>
                        <select id="asset-style" class="form-select bg-dark border-secondary text-white w-auto">
                            <option>Glassmorphism</option>
                            <option>3D Abstract</option>
                            <option>Cyberpunk</option>
                            <option>Minimalist</option>
                        </select>
                    </div>

                    <button class="btn btn-generate w-100" id="btn-generate" onclick="generateAsset()">
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
        async function generateAsset() {
            const promptInput = document.getElementById('ai-prompt');
            const prompt = promptInput.value;
            const size = document.getElementById('asset-size').value;
            const style = document.getElementById('asset-style').value;
            const btn = document.getElementById('btn-generate');

            if (!prompt) {
                alert("Please enter a prompt!");
                return;
            }

            const container = document.getElementById('asset-container');
            container.innerHTML = '<div class="text-center"><div class="spinner-border text-primary mb-3"></div><p>AI is dreaming up your asset...</p></div>';
            btn.disabled = true;

            try {
                const res = await fetch('api/ai_assets.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        prompt: `${style} style: ${prompt}`,
                        size: size
                    })
                });
                const data = await res.json();

                if (data.error) {
                    container.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                } else {
                    container.innerHTML = `<img loading="lazy" src="${data.url}" class="img-fluid w-100 h-100" style="object-fit: cover;">`;
                    document.getElementById('btn-save').disabled = false;
                    document.getElementById('btn-download').disabled = false;
                    document.getElementById('btn-download').onclick = () => window.open(data.url, '_blank');
                }
            } catch (err) {
                container.innerHTML = `<div class="alert alert-danger">Failed to connect to AI Lab.</div>`;
            } finally {
                btn.disabled = false;
            }
        }
    </script>
</body>

</html>