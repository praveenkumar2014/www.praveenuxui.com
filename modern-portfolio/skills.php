<?php 
include 'header.php'; 
require_once 'includes/db_fetch.php';
$all_skills = fetch_skills();

function skill_icon_fallback_url(string $name): string {
    // SimpleIcons slugs are lowercase; spaces removed. Some brands use special slugs.
    $map = [
        'Next.js' => 'nextdotjs',
        'TypeScript' => 'typescript',
        'Hugging Face' => 'huggingface',
        'OpenAI' => 'openai',
        'GitHub' => 'github',
        'Tailwind' => 'tailwindcss',
        'LangChain' => 'langchain',
        'Supabase' => 'supabase',
        'Vercel' => 'vercel',
        'React' => 'react',
        'Python' => 'python',
        'Figma' => 'figma',
        'Adobe' => 'adobe',
        'Anthropic' => 'anthropic',
    ];
    $slug = $map[$name] ?? strtolower(preg_replace('/[^a-z0-9]+/i', '', $name));
    return "https://cdn.simpleicons.org/{$slug}";
}
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
        <div class="skill-card reveal-up text-center">
            <div class="skill-icon">
                <img
                    loading="lazy"
                    decoding="async"
                    src="<?php echo htmlspecialchars($skill['icon_url'] ?? '', ENT_QUOTES); ?>"
                    data-fallback="<?php echo htmlspecialchars(skill_icon_fallback_url($skill['name'] ?? ''), ENT_QUOTES); ?>"
                    alt="<?php echo htmlspecialchars($skill['name'] ?? '', ENT_QUOTES); ?>"
                    onerror="this.onerror=null; this.src=this.dataset.fallback;"
                >
            </div>
            <span class="small fw-bold text-secondary"><?php echo htmlspecialchars($skill['name'] ?? '', ENT_QUOTES); ?></span>
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
.skills-grid { align-items: stretch; }
.skill-icon { width: 24px; height: 24px; transition: width 0.2s ease, height 0.2s ease; }
.skill-card:hover .skill-icon { width: 32px; height: 32px; }
.skill-card { height: 100%; padding: 1rem 0.5rem 0.7rem; }
.skill-card span { font-size: 11px; }
</style>

<?php include 'footer.php'; ?>
