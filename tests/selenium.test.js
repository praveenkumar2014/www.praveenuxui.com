/**
 * Selenium WebDriver Tests — Praveen Kumar K Portfolio
 * Tests key flows with ChromeDriver (no .php/.html in URLs)
 */

const { Builder, By, until } = require('selenium-webdriver');
const chrome = require('selenium-webdriver/chrome');

const BASE_URL = process.env.TEST_URL || 'http://localhost:3000';
const TIMEOUT = 15000;

let driver;

async function setup() {
  const opts = new chrome.Options();
  opts.addArguments('--headless', '--no-sandbox', '--disable-dev-shm-usage', '--disable-gpu');
  driver = await new Builder().forBrowser('chrome').setChromeOptions(opts).build();
  await driver.manage().setTimeouts({ implicit: TIMEOUT });
}

async function teardown() {
  if (driver) await driver.quit();
}

async function assert(condition, message) {
  if (!condition) throw new Error(`FAIL: ${message}`);
  console.log(`  ✓ ${message}`);
}

async function runTests() {
  const errors = [];

  // ── TEST 1: Home page loads with clean URL ──────────────────────
  try {
    await driver.get(BASE_URL + '/');
    const url = await driver.getCurrentUrl();
    await assert(!url.includes('.php') && !url.includes('.html'), 'Home URL is clean (no .php/.html)');
    const title = await driver.getTitle();
    await assert(title.includes('Praveen'), 'Home page has correct title');
  } catch (e) { errors.push(`Home: ${e.message}`); }

  // ── TEST 2: Navigation links ────────────────────────────────────
  try {
    await driver.get(BASE_URL + '/');
    const navLinks = await driver.findElements(By.css('.navbar-info a'));
    await assert(navLinks.length >= 4, `Nav has ${navLinks.length} links (≥4)`);
    for (const link of navLinks) {
      const href = await link.getAttribute('href');
      if (href && !href.startsWith('http')) {
        await assert(!href.endsWith('.php') && !href.endsWith('.html'), `Nav href "${href}" is clean`);
      }
    }
  } catch (e) { errors.push(`Navigation: ${e.message}`); }

  // ── TEST 3: About page clean URL ────────────────────────────────
  try {
    await driver.get(BASE_URL + '/about');
    const url = await driver.getCurrentUrl();
    await assert(!url.includes('.php') && !url.includes('.html'), 'About URL is clean');
    await driver.wait(until.elementLocated(By.css('.profile-card')), TIMEOUT);
    await assert(true, 'About page profile card found');
  } catch (e) { errors.push(`About: ${e.message}`); }

  // ── TEST 4: Skills page ─────────────────────────────────────────
  try {
    await driver.get(BASE_URL + '/skills');
    const url = await driver.getCurrentUrl();
    await assert(!url.includes('.php'), 'Skills URL is clean');
    const skillCards = await driver.findElements(By.css('.skill-card'));
    await assert(skillCards.length > 10, `Skills page has ${skillCards.length} skill cards`);
    const cats = await driver.findElements(By.css('.skill-cat-title'));
    await assert(cats.length >= 4, `Skills page has ${cats.length} categories`);
  } catch (e) { errors.push(`Skills: ${e.message}`); }

  // ── TEST 5: Contact form ────────────────────────────────────────
  try {
    await driver.get(BASE_URL + '/contact');
    const form = await driver.findElement(By.id('contact-form'));
    await assert(!!form, 'Contact form exists');
    const nameInput = await driver.findElement(By.css('input[name="name"]'));
    await nameInput.sendKeys('Test User');
    const emailInput = await driver.findElement(By.css('input[name="email"]'));
    await emailInput.sendKeys('test@example.com');
    await assert(true, 'Contact form inputs are interactive');
  } catch (e) { errors.push(`Contact form: ${e.message}`); }

  // ── TEST 6: WhatsApp / Telegram icons ──────────────────────────
  try {
    await driver.get(BASE_URL + '/');
    const waIcon = await driver.findElements(By.css('.fa-whatsapp'));
    await assert(waIcon.length > 0, 'WhatsApp icon present');
    const tgIcon = await driver.findElements(By.css('.fa-telegram'));
    await assert(tgIcon.length > 0, 'Telegram icon present');
    const waLink = await driver.findElements(By.css('a[href*="wa.me"]'));
    await assert(waLink.length > 0, 'WhatsApp link with wa.me found');
  } catch (e) { errors.push(`Social Icons: ${e.message}`); }

  // ── TEST 7: Portfolio page ──────────────────────────────────────
  try {
    await driver.get(BASE_URL + '/portfolio');
    const url = await driver.getCurrentUrl();
    await assert(!url.includes('.php'), 'Portfolio URL is clean');
    await driver.wait(until.elementLocated(By.css('.profile-card')), TIMEOUT);
    await assert(true, 'Portfolio page loads');
  } catch (e) { errors.push(`Portfolio: ${e.message}`); }

  // ── Summary ─────────────────────────────────────────────────────
  console.log('\n' + '─'.repeat(50));
  if (errors.length === 0) {
    console.log('✅ All Selenium tests passed!');
    process.exit(0);
  } else {
    console.error(`\n❌ ${errors.length} test(s) failed:`);
    errors.forEach(e => console.error('  •', e));
    process.exit(1);
  }
}

(async () => {
  console.log('🧪 Starting Selenium Tests...\n' + '─'.repeat(50));
  try {
    await setup();
    await runTests();
  } catch (fatal) {
    console.error('Fatal error:', fatal.message);
    process.exit(1);
  } finally {
    await teardown();
  }
})();
