<?php 
include 'header.php'; 
require_once 'includes/db_fetch.php';
$all_skills = fetch_skills();
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
<div class="card-body">
<div class="top-info">
    <div class="text">
        <h1 class="main-title">Specialized <span>Skills</span></h1>
    </div>
    <div class="available-btn"><span><i class="fas fa-circle text-success"></i> Available For Hire</span></div>
</div>

<div class="skills-page mt-4" id="skills-page">
    <?php 
    $current_cat = '';
    foreach($all_skills as $skill): 
        if($skill['category'] !== $current_cat):
            if($current_cat !== '') echo '</div></div>'; // close previous category
            $current_cat = $skill['category'];
            echo '<div class="skill-category mb-5">';
            echo '<h2 class="main-common-title skill-cat-title mb-4">' . $current_cat . '</h2>';
            echo '<div class="row g-3 skills-grid">';
        endif;
    ?>
    <div class="col-xl-2 col-md-3 col-sm-4 col-4">
        <div class="skill-card reveal-up text-center p-3" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; transition: 0.3s;">
            <div class="skill-icon mb-2" style="height: 40px; display: flex; align-items: center; justify-content: center;">
                <img loading="lazy" src="<?php echo $skill['icon_url']; ?>" alt="<?php echo $skill['name']; ?>" style="max-height: 100%; max-width: 100%;">
            </div>
            <span class="small fw-bold text-secondary" style="font-size: 11px;"><?php echo $skill['name']; ?></span>
        </div>
    </div>
    <?php endforeach; if($current_cat !== '') echo '</div></div>'; ?>

    <!-- CTA Slider -->
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
</div>
</section>
</main>

<style>
.skill-card:hover { background: rgba(71, 112, 255, 0.05) !important; border-color: rgba(71, 112, 255, 0.2) !important; transform: translateY(-5px); }
.skill-cat-title { font-size: 18px; font-weight: 700; color: #fff; border-left: 3px solid #4770FF; padding-left: 15px; }
</style>

<?php include 'footer.php'; ?>
