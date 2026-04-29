<?php
$sidebar_menu = [
    [
        'title' => 'Dashboard',
        'icon'  => 'fas fa-th-large',
        'url'   => 'dashboard.php',
        'roles' => ['admin', 'super_admin']
    ],
    [
        'title' => 'Projects',
        'icon'  => 'fas fa-briefcase',
        'url'   => 'manage-projects.php',
        'roles' => ['admin', 'super_admin']
    ],
    [
        'title' => 'Blogs',
        'icon'  => 'fas fa-blog',
        'url'   => 'manage-blogs.php',
        'roles' => ['admin', 'super_admin']
    ],
    [
        'title' => 'Skills & Icons',
        'icon'  => 'fas fa-tools',
        'url'   => 'manage-skills.php',
        'roles' => ['admin', 'super_admin']
    ],
    [
        'title' => 'AI Job Agent',
        'icon'  => 'fas fa-robot',
        'url'   => 'ai-job-agent.php',
        'roles' => ['super_admin'],
        'class' => 'text-info'
    ],
    [
        'title' => 'AI Asset Lab',
        'icon'  => 'fas fa-wand-magic-sparkles',
        'url'   => 'ai-asset-lab.php',
        'roles' => ['super_admin'],
        'class' => 'text-warning'
    ],
    [
        'title' => 'Social Connect',
        'icon'  => 'fas fa-share-alt',
        'url'   => 'social-connect.php',
        'roles' => ['super_admin']
    ],
    [
        'title' => 'Settings',
        'icon'  => 'fas fa-cog',
        'url'   => 'settings.php',
        'roles' => ['super_admin']
    ]
];

$current_role = $_SESSION['admin_role'] ?? 'admin';
$current_file = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar d-flex flex-column">
    <div class="logo-text mb-5" style="font-size: 22px; font-weight: 900; letter-spacing: -0.02em;">
        Praveen<span style="color:#4770FF;">UXUI</span>
        <span class="text-secondary" style="font-weight:900;">·</span>
        M<span style="color:#4770FF;">admin</span>
    </div>
    <nav class="flex-grow-1">
        <?php foreach($sidebar_menu as $item): ?>
            <?php if(in_array($current_role, $item['roles'])): ?>
                <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo ($current_file == $item['url']) ? 'active' : ''; ?> <?php echo $item['class'] ?? ''; ?>">
                    <i class="<?php echo $item['icon']; ?>"></i> <?php echo $item['title']; ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
    <div class="user-info mb-3 px-3 small text-secondary">
        Logged in as: <strong><?php echo $_SESSION['admin_username']; ?></strong> (<?php echo str_replace('_', ' ', $current_role); ?>)
    </div>
    <a href="logout.php" class="nav-link btn-logout text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
