document.addEventListener('DOMContentLoaded', () => {
    loadSkills();
    loadProjects();
    loadPortfolioProjects();
    initUxLightbox();
    initCvGate();
    initCalBookingLightbox();
});

function escapeHtml(str) {
    return String(str ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function isExternalUrl(url) {
    try {
        const u = new URL(url, window.location.href);
        return u.origin !== window.location.origin;
    } catch {
        return false;
    }
}

function ensureProjectModal() {
    if (document.getElementById('projectModal')) return;

    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'projectModal';
    modal.tabIndex = -1;
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML = `
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: rgba(10, 10, 10, 0.92); border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; backdrop-filter: blur(18px);">
          <div class="modal-header border-0">
            <div>
              <h5 class="modal-title mb-1" id="projectModalTitle" style="font-weight:800;"></h5>
              <div class="text-secondary small" id="projectModalMeta"></div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body pt-0">
            <div class="ratio ratio-16x9 rounded-4 overflow-hidden mb-3" style="background: rgba(255,255,255,0.04);">
              <img id="projectModalImage" src="" alt="" style="width:100%; height:100%; object-fit: cover;">
            </div>
            <p class="mb-0" id="projectModalDescription" style="color: rgba(255,255,255,0.82); line-height:1.6;"></p>
          </div>
          <div class="modal-footer border-0 pt-0">
            <a class="btn btn-outline-light" id="projectModalOpenImage" href="#" target="_blank" rel="noopener">Open image</a>
            <a class="btn btn-primary" id="projectModalVisit" href="#" target="_blank" rel="noopener">Visit</a>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
}

function initUxLightbox() {
    if (window.UXLightbox) return;

    const overlay = document.createElement('div');
    overlay.id = 'ux-lightbox';
    overlay.className = 'glassy-modal-overlay glassy-modal';
    overlay.innerHTML = `
      <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="ux-lightbox-title">
        <div class="modal-icon" id="ux-lightbox-icon" aria-hidden="true"></div>
        <h3 id="ux-lightbox-title"></h3>
        <p id="ux-lightbox-message"></p>
        <button type="button" id="ux-lightbox-close">Close</button>
      </div>
    `;
    document.body.appendChild(overlay);

    const close = () => overlay.classList.remove('visible');
    overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
    overlay.querySelector('#ux-lightbox-close').addEventListener('click', close);

    const iconEl = overlay.querySelector('#ux-lightbox-icon');
    const titleEl = overlay.querySelector('#ux-lightbox-title');
    const msgEl = overlay.querySelector('#ux-lightbox-message');

    window.UXLightbox = {
        show: ({ title, message, variant } = {}) => {
            const v = variant || 'success';
            const isSuccess = v === 'success';
            iconEl.innerHTML = isSuccess
                ? `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>`
                : `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>`;
            iconEl.style.background = isSuccess ? 'var(--accent-glow)' : 'rgba(239,68,68,0.15)';
            iconEl.style.color = isSuccess ? 'var(--accent)' : 'rgb(239,68,68)';
            titleEl.textContent = title || (isSuccess ? 'Done' : 'Something went wrong');
            msgEl.textContent = message || '';
            overlay.classList.add('visible');
        }
    };
}

function ensureCvModal() {
    if (document.getElementById('cvGateModal')) return;

    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'cvGateModal';
    modal.tabIndex = -1;
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML = `
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content ux-modal-surface">
          <div class="modal-header border-0">
            <div>
              <h5 class="modal-title mb-1" style="font-weight:900;">Download CV</h5>
              <div class="text-secondary small">Quick verification before download (stored for hiring follow-up).</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body pt-0">
            <form id="cv-gate-form" action="admin/api/cv_access.php" method="POST" class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full name</label>
                <input class="form-control shadow-none" name="name" required placeholder="Your name">
              </div>
              <div class="col-md-6">
                <label class="form-label">Work email</label>
                <input class="form-control shadow-none" type="email" name="email" required placeholder="name@company.com">
              </div>
              <div class="col-md-6">
                <label class="form-label">Company</label>
                <input class="form-control shadow-none" name="company" required placeholder="Company / Client">
              </div>
              <div class="col-md-6">
                <label class="form-label">Role</label>
                <input class="form-control shadow-none" name="role" placeholder="Recruiter / HM / Founder">
              </div>
              <div class="col-md-6">
                <label class="form-label">WhatsApp / Phone (optional)</label>
                <input class="form-control shadow-none" name="phone" placeholder="+91…">
              </div>
              <div class="col-md-6">
                <label class="form-label">LinkedIn (optional)</label>
                <input class="form-control shadow-none" name="linkedin" placeholder="https://linkedin.com/in/...">
              </div>
              <div class="col-12">
                <label class="form-label">What are you hiring for?</label>
                <textarea class="form-control shadow-none" name="reason" rows="3" required placeholder="e.g., Senior Product Designer / UX Architect / Agentic AI UX…"></textarea>
              </div>
              <input type="hidden" name="source" value="">
              <div class="col-12 d-flex gap-2 align-items-center">
                <button type="submit" class="btn btn-primary" id="cv-gate-submit" style="font-weight:800;">Notify & Download</button>
                <div class="small text-secondary">I’ll be notified on WhatsApp + Telegram before download.</div>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
}

function initCvGate() {
    ensureCvModal();
    const fileHref = '/assets/Praveen_Kumar_K_Resume.pdf';

    // Intercept any existing "Download CV" links across pages.
    document.addEventListener('click', async (e) => {
        const a = e.target.closest('a');
        if (!a) return;
        const href = a.getAttribute('href') || '';
        if (!href.includes('Praveen_Kumar_K_Resume.pdf')) return;
        e.preventDefault();
        // eslint-disable-next-line no-undef
        const modal = new bootstrap.Modal(document.getElementById('cvGateModal'));
        const form = document.getElementById('cv-gate-form');
        form.querySelector('input[name="source"]').value = window.location.pathname;
        modal.show();
    }, { passive: false });

    const form = document.getElementById('cv-gate-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('cv-gate-submit');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Notifying…';
        }

        try {
            const res = await fetch(form.action, { method: 'POST', body: new FormData(form) });
            const payload = await res.json();
            if (!payload || !payload.success) {
                throw new Error(payload?.error || 'Verification failed');
            }

            // Close modal then download.
            const modalEl = document.getElementById('cvGateModal');
            // eslint-disable-next-line no-undef
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            if (window.UXLightbox) {
                window.UXLightbox.show({
                    title: 'Verified',
                    message: 'Download starting now. Thanks — I’ll reach out if I can help your hiring pipeline move faster.',
                    variant: 'success'
                });
            }

            // Trigger download in new tab (keeps current page).
            const url = payload.downloadUrl || fileHref;
            window.open(url, '_blank', 'noopener');
            form.reset();
        } catch (err) {
            console.error('CV gate failed:', err);
            if (window.UXLightbox) {
                window.UXLightbox.show({
                    title: 'Could not verify',
                    message: String(err?.message || 'Please try again.'),
                    variant: 'error'
                });
            } else {
                alert('Could not verify. Please try again.');
            }
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Notify & Download';
            }
        }
    });
}

