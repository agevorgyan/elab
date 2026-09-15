# eLab.am Laravel Backend REST API

This directory contains the dedicated **PHP Laravel 11** backend for **eLab.am**, connected to **MySQL**.

## System Architecture

```text
Next.js Frontend (http://localhost:3000)
       │
       ▼ (HTTPS REST API / JSON)
Laravel REST API (http://localhost:8000 /api/v1)
       │
       ▼ (Eloquent ORM)
MySQL Database (localhost:3306 / elab_db)
```

## Features Implemented (Phases 1–5)

1. **Infrastructure**: Laravel 11, PHP 8.5, MySQL 8.x, Composer dependencies.
2. **Database Schema & Models**: 19 domain models, 21 application tables, 7 framework tables.
3. **Authentication & Security**: Laravel Sanctum, SPA stateful sessions, HttpOnly cookies, CSRF protection, CORS configuration, Argon2id password verification, rate limiting, and Audit Logging.
4. **Server-Side RBAC**:
   - `SUPER_ADMIN`: All permissions.
   - `ADMIN`: CMS & Settings permissions.
   - `EDITOR`: Portfolio, Services, Media, SEO, Legal content permissions.
5. **Admin REST APIs (`/api/v1/admin/*`)**:
   - Users Management
   - Site Settings
   - Portfolio Projects, Categories, Technologies, Images (with IDOR ownership protection)
   - Services & Features
   - Media Library & Secure File Uploads
   - Leads CRM & Lead Notes
   - Testimonials & FAQ
   - SEO Metadata
   - Legal Pages
   - Cookie Settings
   - Audit Logs (Read-only)
6. **Public REST APIs (`/api/v1/*`)**:
   - Unauthenticated public endpoints for website content (`/settings`, `/portfolio`, `/portfolio/{slug}`, `/services`, `/testimonials`, `/faqs`, `/legal/{slug}`, `/seo/{slug}`, `/cookies`).
   - Server-side publication filtering (`published = true`).
   - Dedicated Public Resources preventing administrative data exposure.
   - Public Contact Form (`POST /api/v1/leads`) with IP rate limiting (5 req/min), honeypot anti-spam protection, 60s duplicate inquiry prevention, and generic 201 Created response.

## Running Locally

```bash
# Start MySQL Service via Homebrew
brew services start mysql

# Run Migrations
php artisan migrate

# Run Test Suite
php artisan test
```

## Documentation

- Detailed Admin API Reference: `docs/ADMIN_API.md`
- Detailed Public API Reference: `docs/PUBLIC_API.md`
