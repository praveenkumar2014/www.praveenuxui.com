<?php
require_once 'auth.php';
require_login();

include 'header.php';
?>
<main>
    <section class="content-box-area mt-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-3">
                    <div class="card content-box-card">
                        <div class="card-body">
                            <h4 class="mb-4">Admin Menu</h4>
                            <ul class="list-unstyled">
                                <li class="mb-3"><a href="admin.php" class="text-primary"><i class="fas fa-home me-2"></i> Dashboard</a></li>
                                <li class="mb-3"><a href="index.php"><i class="fas fa-eye me-2"></i> View Site</a></li>
                                <li class="mb-3"><a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card content-box-card">
                        <div class="card-body">
                            <div class="top-info mb-4">
                                <h1 class="main-title">Welcome, <span>Super Admin</span></h1>
                                <p>This is your private dashboard for managing portfolio settings and inquiries.</p>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="stat-card" style="padding: 20px; border: 1px solid #eee; border-radius: 10px;">
                                        <h5>Total Views</h5>
                                        <h2 class="number">1,286</h2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card" style="padding: 20px; border: 1px solid #eee; border-radius: 10px;">
                                        <h5>Recent Inquiries</h5>
                                        <h2 class="number">12</h2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card" style="padding: 20px; border: 1px solid #eee; border-radius: 10px;">
                                        <h5>Active Projects</h5>
                                        <h2 class="number">24</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <h3>Quick Actions</h3>
                                <div class="d-flex gap-3 mt-3">
                                    <button class="submit-btn" style="width: auto;">Add New Project</button>
                                    <button class="submit-btn" style="width: auto; background: #6c757d;">Edit Profile</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
