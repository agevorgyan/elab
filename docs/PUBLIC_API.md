# eLab.am Public REST API Documentation

All public endpoints are unauthenticated and served under `/api/v1/*`.

---

## Standard Response Format

### Single Resource
```json
{
  "success": true,
  "data": { ... }
}
```

### Collection
```json
{
  "success": true,
  "data": [ ... ]
}
```

### Paginated Collection
```json
{
  "success": true,
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "per_page": 12,
    "total": 45,
    "last_page": 4
  }
}
```

---

## Endpoints

### 1. Site Settings
- **Method**: `GET /api/v1/settings`
- **Description**: Returns public site configuration settings (branding, contact details, social links).

### 2. Portfolio Listing
- **Method**: `GET /api/v1/portfolio`
- **Query Parameters**:
  - `category` (optional, string): Filter by category slug (e.g. `web`).
  - `technology` (optional, string): Filter by technology slug (e.g. `laravel`).
  - `featured` (optional, boolean): Filter featured projects (`true` or `false`).
  - `page` (optional, integer): Page number for pagination.
  - `per_page` (optional, integer): Items per page (default: 12, max: 100).
- **Description**: Returns published portfolio projects ordered by `sort_order` ASC with eager-loaded categories, technologies, and images.

### 3. Portfolio Detail
- **Method**: `GET /api/v1/portfolio/{slug}`
- **Description**: Returns single published project by URL slug. Returns HTTP 404 if project is missing or unpublished.

### 4. Services
- **Method**: `GET /api/v1/services`
- **Description**: Returns published services with ordered service features.

### 5. Testimonials
- **Method**: `GET /api/v1/testimonials`
- **Description**: Returns published client testimonials ordered by `sort_order` ASC.

### 6. FAQs
- **Method**: `GET /api/v1/faqs`
- **Description**: Returns published FAQ items ordered by `sort_order` ASC.

### 7. Legal Pages
- **Method**: `GET /api/v1/legal/{slug}`
- **Description**: Returns single published legal page (e.g. `privacy-policy`, `terms-of-use`). Returns HTTP 404 if unpublished or non-existent.

### 8. SEO Metadata
- **Method**: `GET /api/v1/seo/{slug?}`
- **Description**: Returns SEO title, description, keywords, OpenGraph data, and robots directive for page paths.

### 9. Cookie Settings
- **Method**: `GET /api/v1/cookies`
- **Description**: Returns active cookie consent banner preferences and analytics tracking IDs.

### 10. Contact / Lead Submission
- **Method**: `POST /api/v1/leads`
- **Rate Limit**: 5 requests per minute per IP.
- **Request Body**:
```json
{
  "name": "Arman Gevorgyan",
  "phone": "+37455776066",
  "email": "arman@example.com",
  "company": "Tech Corp",
  "project_type": "corporate-website",
  "budget": "$3,000 - $5,000",
  "message": "Project inquiry message...",
  "source": "Website Form",
  "website_url": "" 
}
```
- **Security Protections**:
  - **Honeypot Anti-Spam**: If optional honeypot field (`website_url` / `honeypot` / `hp_field`) is filled, submission is silently ignored without saving to DB.
  - **Deduplication**: Submissions from the same phone/email within 60 seconds return generic success without inserting duplicate rows.
  - **Input Sanitization**: HTML tags and scripts stripped server-side.
- **Success Response** (`201 Created`):
```json
{
  "success": true,
  "data": {
    "message": "Your message has been received."
  }
}
```
