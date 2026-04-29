<?php include 'includes/config.php'; 

// Handle Deletion
if(isset($_GET['delete'])) {
    $stmt = $db->prepare('DELETE FROM blogs WHERE id = :id');
    $stmt->bindValue(':id', $_GET['delete'], SQLITE3_INTEGER);
    $stmt->execute();
    header('Location: manage-blogs.php');
}

// Handle Form Submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $stmt = $db->prepare('UPDATE blogs SET title = :title, category = :cat, content = :content, image = :img, date = :date WHERE id = :id');
        $stmt->bindValue(':id', $_POST['id'], SQLITE3_INTEGER);
    } else {
        $stmt = $db->prepare('INSERT INTO blogs (title, category, content, image, date) VALUES (:title, :cat, :content, :img, :date)');
    }
    
    $stmt->bindValue(':title', $_POST['title'], SQLITE3_TEXT);
    $stmt->bindValue(':cat', $_POST['category'], SQLITE3_TEXT);
    $stmt->bindValue(':content', $_POST['content'], SQLITE3_TEXT);
    $stmt->bindValue(':img', $_POST['image'], SQLITE3_TEXT);
    $stmt->bindValue(':date', $_POST['date'], SQLITE3_TEXT);
    $stmt->execute();
    header('Location: manage-blogs.php');
}

$blogs = $db->query('SELECT * FROM blogs ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Blogs | Madmin</title>
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
    <div class="sidebar d-flex flex-column">
        <div class="logo-text mb-5" style="font-size: 24px; font-weight: 800;">M<span>admin</span></div>
        <nav class="flex-grow-1">
            <a href="dashboard" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="manage-projects" class="nav-link"><i class="fas fa-briefcase"></i> Projects</a>
            <a href="manage-blogs" class="nav-link active"><i class="fas fa-blog"></i> Blogs</a>
            <a href="manage-services" class="nav-link"><i class="fas fa-magic"></i> Services</a>
            <a href="settings" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
        </nav>
        <a href="logout" class="nav-link btn-logout text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="font-size: 28px; font-weight: 700;">Manage Blogs</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#blogModal" onclick="resetForm()">+ Write New Post</button>
        </div>

        <table class="glass-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $blogs->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><span class="badge bg-secondary"><?php echo $row['category']; ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-info me-2" onclick='editBlog(<?php echo json_encode($row); ?>)'><i class="fas fa-edit"></i></button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="blogModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
                <form method="POST">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="modalTitle">Blog Post</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="blogId">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Post Title</label>
                                    <input type="text" name="title" id="blogTitle" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="text" name="date" id="blogDate" class="form-control" placeholder="March 25, 2024">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <input type="text" name="category" id="blogCategory" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image URL</label>
                                    <input type="text" name="image" id="blogImage" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Content (HTML allowed)</label>
                                    <textarea name="content" id="blogContent" class="form-control" rows="8"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Publish Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function resetForm() {
            document.getElementById('modalTitle').innerText = 'Write New Post';
            document.getElementById('blogId').value = '';
            document.getElementById('blogTitle').value = '';
            document.getElementById('blogDate').value = '';
            document.getElementById('blogCategory').value = '';
            document.getElementById('blogImage').value = '';
            document.getElementById('blogContent').value = '';
        }
        function editBlog(blog) {
            document.getElementById('modalTitle').innerText = 'Edit Post';
            document.getElementById('blogId').value = blog.id;
            document.getElementById('blogTitle').value = blog.title;
            document.getElementById('blogDate').value = blog.date;
            document.getElementById('blogCategory').value = blog.category;
            document.getElementById('blogImage').value = blog.image;
            document.getElementById('blogContent').value = blog.content;
            new bootstrap.Modal(document.getElementById('blogModal')).show();
        }
    </script>
</body>
</html>
