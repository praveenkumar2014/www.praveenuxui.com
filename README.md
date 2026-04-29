# Praveen Kumar K — Portfolio & AI Agentic Design Strategist

A high-fidelity, premium professional portfolio built with PHP, HTML5, Vanilla CSS, and optimized for high-performance deployment on Vercel and Coolify.

## 🚀 Deployment

### 1. Vercel Deployment (Serverless PHP)
The project is configured with `vercel.json` to support PHP runtimes via `vercel-php`.

- **Build Command**: `npm run build` (optional static generation)
- **Runtime**: `vercel-php@0.7.1`
- **Configuration**: `vercel.json` handles Clean URLs and PHP function routing.

To deploy manually:
```bash
vercel --prod
```

### 2. Coolify / Self-Hosted Deployment (Docker)
A `Dockerfile` is provided for seamless integration with Coolify or any Docker-based hosting.

- **Base Image**: `php:8.2-apache`
- **Exposed Port**: 80
- **Internal Routing**: Apache `mod_rewrite` enabled.

To build and run:
```bash
docker build -t praveen-portfolio .
docker run -p 8080:80 praveen-portfolio
```

## 📂 Site Structure

### Main Navigation
- **Home**: `/index`
- **About**: `/about`
- **Expertise**: `/services`
- **Skills**: `/skills`
- **Portfolio**: `/portfolio`
- **Blog**: `/blog`
- **Contact**: `/contact`

### Admin & Auth
- **Admin Dashboard**: `/admin`
- **Login**: `/login`
- **Terms**: `/terms`
- **Privacy**: `/privacy`

### Core Features
- **AI Agentic Design**: Integrated AI branding and strategy content.
- **Micro-Animations**: GSAP & CSS3 transitions.
- **WhatsApp/Telegram Integration**: Direct recruitment CTAs.
- **Secure Admin Panel**: Session-based login system.

## 🛠️ Build System
The project includes a `build.php` script that can generate a purely static HTML version of the site for hosting on platforms like GitHub Pages.

```bash
php build.php
```

## 🔐 Admin Credentials
Refer to the private `admin_info.md` for superuser login details.
