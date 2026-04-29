# Portfolio Website

A modern portfolio website built with PHP, HTML, CSS, and JavaScript.

## Project Structure

```
├── admin/                 # Admin interface files
├── api/                  # API endpoints
├── assets/               # Static assets (images, CSS, JS)
│   ├── css/              # Stylesheets
│   ├── img/              # Images
│   └── js/               # JavaScript files
├── db/                   # Database files and initialization
├── includes/             # PHP includes and components
├── scripts/              # Utility scripts
└── *.html, *.php         # Main page files
```

## Features

- **Portfolio Management**: Display project details with images
- **Blog System**: Article management and display
- **Contact Form**: User contact functionality
- **Authentication**: Login/logout system
- **Admin Panel**: Administrative interface
- **Responsive Design**: Mobile-friendly layout
- **Elasticsearch Integration**: Demo and indexing capabilities

## Key Technologies

- **Backend**: PHP
- **Frontend**: HTML5, CSS3, JavaScript
- **Database**: SQLite (portfolio.db)
- **Search**: Elasticsearch integration
- **Deployment**: Vercel compatible

## Database Structure

- `portfolio.db`: SQLite database containing projects and skills
- `init.php`: Database initialization script
- `seed_projects.php`: Sample projects data
- `seed_skills.php`: Sample skills data

## Scripts

- `scripts/elastic/demo.sh`: Elasticsearch demo script
- `scripts/elastic/index_projects_ndjson.php`: Project indexing for Elasticsearch
- `db/fix_skill_icons.php`: Database utility for skill icons

## Setup Instructions

1. Copy the project files to your web server
2. Ensure PHP and SQLite are enabled
3. Run the database initialization scripts
4. Configure Elasticsearch if needed
5. Update connection details in configuration files

## File Notes

- `router.php`: Main routing system
- `header.php` & `footer.php`: Template includes
- `assets/mail.php`: Email functionality
- `vercel.json`: Vercel deployment configuration

## Author

Praveen Kumar - UI/UX Architect