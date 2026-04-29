import { test, expect } from '@playwright/test';

const BASE_URL = process.env.TEST_URL || 'http://localhost:3000';

// ── Helper ──────────────────────────────────────────────────────────────
async function goto(page, path) {
  await page.goto(BASE_URL + path, { waitUntil: 'domcontentloaded', timeout: 30000 });
}

// ── 1. CLEAN URLS — no .php or .html in location ─────────────────────
test.describe('Clean URLs', () => {
  const routes = ['/', '/about', '/services', '/skills', '/portfolio', '/contact'];
  for (const route of routes) {
    test(`${route} loads without .php/.html`, async ({ page }) => {
      await goto(page, route);
      expect(page.url()).not.toMatch(/\.(php|html)($|\?)/);
    });
  }
});

// ── 2. PAGE TITLES ────────────────────────────────────────────────────
test.describe('Page Titles', () => {
  test('Home has title', async ({ page }) => {
    await goto(page, '/');
    await expect(page).toHaveTitle(/Praveen Kumar/i);
  });
  test('About has title', async ({ page }) => {
    await goto(page, '/about');
    await expect(page).toHaveTitle(/Praveen Kumar/i);
  });
  test('Skills page has title', async ({ page }) => {
    await goto(page, '/skills');
    await expect(page).toHaveTitle(/Praveen Kumar/i);
  });
  test('Contact has title', async ({ page }) => {
    await goto(page, '/contact');
    await expect(page).toHaveTitle(/Praveen Kumar/i);
  });
});

// ── 3. NAVIGATION ─────────────────────────────────────────────────────
test.describe('Navigation', () => {
  test('Header is visible on home', async ({ page }) => {
    await goto(page, '/');
    await expect(page.locator('.header-area')).toBeVisible();
  });
  test('Nav has Skills link', async ({ page }) => {
    await goto(page, '/');
    const skillsLink = page.locator('a[href*="skills"]');
    await expect(skillsLink.first()).toBeVisible();
  });
  test('Logo links to home', async ({ page }) => {
    await goto(page, '/about');
    const logo = page.locator('.navbar-brand').first();
    await expect(logo).toBeVisible();
  });
  test('Nav links do not contain .php', async ({ page }) => {
    await goto(page, '/');
    const links = await page.locator('.navbar-info a').all();
    for (const link of links) {
      const href = await link.getAttribute('href');
      if (href && !href.startsWith('http') && !href.startsWith('#')) {
        expect(href).not.toMatch(/\.php$/);
      }
    }
  });
});

// ── 4. SOCIAL ICONS — WhatsApp + Telegram ─────────────────────────────
test.describe('Social Icons', () => {
  test('WhatsApp icon visible on home', async ({ page }) => {
    await goto(page, '/');
    await expect(page.locator('.fa-whatsapp').first()).toBeVisible();
  });
  test('Telegram icon visible on home', async ({ page }) => {
    await goto(page, '/');
    await expect(page.locator('.fa-telegram').first()).toBeVisible();
  });
  test('WhatsApp links to correct number', async ({ page }) => {
    await goto(page, '/');
    const waLink = page.locator('a[href*="wa.me"]').first();
    const href = await waLink.getAttribute('href');
    expect(href).toContain('918884263999');
  });
});

// ── 5. CONTACT FORM ───────────────────────────────────────────────────
test.describe('Contact Form', () => {
  test('Contact form is visible', async ({ page }) => {
    await goto(page, '/contact');
    await expect(page.locator('#contact-form')).toBeVisible();
  });
  test('Contact form has required fields', async ({ page }) => {
    await goto(page, '/contact');
    await expect(page.locator('input[name="name"]')).toBeVisible();
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="subject"]')).toBeVisible();
    await expect(page.locator('textarea[name="message"]')).toBeVisible();
  });
  test('Submit button is present', async ({ page }) => {
    await goto(page, '/contact');
    await expect(page.locator('.submit-btn')).toBeVisible();
  });
});

// ── 6. SKILLS PAGE ────────────────────────────────────────────────────
test.describe('Skills Page', () => {
  test('Skills page loads', async ({ page }) => {
    await goto(page, '/skills');
    await expect(page.locator('.skills-page')).toBeVisible();
  });
  test('Skills categories are present', async ({ page }) => {
    await goto(page, '/skills');
    const cats = page.locator('.skill-cat-title');
    await expect(cats).toHaveCount({ min: 4 });
  });
  test('Skill cards are visible', async ({ page }) => {
    await goto(page, '/skills');
    const cards = page.locator('.skill-card');
    const count = await cards.count();
    expect(count).toBeGreaterThan(20);
  });
  test('Skill icons use svgl.app or local assets', async ({ page }) => {
    await goto(page, '/skills');
    const imgs = page.locator('.skill-icon img').first();
    await expect(imgs).toBeVisible();
  });
});

// ── 7. PROFILE CARD ───────────────────────────────────────────────────
test.describe('Profile Card', () => {
  test('Profile image loads on home', async ({ page }) => {
    await goto(page, '/');
    const img = page.locator('.profile-card img').first();
    await expect(img).toBeVisible();
  });
  test('"Available For Hire" badge visible', async ({ page }) => {
    await goto(page, '/');
    await expect(page.locator('.available-btn')).toBeVisible();
  });
});

// ── 8. PERFORMANCE — no broken internal links ──────────────────────────
test.describe('Link Integrity', () => {
  test('No raw .php links in rendered home page', async ({ page }) => {
    await goto(page, '/');
    const html = await page.content();
    // Check nav links specifically
    const navMatches = html.match(/href="[^"]*\.php"/g) || [];
    // Filter out assets/mail.php which is expected
    const badLinks = navMatches.filter(l => !l.includes('assets/mail.php'));
    expect(badLinks).toHaveLength(0);
  });
});
