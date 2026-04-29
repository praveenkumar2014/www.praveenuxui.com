<?php include 'includes/config.php'; 

// Handle Deletion
if(isset($_GET['delete'])) {
    $stmt = $db->prepare('DELETE FROM skills WHERE id = :id');
    $stmt->bindValue(':id', $_GET['delete'], SQLITE3_INTEGER);
    $stmt->execute();
    header('Location: manage-skills.php');
}

// Handle Form Submission (Add/Edit)
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $stmt = $db->prepare('UPDATE skills SET name = :name, icon_url = :icon WHERE id = :id');
        $stmt->bindValue(':id', $_POST['id'], SQLITE3_INTEGER);
    } else {
        $stmt = $db->prepare('INSERT INTO skills (name, icon_url) VALUES (:name, :icon)');
    }
    
    $stmt->bindValue(':name', $_POST['name'], SQLITE3_TEXT);
    $stmt->bindValue(':icon', $_POST['icon_url'], SQLITE3_TEXT);
    $stmt->execute();
    header('Location: manage-skills.php');
}

$skills = $db->query('SELECT * FROM skills ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Skills | Madmin</title>
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
            <h1 style="font-size: 28px; font-weight: 700;">Manage Skills</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal" onclick="resetForm()">+ Add New Skill</button>
        </div>

        <table class="glass-table">
            <thead>
                <tr>
                    <th>Skill Name</th>
                    <th>Icon</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $skills->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <img loading="lazy" src="<?php echo $row['icon_url']; ?>" width="30" height="30" style="object-fit: contain;">
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info me-2" onclick='editSkill(<?php echo json_encode($row); ?>)'><i class="fas fa-edit"></i></button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addSkillModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
                <form method="POST">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="modalTitle">Add New Skill</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="skillId">
                        <div class="mb-3">
                            <label class="form-label">Skill Name</label>
                            <input type="text" name="name" id="skillName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon URL</label>
                            <input type="text" name="icon_url" id="skillIcon" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Skill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function resetForm() {
            document.getElementById('modalTitle').innerText = 'Add New Skill';
            document.getElementById('skillId').value = '';
            document.getElementById('skillName').value = '';
            document.getElementById('skillIcon').value = '';
        }
        function editSkill(skill) {
            document.getElementById('modalTitle').innerText = 'Edit Skill';
            document.getElementById('skillId').value = skill.id;
            document.getElementById('skillName').value = skill.name;
            document.getElementById('skillIcon').value = skill.icon_url;
            new bootstrap.Modal(document.getElementById('addSkillModal')).show();
        }
    </script>
</body>
</html>