function ensureCalModal() {
    if (document.getElementById('calBookingModal')) return;
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'calBookingModal';
    modal.tabIndex = -1;
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML = `
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content ux-modal-surface">
          <div class="modal-header border-0">
            <div>
              <h5 class="modal-title mb-1" style="font-weight:900;">Book an appointment</h5>
              <div class="text-secondary small">Choose a slot on Cal.com (opens inside this lightbox).</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body pt-0">
            <div class="ratio ratio-16x9 rounded-4 overflow-hidden" style="background: rgba(255,255,255,0.04); min-height: 560px;">
              <iframe id="calBookingFrame" title="Cal booking" src="" style="width:100%; height:100%; border:0;"></iframe>
            </div>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
}

function initCalBookingLightbox() {
    ensureCalModal();
    const username = 'praveenkumar-kanneganti';
    const defaultUrl = `https://cal.com/${encodeURIComponent(username)}`;

    // Intercept any Cal.com triggers to show lightbox iframe.
    document.addEventListener('click', (e) => {
        const calBtn = e.target.closest('[data-cal-link]');
        if (!calBtn) return;
        e.preventDefault();
        const link = calBtn.getAttribute('data-cal-link') || username;
        const frame = document.getElementById('calBookingFrame');
        frame.src = `https://cal.com/${encodeURIComponent(link)}`;
        // eslint-disable-next-line no-undef
        const modal = new bootstrap.Modal(document.getElementById('calBookingModal'));
        modal.show();
    }, { passive: false });

    // Intercept "Book A call" anchors (legacy) to show lightbox.
    document.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (!a) return;
        const text = (a.textContent || '').toLowerCase();
        const href = (a.getAttribute('href') || '').toLowerCase();
        const isBook =
            text.includes('book a call') ||
            href.includes('#booking-section') ||
            href === 'contact' || href.endsWith('/contact');
        if (!isBook) return;

        // Only override when link is clearly intended as booking.
        if (!(href.includes('#booking-section'))) return;
        e.preventDefault();
        const frame = document.getElementById('calBookingFrame');
        frame.src = defaultUrl;
        // eslint-disable-next-line no-undef
        const modal = new bootstrap.Modal(document.getElementById('calBookingModal'));
        modal.show();
    }, { passive: false });
}

