<?php
require_once 'auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = 'Invalid credentials. Please try again.';
    }
}

if (is_logged_in()) {
    header("Location: admin.php");
    exit();
}

include 'header.php';
?>
<main>
    <section class="content-box-area mt-4">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-xl-5">
                    <div class="card content-box-card">
                        <div class="card-body contact-card">
                            <div class="top-info text-center mb-4">
                                <h1 class="main-title">Admin <span>Login</span></h1>
                                <p>Access restricted to authorized personnel only.</p>
                            </div>
                            <form method="POST" action="login.php">
                                <?php if ($error): ?>
                                    <div class="alert alert-danger" style="background: rgba(255,0,0,0.1); border: 1px solid red; color: red; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                                        <?php echo $error; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="mb-4">
                                    <label class="form-label">Username</label>
                                    <input name="username" required type="text" class="form-control shadow-none" placeholder="Enter username">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Password</label>
                                    <input name="password" required type="password" class="form-control shadow-none" placeholder="Enter password">
                                </div>
                                <button class="submit-btn w-100" type="submit">
                                    Login to Dashboard
                                    <svg class="icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.5 11.6665V6.6665H12.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M17.5 6.6665L10 14.1665L2.5 6.6665" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
