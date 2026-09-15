# eLab.am Admin REST API Reference

Base URL: `/api/v1/admin`

All admin endpoints require `auth:sanctum` authentication and appropriate server-side RBAC permission.

## Response Formats

### Success Single Resource
```json
{
  "success": true,
  "data": { ... }
}
```

### Success Collection (Paginated)
```json
{
  "success": true,
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 100,
    "last_page": 5
  }
}
```

### Error Responses
- **401 Unauthenticated**: `{"success": false, "message": "Unauthenticated."}`
- **403 Forbidden**: `{"success": false, "message": "Forbidden."}`
- **404 Not Found**: `{"success": false, "message": "Resource not found"}`
- **409 Conflict**: `{"success": false, "message": "Resource already exists"}`
- **422 Validation Error**: `{"success": false, "message": "Validation failed", "errors": { ... }}`

---

## Authentication Endpoints (`/api/v1/auth`)

| Method | Endpoint | Description | Permission |
|---|---|---|---|
| `POST` | `/api/v1/auth/login` | User login (returns user object & sets HttpOnly session) | Public |
| `POST` | `/api/v1/auth/logout` | Invalidate current session | Authenticated |
| `GET` | `/api/v1/auth/me` | Retrieve authenticated user profile | Authenticated |
| `POST` | `/api/v1/auth/change-password` | Change user password | Authenticated |
| `POST` | `/api/v1/auth/forgot-password` | Request password reset token | Public |
| `POST` | `/api/v1/auth/reset-password` | Reset password using token | Public |

---

## Admin CMS Endpoints (`/api/v1/admin`)

### 1. Users Management
Permission Required: `manage_users`

- `GET /api/v1/admin/users` (List users with pagination `?page=1&per_page=20`, search `?search=...`, filter `?role=...`)
- `POST /api/v1/admin/users` (Create new user)
- `GET /api/v1/admin/users/{id}` (Get user details)
- `PUT /api/v1/admin/users/{id}` (Update user name, email, role, password)
- `DELETE /api/v1/admin/users/{id}` (Delete user; protects last SUPER_ADMIN)

### 2. Site Settings
Permission Required: `manage_settings`

- `GET /api/v1/admin/settings` (Get all key-value settings)
- `PUT /api/v1/admin/settings` (Batch update site settings)

### 3. Portfolio Management
Permission Required: `manage_portfolio`

- `GET /api/v1/admin/portfolio` (List portfolio projects with categories, technologies, images)
- `POST /api/v1/admin/portfolio` (Create project; syncs categories, technologies, and images; 409 on duplicate slug)
- `GET /api/v1/admin/portfolio/{id}` (Get project details)
- `PUT /api/v1/admin/portfolio/{id}` (Update project details)
- `DELETE /api/v1/admin/portfolio/{id}` (Delete project)
- `GET /api/v1/admin/portfolio/categories` & CRUD
- `GET /api/v1/admin/portfolio/technologies` & CRUD
- `GET /api/v1/admin/portfolio/{project}/images` & CRUD (Includes IDOR ownership protection)

### 4. Services CMS
Permission Required: `manage_services`

- `GET /api/v1/admin/services` & CRUD (Includes nested `features` management)

### 5. Media Library
Permission Required: `manage_media`

- `GET /api/v1/admin/media` (List uploaded media)
- `POST /api/v1/admin/media` (Upload media file with MIME, extension, and file size validation)
- `GET /api/v1/admin/media/{id}`
- `PUT /api/v1/admin/media/{id}` (Update metadata)
- `DELETE /api/v1/admin/media/{id}` (Delete file from storage disk)

### 6. Leads CRM
Permission Required: `manage_leads`

- `GET /api/v1/admin/leads` (List leads with status filter `?status=NEW`, search, pagination)
- `GET /api/v1/admin/leads/{id}`
- `PUT /api/v1/admin/leads/{id}` (Update status, assignment, details)
- `DELETE /api/v1/admin/leads/{id}`
- `GET /api/v1/admin/leads/{lead}/notes` & CRUD (Includes IDOR ownership protection)

### 7. Testimonials & FAQs
Permission Required: `manage_settings`

- `GET /api/v1/admin/testimonials` & CRUD
- `GET /api/v1/admin/faqs` & CRUD

### 8. SEO Metadata
Permission Required: `manage_seo`

- `GET /api/v1/admin/seo`
- `GET /api/v1/admin/seo/{id}`
- `PUT /api/v1/admin/seo/{id}`

### 9. Legal Pages
Permission Required: `manage_legal`

- `GET /api/v1/admin/legal` & CRUD

### 10. Cookie Settings
Permission Required: `manage_cookies`

- `GET /api/v1/admin/cookies`
- `PUT /api/v1/admin/cookies`

### 11. Audit Logs
Permission Required: `view_audit_logs`

- `GET /api/v1/admin/audit-logs` (Read-only list with search, action, user_id filtering)
- `GET /api/v1/admin/audit-logs/{id}` (Read-only detail)