function openProjectModal(project) {
    ensureProjectModal();
    const titleEl = document.getElementById('projectModalTitle');
    const metaEl = document.getElementById('projectModalMeta');
    const imgEl = document.getElementById('projectModalImage');
    const descEl = document.getElementById('projectModalDescription');
    const visitEl = document.getElementById('projectModalVisit');
    const openImgEl = document.getElementById('projectModalOpenImage');

    const title = project?.title || 'Project';
    const category = project?.category || '';
    const desc = project?.description || '';
    const image = project?.image || 'assets/img/projects/myprofile.png';
    const link = project?.link || '#';

    titleEl.textContent = title;
    metaEl.textContent = category;
    imgEl.src = image;
    imgEl.alt = title;
    imgEl.onerror = () => { imgEl.onerror = null; imgEl.src = 'assets/img/projects/myprofile.png'; };
    descEl.textContent = desc;

    openImgEl.href = image;

    if (link && link !== '#') {
        visitEl.href = link;
        visitEl.style.display = '';
        visitEl.target = isExternalUrl(link) ? '_blank' : '_self';
        visitEl.rel = isExternalUrl(link) ? 'noopener' : '';
    } else {
        visitEl.href = '#';
        visitEl.style.display = 'none';
    }

    // Bootstrap 5 modal (already included on pages)
    // eslint-disable-next-line no-undef
    const modal = new bootstrap.Modal(document.getElementById('projectModal'));
    modal.show();
}

async function loadSkills() {
    const container = document.getElementById('skills-container');
    if (!container) return;

    // Show skeletons
    container.innerHTML = Array(8).fill(0).map(() => `
        <div class="col-xl-3 col-md-3 col-sm-6 col-6">
            <div class="expertise-item">
                <div class="image text-center skeleton skeleton-circle mx-auto"></div>
                <div class="text mt-2">
                    <div class="skeleton skeleton-text mx-auto" style="width: 60%"></div>
                </div>
            </div>
        </div>
    `).join('');

    try {
        const res = await fetch('admin/api/skills.php');
        const skills = await res.json();
        
        container.innerHTML = skills.map(skill => `
            <div class="col-xl-3 col-md-3 col-sm-6 col-6">
                <div class="expertise-item">
                    <div class="image text-center">
                        <img loading="lazy" src="${skill.icon_url}" alt="${skill.name}">
                    </div>
                    <div class="text">
                        <h4 class="title">${skill.name}</h4>
                    </div>
                </div>
            </div>
        `).join('');
    } catch (err) {
        console.error('Failed to load skills:', err);
    }
}

