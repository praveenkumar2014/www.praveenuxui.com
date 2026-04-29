<?php include 'header.php'; ?>
<main>
<section class="content-box-area mt-4">
<div class="container">
<div class="row g-4">
<div class="col-xl-4">
<div class="card profile-card">
<div class="card-body">
<div class="image text-center">
<img loading="lazy" src="assets/img/images/profile.png" alt="profile">
</div>
<div class="text">
<h3 class="card-title">Praveen Kumar K 👋</h3>
<p>Senior UX/UI Architect &amp; <span class="ai-badge">✦ AI Agentic</span> Design Strategist. Book an appointment for consultation or project inquiries.</p>
<div class="common-button-groups">
<a class="btn btn-call" href="https://wa.me/918884263999?text=Hi+Praveen%2C+I+would+like+to+book+an+appointment+for+a+design+consultation." target="_blank">
    <i class="fab fa-whatsapp"></i> WhatsApp Me
</a>
</div>
<div class="social-media-icon">
<ul class="list-unstyled">
<li><a href="https://www.facebook.com/pranu21m/" target="_blank"><i class="fab fa-facebook"></i></a></li>
<li><a href="https://www.linkedin.com/in/praveenkumarkanneganti/" target="_blank"><i class="fab fa-linkedin"></i></a></li>
<li><a href="https://www.behance.net/praveen-ui-ux" target="_blank"><i class="fab fa-behance"></i></a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div class="col-xl-8">
<div class="card content-box-card">
<div class="card-body">
<div class="top-info">
<div class="text">
<h1 class="main-title">Book An <span>Appointment</span></h1>
</div>
<div class="available-btn"><span><i class="fas fa-circle"></i> Instant WhatsApp Alert</span></div>
</div>

<div class="booking-section mt-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="booking-card reveal-up">
                <div class="icon-box"><i data-lucide="calendar"></i></div>
                <h3>Standard Consultation</h3>
                <p>30-minute discovery call to discuss your project, strategy, or design requirements.</p>
                <a href="https://wa.me/918884263999?text=Hi+Praveen%2C+I+want+to+book+a+30-min+Consultation." class="btn btn-primary w-100 mt-3">Book via WhatsApp</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="booking-card reveal-up">
                <div class="icon-box"><i data-lucide="zap"></i></div>
                <h3>Strategy Session</h3>
                <p>60-minute deep dive into UX strategy, AI agentic design, or system architecture.</p>
                <a href="https://wa.me/918884263999?text=Hi+Praveen%2C+I+want+to+book+a+60-min+Strategy+Session." class="btn btn-primary w-100 mt-3">Book via WhatsApp</a>
            </div>
        </div>
    </div>

    <div class="booking-calendar mt-5 reveal-up">
        <h2 class="main-common-title">Preferred Time Slots</h2>
        <div class="time-slots">
            <div class="slot">Mon-Fri: 10:00 AM - 06:00 PM (IST)</div>
            <div class="slot">Saturday: By Appointment Only</div>
            <div class="slot">Sunday: Closed</div>
        </div>
        <p class="mt-3 text-muted">Note: Clicking "Book via WhatsApp" will instantly notify me on my phone.</p>
    </div>
</div>

<style>
.booking-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--card-border);
    padding: 2rem;
    border-radius: 24px;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
}
.booking-card:hover {
    background: var(--accent-glow);
    border-color: var(--accent);
    transform: translateY(-5px);
}
.booking-card .icon-box {
    width: 60px;
    height: 60px;
    background: rgba(71, 112, 255, 0.1);
    color: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}
.booking-card h3 { font-size: 1.25rem; margin-bottom: 1rem; }
.booking-card p { font-size: 0.9rem; color: var(--text-dim); }

.time-slots {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 1.5rem;
}
.slot {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: 12px;
    font-weight: 500;
}
</style>

</div>
</div>
</div>
</div>
</div>
</section>
</main>
<?php include 'footer.php'; ?>
