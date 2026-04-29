const { test, expect } = require('@playwright/test');

test.describe('Praveen Portfolio E2E Tests', () => {

  test('Homepage loads correctly and has dynamic content', async ({ page }) => {
    await page.goto('/modern-portfolio/index.php');

    // Check title
    await expect(page).toHaveTitle(/Praveen Kumar K/);

    // Check if skills container exists
    const skillsContainer = page.locator('#skills-container');
    await expect(skillsContainer).toBeVisible({ timeout: 20000 });

    // Wait for dynamic content to load
    await page.waitForSelector('.expertise-item .title', { timeout: 20000 });
    const skillsCount = await page.locator('.expertise-item').count();
    expect(skillsCount).toBeGreaterThan(0);
  });

  test('Portfolio page loads and displays projects', async ({ page }) => {
    await page.goto('/modern-portfolio/portfolio.php');

    const projectsContainer = page.locator('#projects-container');
    await expect(projectsContainer).toBeVisible({ timeout: 20000 });

    await page.waitForSelector('.project-item', { timeout: 20000 });
    const projectItems = await page.locator('.project-item').count();
    expect(projectItems).toBeGreaterThan(0);
  });

  test('Contact form submission workflow', async ({ page }) => {
    await page.goto('/modern-portfolio/contact.php');

    const form = page.locator('#contact-form');
    await expect(form).toBeVisible({ timeout: 20000 });

    await page.fill('#contact-form input[name="name"]', 'Test User');
    await page.fill('#contact-form input[name="email"]', 'test@example.com');
    await page.fill('#contact-form input[name="subject"]', 'E2E Test Subject');
    await page.selectOption('#contact-form select[name="budget"]', '$5000');
    await page.fill('#contact-form textarea[name="message"]', 'This is an automated E2E test message.');

    const submitBtn = page.locator('#contact-form button.submit-btn');
    await expect(submitBtn).toBeVisible();
    await submitBtn.click();

    await page.waitForTimeout(3000);
  });

  test('Admin login workflow', async ({ page }) => {
    await page.goto('/modern-portfolio/admin/');

    const logoText = page.locator('.logo-text');
    await expect(logoText).toContainText('Madmin');

    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'admin123');
    await page.click('button[type="submit"]');

    await page.waitForURL(/dashboard/, { timeout: 15000 });
    await expect(page.locator('.main-content h1')).toContainText(/Welcome back|back/);
  });

  test('AI Chatbot interaction', async ({ page }) => {
    await page.goto('/modern-portfolio/admin/');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'admin123');
    await page.click('button[type="submit"]');
    await page.waitForURL(/dashboard/);

    const toggleBtn = page.locator('.ai-toggle-btn');
    await expect(toggleBtn).toBeVisible({ timeout: 15000 });
    await toggleBtn.click();

    const chatInput = page.locator('#ai-query');
    await expect(chatInput).toBeVisible({ timeout: 15000 });

    await chatInput.fill('Hello AI');
    await page.click('button[onclick="sendAIQuery()"]');

    await page.waitForSelector('.msg.bot', { timeout: 25000 });
    const botResponse = await page.locator('.msg.bot').last().innerText();
    expect(botResponse.length).toBeGreaterThan(0);
  });
});