async function loadProjects() {
    const container = document.getElementById('projects-container');
    if (!container) return;

    // Show skeletons
    container.innerHTML = Array(3).fill(0).map(() => `
        <div class="col-lg-12">
            <div class="project-item">
                <div class="image skeleton skeleton-img"></div>
                <div class="info mt-2">
                    <div class="skeleton skeleton-text" style="width: 40%"></div>
                </div>
            </div>
        </div>
    `).join('');

    try {
        const res = await fetch('admin/api/projects.php?limit=4');
        const projects = await res.json();
        
        container.innerHTML = projects.map((proj) => `
            <div class="col-lg-12">
                <div class="project-item">
                    <div class="image">
                        <a href="javascript:void(0)" class="js-project-details d-block" data-project-id="${escapeHtml(proj.id)}" aria-label="Open project details">
                          <img loading="lazy" src="${escapeHtml(proj.image)}" alt="${escapeHtml(proj.title)}" class="img-fluid w-100" onerror="this.onerror=null;this.src='assets/img/projects/myprofile.png'">
                        </a>
                        <div class="info">
                          <span class="category">${escapeHtml(proj.category)}</span>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        container.addEventListener('click', (e) => {
            const btn = e.target.closest('.js-project-details');
            if (!btn) return;
            e.preventDefault();
            const id = btn.getAttribute('data-project-id');
            const project = projects.find(p => String(p.id) === String(id));
            if (project) openProjectModal(project);
        }, { passive: false });
    } catch (err) {
        console.error('Failed to load projects:', err);
    }
}

async function loadPortfolioProjects() {
    const container = document.getElementById('portfolio-projects');
    if (!container) return;

    // Skeletons
    container.innerHTML = Array(8).fill(0).map(() => `
        <div class="col-lg-6 portfolio-item-wrapper">
          <div class="portUXUI-item">
            <div class="image skeleton skeleton-img" style="border-radius: 18px; height: 240px;"></div>
            <div class="text mt-3">
              <div class="skeleton skeleton-text" style="width: 60%"></div>
              <div class="skeleton skeleton-text mt-2" style="width: 35%"></div>
            </div>
          </div>
        </div>
    `).join('');

    try {
        const res = await fetch('admin/api/projects.php?limit=0');
        const projects = await res.json();

        if (!Array.isArray(projects) || projects.length === 0) {
            container.innerHTML = `
              <div class="col-12">
                <div class="alert alert-secondary bg-transparent border" style="border-color: rgba(255,255,255,0.12) !important; color: rgba(255,255,255,0.8); border-radius: 16px;">
                  No projects found yet. Add projects from the admin panel.
                </div>
              </div>
            `;
            return;
        }

        container.innerHTML = projects.map((proj) => {
            const title = escapeHtml(proj.title);
            const category = escapeHtml(proj.category);
            const image = escapeHtml(proj.image || 'assets/img/projects/myprofile.png');
            const link = String(proj.link || '#');
            const visitTarget = (link && link !== '#' && isExternalUrl(link)) ? '_blank' : '_self';
            const visitRel = (visitTarget === '_blank') ? 'noopener' : '';
            const hasVisit = link && link !== '#';

            return `
              <div class="col-lg-6 portfolio-item-wrapper">
                <div class="portUXUI-item">
                  <div class="image">
                    <img loading="lazy" src="${image}" alt="${title}" class="img-fluid w-100" onerror="this.onerror=null;this.src='assets/img/projects/myprofile.png'">
                    <a href="${image}" class="gallery-popup full-image-preview" title="${title}">
                      <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                        <path d="M10 4.167v11.666M4.167 10h11.666"></path>
                      </svg>
                    </a>
                  </div>
                  <div class="text">
                    <div class="info">
                      <a href="javascript:void(0)" class="title js-project-details" data-project-id="${escapeHtml(proj.id)}">${title}</a>
                      <p class="subtitle">${category}</p>
                    </div>
                    <div class="visite-btn">
                      ${hasVisit ? `
                        <a href="${escapeHtml(link)}" target="${visitTarget}" rel="${visitRel}">Visit Site
                          <svg class="arrow-up" width="14" height="15" viewBox="0 0 14 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.91634 4.5835L4.08301 10.4168" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M4.66699 4.5835H9.91699V9.8335" stroke-linecap="round" stroke-linejoin="round"></path>
                          </svg>
                        </a>
                      ` : `
                        <a href="javascript:void(0)" class="js-project-details" data-project-id="${escapeHtml(proj.id)}">View Details
                          <svg class="arrow-up" width="14" height="15" viewBox="0 0 14 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.91634 4.5835L4.08301 10.4168" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M4.66699 4.5835H9.91699V9.8335" stroke-linecap="round" stroke-linejoin="round"></path>
                          </svg>
                        </a>
                      `}
                    </div>
                  </div>
                </div>
              </div>
            `;
        }).join('');

        // Details click handler (event delegation)
        container.addEventListener('click', (e) => {
            const btn = e.target.closest('.js-project-details');
            if (!btn) return;
            e.preventDefault();
            const id = btn.getAttribute('data-project-id');
            const project = projects.find(p => String(p.id) === String(id));
            if (project) openProjectModal(project);
        }, { passive: false });
    } catch (err) {
        console.error('Failed to load portfolio projects:', err);
        container.innerHTML = `
          <div class="col-12">
            <div class="alert alert-danger bg-transparent border" style="border-color: rgba(255,255,255,0.12) !important; border-radius: 16px;">
              Failed to load projects. Please try again.
            </div>
          </div>
        `;
    }
}
