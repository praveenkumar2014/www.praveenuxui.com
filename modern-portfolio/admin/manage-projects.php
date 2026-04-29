<?php include 'includes/config.php'; 

// Handle Deletion
if(isset($_GET['delete'])) {
    $stmt = $db->prepare('DELETE FROM projects WHERE id = :id');
    $stmt->bindValue(':id', $_GET['delete'], SQLITE3_INTEGER);
    $stmt->execute();
    header('Location: manage-projects.php');
}

// Handle Form Submission (Add/Edit)
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $stmt = $db->prepare('UPDATE projects SET title = :title, category = :cat, link = :link, image = :img, description = :desc WHERE id = :id');
        $stmt->bindValue(':id', $_POST['id'], SQLITE3_INTEGER);
    } else {
        // Insert
        $stmt = $db->prepare('INSERT INTO projects (title, category, link, image, description) VALUES (:title, :cat, :link, :img, :desc)');
    }
    
    $stmt->bindValue(':title', $_POST['title'], SQLITE3_TEXT);
    $stmt->bindValue(':cat', $_POST['category'], SQLITE3_TEXT);
    $stmt->bindValue(':link', $_POST['link'], SQLITE3_TEXT);
    $stmt->bindValue(':img', $_POST['image'], SQLITE3_TEXT);
    $stmt->bindValue(':desc', $_POST['description'] ?? '', SQLITE3_TEXT);
    $stmt->execute();
    header('Location: manage-projects.php');
}

$projects = $db->query('SELECT * FROM projects ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Projects | Madmin</title>
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
        .glass-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .glass-table tr { background: rgba(255,255,255,0.02); }
        .glass-table td, .glass-table th { padding: 15px; border: 1px solid rgba(255,255,255,0.05); border-width: 1px 0; }
        .glass-table td:first-child, .glass-table th:first-child { border-left-width: 1px; border-radius: 12px 0 0 12px; }
        .glass-table td:last-child, .glass-table th:last-child { border-right-width: 1px; border-radius: 0 12px 12px 0; }
        .form-control { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; border-radius: 10px; padding: 10px; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="font-size: 28px; font-weight: 700;">Manage Projects</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal" onclick="resetForm()">+ Add New Project</button>
        </div>

        <table class="glass-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $projects->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img loading="lazy" src="<?php echo $row['image']; ?>" width="40" height="40" style="border-radius: 8px; object-fit: cover;">
                            <span><?php echo $row['title']; ?></span>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary"><?php echo $row['category']; ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-info me-2" onclick='editProject(<?php echo json_encode($row); ?>)'><i class="fas fa-edit"></i></button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
                <form method="POST">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="modalTitle">Add New Project</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="projectId">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="projectTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" id="projectCategory" class="form-control" placeholder="e.g. Product Design">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="text" name="image" id="projectImage" class="form-control" placeholder="assets/img/projects/...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Project Link</label>
                            <input type="text" name="link" id="projectLink" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="projectDescription" class="form-control" rows="4" placeholder="1–3 lines about the problem, approach, and outcome."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function resetForm() {
            document.getElementById('modalTitle').innerText = 'Add New Project';
            document.getElementById('projectId').value = '';
            document.getElementById('projectTitle').value = '';
            document.getElementById('projectCategory').value = '';
            document.getElementById('projectImage').value = '';
            document.getElementById('projectLink').value = '';
            document.getElementById('projectDescription').value = '';
        }
        function editProject(project) {
            document.getElementById('modalTitle').innerText = 'Edit Project';
            document.getElementById('projectId').value = project.id;
            document.getElementById('projectTitle').value = project.title;
            document.getElementById('projectCategory').value = project.category;
            document.getElementById('projectImage').value = project.image;
            document.getElementById('projectLink').value = project.link;
            document.getElementById('projectDescription').value = project.description || '';
            new bootstrap.Modal(document.getElementById('addProjectModal')).show();
        }
    </script>
</body>
</html>
