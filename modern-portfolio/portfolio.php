<?php 
include 'header.php'; 
require_once 'includes/db_fetch.php';
$all_projects = fetch_projects();
?>
<main>
<section class="content-box-area mt-4">
<div class="container">
<div class="row g-4">
<div class="col-xl-4">
<?php include 'includes/profile_card.php'; ?>
</div>
<div class="col-xl-8">
<div class="card content-box-card">
<div class="card-body portUXUI-card">
<div class="top-info mb-4">
    <div class="text">
        <h1 class="main-title">Check Out My Latest <span>Projects Works</span></h1>
        <p>I am a UX/UI Architect with over 18 years of end-to-end digital experience and 8+ years in leadership and delivery management roles, consistently translating complex business requirements into meaningful, intuitive, and scalable user experiences.</p>
    </div>
</div>

<div class="portUXUI-area">
    <div class="row g-4 parent-container">
    <?php if(empty($all_projects)): ?>
        <div class="col-12 text-center py-5"><p class="text-secondary">No projects discovered yet. Managing via Madmin...</p></div>
    <?php else: ?>
        <?php foreach($all_projects as $p): ?>
        <div class="col-lg-6 portfolio-item-wrapper">
            <div class="portUXUI-item">
                <div class="image">
                    <img loading="lazy" src="<?php echo $p['image']; ?>" alt="<?php echo $p['title']; ?>" class="img-fluid w-100">
                    <a href="<?php echo $p['image']; ?>" class="gallery-popup full-image-preview parent-container">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                            <path d="M10 4.167v11.666M4.167 10h11.666"></path>
                        </svg>
                    </a>
                </div>
                <div class="text">
                    <div class="info">
                        <a href="<?php echo $p['link']; ?>" target="_blank" class="title"><?php echo $p['title']; ?></a>
                        <p class="subtitle"><?php echo $p['category']; ?></p>
                    </div>
                    <div class="visite-btn">
                        <a href="<?php echo $p['link']; ?>" target="_blank">Visit Site
                            <svg class="arrow-up" width="14" height="15" viewBox="0 0 14 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.91634 4.5835L4.08301 10.4168" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M4.66699 4.5835H9.91699V9.8335" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>

<div class="work-together-slider mt-5">
    <div class="slider-main d-flex gap-4 align-items-center">
        <div class="slider-item">
            <a href="contact">Let's 👋 Work Together</a>
            <a href="contact">Let's 👋 Work Together</a>
        </div>
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