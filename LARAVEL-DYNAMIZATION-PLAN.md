# NDC Bangladesh Website — Laravel + MySQL Dynamization Plan

> **Scope of this document:** Use cases mapped to models, number of Models and
> Services needed in the MVC pattern, file handling strategy, and authentication
> design. No code is written yet — this is the planning layer only.

> **Current static prototype layout** (as of 2026-07-11):
> ```
> public/   — customer-facing pages (index, ndc_about, ndc_services, ndc_contact,
>             ndc_notices, ndc_pricing_cloud, ndc_pricing_request, ndc_policies,
>             ndc_forms, login)
> admin/    — admin panel pages, filenames without the old "admin-" prefix
>             (dashboard, login, homepage, pages, services, pricing-cloud,
>             pricing-request, notices, team, media, users, settings)
> assets/
>   css/main.css   — shared variables/layout/header/nav/footer (extracted;
>                    page-specific assets/css/*.css files are not yet split out —
>                    every page still carries a full inline <style> fallback)
>   docs/forms/, docs/portfolio/ — real downloadable forms/SLAs/portfolio PDFs
>                    mirrored from ndc.bcc.gov.bd
> ```
> **Update (2026-07-11):** `ndc_policies.html` and `ndc_forms.html` now have
> dedicated admin editor screens — `admin/page-policies.html` and
> `admin/page-forms.html` — matching the pattern used for About/Contact. They're
> registered in `admin/pages.html`'s Site Pages list and reuse the generic `Page`
> model design below (`Page.content_blocks`), same as every other content page.
>
> **Update (2026-07-11) — Advertising-only scope change:** NDC's own site no
> longer sells, takes, or tracks orders. Real ordering and account management
> happen on the external **NDC Cloud Management Portal** (`cmp.bcc.gov.bd`,
> currently in testing); SSL/VPN certificate subscription happens on the
> external **BCC Certifying Authority** portal (`bcc-ca.gov.bd`). Concretely:
> `public/register.html` is deleted (no more public self-registration),
> `public/login.html` is repurposed into an "Account Access" hand-off page
> (Admin Login / CMP / a plain contact-style message form), and the
> `ServiceOrder` + `Organization` models below are removed. `admin/users.html`
> now manages NDC staff/admin accounts (`is_admin` flag) instead of customer
> portal accounts. See Part 6.7 for the new public-form abuse-protection design
> (CAPTCHA from the 2nd submission onward) that replaces account-gating as the
> anti-abuse mechanism for public forms.

---

## Part 1 — Use Cases Mapped to Models

Each row describes one user-facing or admin-facing capability, the actor who
triggers it, and the Eloquent model(s) that own the data.

### 1.1 Public Portal Use Cases

| # | Use Case | Actor | Primary Model(s) |
|---|----------|-------|-----------------|
| UC-01 | View homepage (hero, ticker, stats, featured services) | Visitor | `Page`, `Service`, `Setting` |
| UC-02 | Browse About page (history, mission, team, certifications) | Visitor | `Page`, `TeamMember` |
| UC-03 | Browse Services page (service descriptions + order procedure — informational; hands off to CMP for Cloud/Request orders, to BCC CA + a downloadable form for SSL/VPN) | Visitor | `Service` |
| UC-04 | View Cloud Based Pricing page (ECS, EVS, ELB, Backup, Network) | Visitor | `PricingTier` |
| UC-05 | View Request Based Pricing page (VPS, Backup, LB, DB, Email, Colo, Hosting) | Visitor | `PricingTier` |
| UC-06 | View Notices & Circulars (filter by category, download PDF) | Visitor | `Notice`, `MediaAsset` |
| UC-07 | Submit a Contact Inquiry / message — via the Contact page or the Account Access (login) page's message box; CAPTCHA required from the 2nd submission onward | Visitor | `ContactInquiry` |

Ordering, account creation, and order tracking are explicitly **out of scope**
for this site — see the header note above. There is no `ServiceOrder` or
`Organization` model and no public registration/login use case.

### 1.2 Admin CMS Use Cases

| # | Use Case | Actor | Primary Model(s) |
|---|----------|-------|-----------------|
| UC-08 | Admin login (gated on `is_admin = 1`) | Admin | `User` |
| UC-09 | View dashboard (stats: notices, admin users, pages) | Admin | `Notice`, `User`, `Page` |
| UC-10 | Create / Edit / Publish / Delete a Notice | Content Editor | `Notice`, `MediaAsset` |
| UC-11 | Bulk publish / draft / delete selected notices | Content Editor | `Notice` |
| UC-12 | Manage site pages content (About, Contact, Policies, Forms text blocks) | Content Editor | `Page` |
| UC-13 | Edit Homepage (hero, ticker, stat bar, about section, featured services) | Content Editor | `Page`, `Service`, `Setting` |
| UC-14 | Add / Edit / Hide a Service in the Services Catalog | Content Editor | `Service` |
| UC-15 | Add / Edit / Remove a Team Member | Content Editor | `TeamMember`, `MediaAsset` |
| UC-16 | Edit Cloud Based Pricing tier (price, visibility, specs) | Content Editor | `PricingTier` |
| UC-17 | Edit Request Based Pricing tier (price, visibility, specs) | Content Editor | `PricingTier` |
| UC-18 | Add / Delete a new pricing tier row | Super Admin | `PricingTier` |
| UC-19 | Toggle pricing tier visibility on/off | Content Editor | `PricingTier` |
| UC-20 | Upload / Delete media files (images, PDFs) | Content Editor | `MediaAsset` |
| UC-21 | Invite / Suspend / Delete admin/staff accounts | Super Admin | `User` |
| UC-22 | Update site settings (contact info, feature toggles, logos) | Super Admin | `Setting`, `MediaAsset` |
| UC-23 | View audit trail of all content changes | Super Admin | `AuditLog` |

---

## Part 2 — Models (Eloquent, 10 total)

Below is every model needed, its table, purpose, and key relationships.
No model is redundant — each maps to a distinct entity with its own lifecycle.

```
app/Models/
├── User.php              ← NDC admin/staff accounts only (is_admin gate + role)
├── Page.php              ← CMS content blocks per page slug
├── Notice.php            ← Notices & Circulars (public-facing)
├── Service.php           ← Services catalog (shown on Services page + homepage)
├── TeamMember.php        ← About page team section
├── PricingTier.php       ← All pricing rows for Cloud + Request Based pages
├── ContactInquiry.php    ← Contact form + Account-Access-page message submissions
├── MediaAsset.php        ← Uploaded files (images, PDFs) shared across entities
├── Setting.php           ← Site-wide key/value settings
└── AuditLog.php          ← Admin action audit trail
```

### Model Responsibilities

**User** — NDC admin/staff accounts only; there are no public customer
accounts on this site (real customer accounts live on CMP). `is_admin`
(boolean, 0/1) is the panel-access gate — everything under `/admin/*` checks
this first. `role` (`content_editor` / `super_admin`) then decides what an
admin account can do once inside, since several use cases are Super-Admin-only
(e.g. UC-18 add/delete a pricing tier). Has `status` (active / suspended).
Created via Super Admin invite (see Part 6.2), never via public self-registration.

**Page** — one row per page slug (home, about, services, notices, contact).
Stores all editable text in a `content_blocks` JSON column so the admin can
change hero text, ticker, stat bar values, and about paragraphs without a
code deploy. Maps directly to admin/homepage.html and admin/pages.html.

**Notice** — has `category` (maintenance / services / tender / policy /
security / general) which maps to the `.cat-*` CSS classes in the frontend.
Has `status` (draft / review / published) and `visibility` (public /
internal). Has optional `attachment_media_id` FK pointing to a PDF in
MediaAsset. Soft-deletes so bulk-delete is recoverable.

**Service** — each row is one service card shown on the homepage and services
page. Has `is_featured` (appears on homepage grid) and `is_visible` (toggle
in admin/services.html). Has `display_order` for drag-to-reorder.

**TeamMember** — About page leadership and technical staff. Has `group`
(leadership / technical_staff), `display_order`, and optional `photo_media_id`
FK to MediaAsset.

**PricingTier** — single table covers all 38 pricing rows across both pricing
pages. The `tier_key` column matches `data-tier-id` attributes in the HTML
exactly (e.g. `ecs-001`, `colo-rua`, `vps-004`). The `service_type` column
(e.g. `cloud_ecs`, `rbs_vps`) groups tiers per page section. The `specs`
JSON column holds per-tier attributes (vCPU, RAM, storage, etc.) that render
as the spec list on pricing cards. `price_value` is numeric for sorting and
calculations; `price_display` is the pre-formatted Bengali Taka string shown
as-is in the frontend.

**ContactInquiry** — stores both the Contact page form and the Account Access
(login) page's message-box submissions (same model, no separate table — the
login-page form posts to the same `/api/contact-inquiries` endpoint). Has
`inquiry_type` matching the `#cfSubject` select option values exactly (nullable
for login-page submissions, which don't show that field). Has a free-text
`organization` field (carries what a normalized `Organization` model used to,
now that there's no account system to attach it to). Has `status`
(new / in_progress / resolved) for admin follow-up tracking.

**MediaAsset** — shared media store used by Notice (PDF attachment), TeamMember
(profile photo), and Setting (logo uploads). Stores `storage_path` (relative
to the storage disk) and `public_url` (cached resolved URL). Uploading through
admin/media.html creates rows here; other entities reference them by FK.

**Setting** — key/value store. Keys are pre-seeded to match every field in
admin/settings.html (site_name, helpdesk_phone, ticker_message, logo media
IDs, feature toggle flags, etc.). Accessed through a global `setting($key)`
helper so Blade templates never query the DB directly for individual settings.

**AuditLog** — append-only log. Written by a service layer method on every
create / update / delete action in the admin. Stores `entity_type`,
`entity_id`, `action` string, and a `meta` JSON diff (before/after values).
Used to power the Recent Activity feed on admin/dashboard.html.

---

## Part 3 — Services (Application Layer, 8 total)

Services sit between Controllers and Models. Each service encapsulates
business logic that would otherwise bloat controllers. Named after the
domain capability they provide, not the model they touch.

```
app/Services/
├── AuthService.php            ← admin login, logout, invite-based account creation
├── NoticeService.php          ← create, update, publish, bulk actions
├── PricingService.php         ← update tier, toggle visibility, cache management
├── ContentService.php         ← page CMS blocks read/write, homepage builder
├── MediaService.php           ← file upload, validation, storage, delete
├── SettingService.php         ← read/write settings, cache, logo swap
├── UserManagementService.php  ← invite, suspend, delete admin/staff accounts
└── AuditService.php           ← record audit log entries (called by all other services)
```

### Service Responsibilities

**AuthService** handles admin authentication only (no public accounts exist):
- `login(email, password, rememberMe)` — validates credentials, checks
  `is_admin = true` and `status === 'active'`, regenerates session, updates
  `last_login_at`.
- `logout()` — invalidates session token.
- `completeInvite(token, password)` — the invited-user counterpart to
  register: sets the password on the `User` row a Super Admin already
  created via `UserManagementService::invite()`, activates the account. See
  Part 6.2.

**NoticeService** handles all CRUD for Notices:
- `createOrUpdate(data, file?)` — sanitizes `body_html` through HTMLPurifier,
  stores the PDF attachment via MediaService if provided, writes the notice row,
  calls AuditService to log the action.
- `bulkAction(ids[], action)` — publish / draft / soft-delete selected rows.
  Used by the bulk action bar in admin/notices.html.
- `getPublic(filters)` — returns published, public notices ordered by
  `published_at` DESC, with optional category filter for the public notices page.

**PricingService** manages pricing tier data:
- `updatePrice(tierKey, newPrice)` — finds the `PricingTier` by `tier_key`
  (which matches `data-tier-id` in HTML), updates `price_value` and regenerates
  `price_display` string, flushes the pricing cache, calls AuditService.
- `toggleVisibility(tierKey, visible)` — sets `is_visible`, flushes cache.
- `getForPage(serviceTypes[])` — returns tiers grouped by `service_type`,
  reading from cache when available. Cache key: `pricing:{service_type}`.
  Called by `PricingController` to render both public pricing pages and the
  admin pricing editors.
- `addTier(data)` / `deleteTier(tierKey)` — super admin only operations.

**ContentService** manages the CMS page content:
- `getBlocks(slug)` — reads `Page.content_blocks` JSON for a given page slug.
  Returns a typed array. Caches per slug with key `page:{slug}`.
- `updateBlocks(slug, blocks)` — merges the incoming block data into the
  existing JSON, saves, flushes cache, calls AuditService.
- `getFeaturedServices()` — reads `featured_service_ids` from the homepage
  page block, fetches the corresponding `Service` rows in order.
- Used by: `HomeController`, `AboutController`, `AdminHomepageController`,
  `AdminPageController`.

**MediaService** handles all file operations:
- `store(UploadedFile, folder)` — validates MIME type by magic bytes (not just
  extension), generates a unique filename, stores to the configured disk
  (`public` for local, `s3` for MinIO), creates and returns a `MediaAsset` row.
- `delete(MediaAsset)` — removes from storage disk, deletes the DB row, checks
  no other entity holds a FK reference to it before deleting.
- `getPublicUrl(MediaAsset)` — returns the cached public URL or regenerates a
  signed URL if using private S3.
- Accepted MIME types: `image/jpeg`, `image/png`, `image/svg+xml`,
  `application/pdf`, `application/vnd.openxmlformats-officedocument.*`.
- Max size: 20 MB per file (enforced server-side regardless of HTML `max`
  attribute).

**SettingService** wraps the `Setting` model:
- `get(key, default)` — reads from cache first, falls back to DB.
  Cache key: `setting:{key}`, TTL: 60 minutes.
- `set(key, value)` — saves to DB, flushes specific cache key.
- `setBulk(array)` — used by admin/settings.html form POST — saves multiple
  keys in one transaction, flushes all affected cache keys.
- `swapLogo(type, UploadedFile)` — delegates to MediaService, then updates the
  relevant setting key (`logo_ndc_media_id`, `logo_bcc_media_id`, etc.).
- The global `setting($key)` helper calls `app(SettingService::class)->get($key)`.

**UserManagementService** handles admin/staff account operations:
- `invite(email, role)` — creates a `User` row with `is_admin = true`,
  `status = pending`, the given `role`, and a signed invite token; dispatches
  `AdminInviteMail` with a link to `AuthService::completeInvite()`. Used by
  admin/users.html's "Invite Admin User" button.
- `suspend(User)` — sets `status = suspended`, invalidates any active sessions.
- `delete(User)` — never deletes the last `super_admin` account.
- `list(filters)` — paginated list with role and status filters, used by
  admin/users.html's table.

**AuditService** is called by all other services, never directly by controllers:
- `record(User, action, Model, meta[])` — appends one `AuditLog` row.
  `action` string format: `entity.verb` (e.g. `notice.publish`, `pricing.update`).
  `meta` stores before/after diff of changed fields.
- `recent(limit)` — returns latest N audit entries for the dashboard activity feed.

---

## Part 4 — MVC Structure Summary

```
Controller             calls        Service              touches      Model(s)
─────────────────────────────────────────────────────────────────────────────
HomeController         ──────────▶  ContentService       ──────────▶  Page
                                    SettingService       ──────────▶  Setting
                                    ContentService       ──────────▶  Service (featured)

PricingController      ──────────▶  PricingService       ──────────▶  PricingTier

ContactController      ──────────▶  (direct)             ──────────▶  ContactInquiry
                                    (handles both the Contact page form and the
                                     Account Access page's message box)

AdminAuthController    ──────────▶  AuthService          ──────────▶  User

AdminNoticeController  ──────────▶  NoticeService        ──────────▶  Notice
                                    MediaService         ──────────▶  MediaAsset
                                    AuditService         ──────────▶  AuditLog

AdminPricingController ──────────▶  PricingService       ──────────▶  PricingTier
                                    AuditService         ──────────▶  AuditLog

AdminHomepageController──────────▶  ContentService       ──────────▶  Page
                                    AuditService         ──────────▶  AuditLog

AdminUserController    ──────────▶  UserManagementService──────────▶  User
                                    AuditService         ──────────▶  AuditLog

AdminMediaController   ──────────▶  MediaService         ──────────▶  MediaAsset

AdminSettingsController──────────▶  SettingService       ──────────▶  Setting
                                    MediaService         ──────────▶  MediaAsset
```

**Rule:** Controllers validate the HTTP request and call one service method.
Services contain all business logic and call other services when needed.
Models contain only Eloquent relationships, scopes, and casts — no logic.

---

## Part 5 — File Handling

### 5.1 Storage Strategy

Two environments, same code path:

| Environment | Disk Driver | Location |
|-------------|------------|----------|
| Development / MVP | `public` (Laravel local) | `storage/app/public/` symlinked to `public/storage/` |
| Production | `s3` (MinIO self-hosted or AWS S3) | Bucket: `ndc-media` |

The `FILESYSTEM_DISK` env var switches between them. `MediaService` always
calls `Storage::disk()` without naming the disk explicitly, so the same
service code works in both environments.

### 5.2 Upload Flow

```
Browser uploads file via admin/media.html form
     ↓
MediaController::store() validates HTTP request (size, MIME hint)
     ↓
MediaService::store() — validates MIME by magic bytes (finfo_file)
     ↓
File stored to configured disk under /media/{YYYY}/{MM}/{uuid}.{ext}
     ↓
MediaAsset row created with storage_path, public_url, mime_type, size_bytes
     ↓
Response: { id, public_url } — used by notice attachment picker, team photo, logo swap
```

### 5.3 File Categories and Rules

| Category | Accepted Types | Max Size | Stored Under |
|----------|---------------|----------|--------------|
| Notice PDF attachment | `application/pdf` | 10 MB | `/media/notices/` |
| Team member photo | `image/jpeg`, `image/png` | 2 MB | `/media/team/` |
| Site logos (NDC / BCC / ICT) | `image/png`, `image/svg+xml` | 500 KB | `/media/logos/` |
| General media library | jpg, png, svg, pdf, docx | 20 MB | `/media/{YYYY}/{MM}/` |

### 5.4 Image Processing

For team member photos and logo uploads, `MediaService` runs an optional
resize through `intervention/image` before saving:
- Team photos → resized/cropped to 200×200 px, stored as JPEG quality 85.
- Logo files → kept as-is (SVG pass-through, PNG only resized if over 300 KB).

### 5.5 PDF Serving

Public PDF downloads (Notice attachments) are served via a signed temporary
URL in production (S3) or directly via `public/storage` symlink in development.
The URL is never the raw storage path — always routed through
`GET /files/notices/{mediaAsset:id}` which checks visibility before streaming.

### 5.6 Deleting Files

`MediaService::delete()` first checks whether any other model holds a FK
reference to the `MediaAsset` row (notice attachment, team photo, logo setting).
If references exist, deletion is blocked and an error is returned. Only
unreferenced media is permanently deleted from both the DB and the storage disk.

---

## Part 6 — Authentication Design

This site has **one** auth system, for NDC admin/staff only. There are no
public customer accounts — real customer accounts and login live on CMP
(`cmp.bcc.gov.bd`), outside this codebase entirely.

### 6.1 Admin Auth Guard

```
Guard: web (single guard, no separate portal guard needed)
  Session-based, standard Laravel Auth
  Login route: POST /admin/login  (admin/login.html)
  Protected routes: /admin/*
  Gate: is_admin must be true AND status === 'active'
  Extra check inside the panel: role must be super_admin for
    Super-Admin-only actions (UC-18, UC-21, UC-22)
  Optional: IP allowlist middleware for government network
```

The `CheckAdminRole` middleware redirects to `/admin/login` if the request is
unauthenticated, or lacking `is_admin`, or `status !== 'active'`.

### 6.2 Admin Account Creation Flow (UC-21) — invite-based, no public signup

```
Super Admin fills the "Invite Admin User" form in admin/users.html
  fields: full_name, email, role (content_editor | super_admin)
     ↓
UserManagementService::invite() creates:
  User { full_name, email, is_admin: true, role, status: 'pending' }
  + a signed, expiring invite token
     ↓
AdminInviteMail dispatched → sent to the invited email with a
  "Set your password" link containing the token
     ↓
Invitee opens the link → sets a password
AuthService::completeInvite(token, password) sets status = 'active'
     ↓
Invitee can now log in at /admin/login
```

No self-registration route exists anywhere on this site — every admin
account traces back to a Super Admin's invite action, which the `AuditLog`
records.

### 6.3 Login Flow (UC-08)

```
Admin fills admin/login.html (#loginForm)
  fields: email, password, remember_me
     ↓
AuthService::login()
  Auth::attempt(['email', 'password'], $rememberMe)
  Check is_admin === true AND status === 'active' (pending/suspended → reject)
  Session::regenerate()
  Update last_login_at
     ↓
Redirect to intended URL or /admin/dashboard
```

### 6.4 Role Hierarchy

```
super_admin
  └── All admin capabilities + admin-user management + settings + delete any content

content_editor
  └── Notices CRUD + Pricing edit + Services + Team + Media + Pages + Homepage
```

### 6.5 Password Security

- Hashed with bcrypt, cost factor ≥ 12 (Laravel default).
- Minimum 8 characters enforced at validation layer.
- Password reset flow: standard Laravel `Password::sendResetLink()` via the
  government SMTP relay.
- No plaintext passwords ever stored or logged.

### 6.6 Session Security

- `SESSION_DRIVER=redis` in production — faster and survives PHP restarts.
- Session cookie: `HttpOnly`, `Secure` (HTTPS only), `SameSite=Strict`.
- CSRF: Laravel's `VerifyCsrfToken` middleware active on all web routes.
  Every Blade form gets `@csrf`. The `data-api-endpoint` attributes in the
  current static HTML will become real `action=""` values in Blade with
  `@csrf` tokens injected.
- Admin session timeout: 2 hours idle (configurable via `SESSION_LIFETIME`).

### 6.7 Public Form Abuse Protection (replaces account-gating for public forms)

With no accounts to gate submissions, `POST /api/contact-inquiries` — used by
both the Contact page form and the Account Access page's message box — is
protected instead by:

- **Rate limiting**: Laravel's `throttle` middleware on the route (e.g.
  `throttle:5,1` — 5 submissions per minute per IP), independent of the
  CAPTCHA below.
- **CAPTCHA from the 2nd submission onward**: the first submission from a
  given browser is unobstructed; a signed cookie is set on success. Any
  subsequent submission while that cookie is present requires solving a
  CAPTCHA (reCAPTCHA v2 or hCaptcha) before the request is accepted. This
  mirrors the frontend mock already in the static HTML — both `ndc_contact.html`
  and `login.html` show a placeholder CAPTCHA checkbox from the 2nd submission
  onward, tracked client-side via `localStorage` since there's no backend yet.
- Server-side validation must re-check the CAPTCHA token even though the
  frontend already gates the UI — the client-side check is a UX nicety, not
  the security boundary.

---

## Part 7 — Summary Counts

| Layer | Count | Names |
|-------|-------|-------|
| Eloquent Models | **10** | User, Page, Notice, Service, TeamMember, PricingTier, ContactInquiry, MediaAsset, Setting, AuditLog |
| Application Services | **8** | AuthService, NoticeService, PricingService, ContentService, MediaService, SettingService, UserManagementService, AuditService |
| Public Controllers | **5** | HomeController, AboutController, ServicesController, PricingController, ContactController |
| Auth Controllers | **1** | AdminAuthController (admin panel only — no public/portal auth controller) |
| Admin Controllers | **9** | AdminDashboardController, AdminNoticeController, AdminPricingController, AdminPageController, AdminServiceController, AdminTeamController, AdminUserController, AdminMediaController, AdminSettingsController, AdminHomepageController *(10 files)* |
| Blade Layouts | **2** | `layouts/public.blade.php`, `layouts/admin.blade.php` |
| DB Migrations | **10** | One per model/table (see LARAVEL-IMPLEMENTATION-PLAN.md §2) |
| Seeders | **6** | RolesAndPermissions, AdminUser, Settings, PricingTiers, Pages, Services |
| Mail Classes | **3** | AdminInvite, ContactInquiryAck, NoticePublished |

---

## Part 8 — Implementation Roadmap (Step-by-Step, Trackable)

> **How to use this checklist:** Work top to bottom. Each numbered step is
> small enough to build, test, and commit on its own. Check the box when the
> **Verify** command/action for that step passes — not before. If a step
> breaks something, the **Fix** note tells you the fastest way to isolate it.
> Nothing here is code yet; this is the checklist to follow *when* coding
> starts. Static reference throughout: the files in `public/` and `admin/`
> are the visual/functional spec for every screen below.

### Phase 0 — Environment Setup (LAMPP + Laravel + MySQL)

- [ ] **0.1 Install LAMPP** (Linux XAMPP bundle: Apache + MySQL/MariaDB + PHP + phpMyAdmin).
  ```bash
  # Download the latest LAMPP installer for Linux from Apache Friends, then:
  chmod +x xampp-linux-*-installer.run
  sudo ./xampp-linux-*-installer.run
  sudo /opt/lampp/lampp start        # starts Apache + MySQL
  ```
  **Verify:** `sudo /opt/lampp/lampp status` shows Apache and MySQL both running.
  **Fix:** if MySQL fails to start, check `/opt/lampp/var/mysql/*.err` for a
  port conflict with a system MySQL/MariaDB (`sudo systemctl stop mysql`).

- [ ] **0.2 Install PHP CLI + Composer** (if not already on PATH; LAMPP's PHP
  is under `/opt/lampp/bin/php` but a system PHP ≥ 8.2 + Composer is easier
  for `artisan` day-to-day).
  ```bash
  php -v            # confirm >= 8.2
  composer --version
  ```
  **Verify:** both commands print a version, no errors.

- [ ] **0.3 Create the MySQL database + dedicated app user** (don't use root
  in `.env`).
  ```bash
  /opt/lampp/bin/mysql -u root -p <<'SQL'
  CREATE DATABASE ndc_bcc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  CREATE USER 'ndc_app'@'localhost' IDENTIFIED BY 'CHANGE_ME_STRONG_PASSWORD';
  GRANT ALL PRIVILEGES ON ndc_bcc.* TO 'ndc_app'@'localhost';
  FLUSH PRIVILEGES;
  SQL
  ```
  **Verify:** `mysql -u ndc_app -p ndc_bcc -e "SELECT 1;"` returns `1`.

- [ ] **0.4 Scaffold the Laravel app** (name suggestion: `ndc-backend`, kept
  as a sibling folder to this static prototype, not inside it).
  ```bash
  composer create-project laravel/laravel ndc-backend
  cd ndc-backend
  cp .env.example .env
  php artisan key:generate
  ```
  **Verify:** `php artisan --version` runs inside `ndc-backend/`.

- [ ] **0.5 Point `.env` at the LAMPP MySQL instance.**
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=ndc_bcc
  DB_USERNAME=ndc_app
  DB_PASSWORD=CHANGE_ME_STRONG_PASSWORD
  ```
  **Verify:** `php artisan migrate:status` connects without a "could not
  find driver" or "connection refused" error (it will say "No migrations
  found" the first time — that's fine, it proves the DB connection works).
  **Fix:** connection refused → LAMPP MySQL isn't running (`lampp status`);
  access denied → re-check step 0.3's `GRANT`.

- [ ] **0.6 Run the app locally and confirm the default Laravel welcome page
  loads**, before any custom code is added.
  ```bash
  php artisan serve   # http://127.0.0.1:8000
  ```
  **Verify:** default Laravel page renders in a browser.
  **Fix:** port 8000 busy → `php artisan serve --port=8001`.

- [ ] **0.7 Copy static assets into the Laravel public disk** so the exact
  same downloadable files/images are served without re-uploading:
  ```bash
  cp -r ../files/assets/docs ./storage/app/public/docs
  php artisan storage:link
  ```
  **Verify:** `http://127.0.0.1:8000/storage/docs/portfolio/NDC-Service-Portfolio.pdf`
  downloads the real file.

---

### Phase 1 — Database Schema (all 10 models, no business logic yet)

- [ ] **1.1** Create all 10 migrations from Part 2 (`User`, `Page`, `Notice`,
  `Service`, `TeamMember`, `PricingTier`, `ContactInquiry`, `MediaAsset`,
  `Setting`, `AuditLog`).
  ```bash
  php artisan make:model Page -m
  php artisan make:model Notice -m
  php artisan make:model Service -m
  php artisan make:model TeamMember -m
  php artisan make:model PricingTier -m
  php artisan make:model ContactInquiry -m
  php artisan make:model MediaAsset -m
  php artisan make:model Setting -m
  php artisan make:model AuditLog -m
  # User migration already exists — edit it instead of generating a new one
  # (add is_admin boolean, role, status columns)
  ```
- [ ] **1.2** Run migrations.
  ```bash
  php artisan migrate
  ```
  **Verify:** `php artisan migrate:status` shows all 10 (11 incl. `users`)
  as `Ran`. Cross-check column list against phpMyAdmin or
  `php artisan db:table pricing_tiers`.
  **Fix:** a bad migration fails loudly and stops the batch — fix that one
  file and re-run `php artisan migrate` (already-applied ones are skipped).
  To start over cleanly: `php artisan migrate:fresh` (drops all tables —
  only ever run this against the dev DB, never anything with real data).

- [ ] **1.3** Define Eloquent relationships + casts on each model per Part 2
  (e.g. `PricingTier.specs` cast to `array`, `Page.content_blocks` cast to
  `array`, `User.is_admin` cast to `boolean`).
  **Verify:** `php artisan tinker` → `PricingTier::factory()->make()->specs`
  returns an array/null without a cast error.

---

### Phase 2 — Admin Authentication (invite-based, no public accounts)

- [ ] **2.1** Build `AuthService` (login/logout/completeInvite) per §6.2–6.3.
- [ ] **2.2** Build `AdminAuthController`, wire routes `/admin/login`,
  `/admin/invite/{token}`.
- [ ] **2.3** Add `CheckAdminRole` middleware, apply to `/admin/*` route group.
- [ ] **2.4** Seed one `super_admin` user for local testing.
  ```bash
  php artisan make:seeder AdminUserSeeder
  php artisan db:seed --class=AdminUserSeeder
  ```
  **Verify:** log in at `/admin/login` with the seeded credentials, land on
  `/admin/dashboard`; hitting `/admin/dashboard` while logged out redirects
  to `/admin/login`.
  **Fix:** stuck in a redirect loop → check the middleware isn't also
  applied to the login route itself.

- [ ] **2.5** Convert `admin/login.html` into a Blade view wired to the real
  route/`@csrf`. Convert `public/login.html` (Account Access page) into a
  Blade view too — its Admin/CMP buttons are static links, only the message
  box form needs a real `action=""` (see Phase 9).
  **Verify:** logging in with a `status: 'pending'` (not-yet-invited-complete)
  or `status: 'suspended'` account is rejected with a clear message.

---

### Phase 3 — Site Settings + Public Layout Shell

- [ ] **3.1** Build `SettingService` + `Setting` seeder pre-populated with
  every field currently hardcoded in `admin/settings.html` (site_name,
  helpdesk_phone, ticker_message, logo media IDs, feature toggles).
- [ ] **3.2** Convert the shared header/nav/footer markup (identical across
  all 11 `public/*.html` files) into `layouts/public.blade.php`, pulling
  phone/email/address through the `setting()` helper instead of hardcoded text.
- [ ] **3.3** Convert `admin/*.html`'s shared sidebar/topbar into
  `layouts/admin.blade.php` the same way.
  **Verify:** load the Laravel-rendered homepage side-by-side with
  `public/index.html` — header, nav, footer must be pixel-identical. Change
  a `Setting` row in the DB and confirm the phone number updates on refresh
  without a code change.
  **Fix:** if Blade output diverges from the static HTML, diff the rendered
  HTML source against the static file rather than eyeballing the browser.

---

### Phase 4 — Pricing (highest content-accuracy risk — 60+ real rows)

- [ ] **4.1** Write the `PricingTier` seeder from the **current**
  `public/ndc_pricing_cloud.html` and `public/ndc_pricing_request.html`
  tables (the full-parity rewrite done 2026-07) — not from Part 1/2's
  original examples, which predate that rewrite. `tier_key` values don't
  exist as `data-tier-id` in the current HTML (that attribute was never
  added) — assign new stable slugs (e.g. `ecs-x-small`, `rbs-vps-basic`) and
  keep a mapping comment in the seeder.
- [ ] **4.2** Build `PricingService` (`getForPage`, `updatePrice`,
  `toggleVisibility`, `addTier`/`deleteTier`) + `PricingController`.
- [ ] **4.3** Convert `public/ndc_pricing_cloud.html` +
  `ndc_pricing_request.html` to Blade, looping tiers from the DB instead of
  static cards/table rows.
- [ ] **4.4** Wire `admin/pricing-cloud.html` + `pricing-request.html`'s
  inline edit/save buttons to a real `PUT /api/admin/pricing/{tier_key}`.
  **Verify:** edit one price in the admin screen, reload the public pricing
  page in another tab, confirm the new price shows. Toggle a tier's
  visibility off, confirm its card disappears from the public page.
  **Fix:** price shows on public page but not admin (or vice versa) → check
  both read through `PricingService::getForPage()`, not two different queries.

---

### Phase 5 — Notices & Circulars

- [ ] **5.1** Build `NoticeService` (`createOrUpdate`, `bulkAction`,
  `getPublic`) + `AdminNoticeController` + public `NoticeController`.
- [ ] **5.2** Seed the categories used by `.cat-*` CSS classes (maintenance,
  services, tender, policy, security, general).
- [ ] **5.3** Convert `admin/notices.html` (list + bulk actions) and
  `public/ndc_notices.html` (public list, now horizontally-scrollable table
  per the 2026-07 responsive fix — keep that wrapper in the Blade version).
  **Verify:** publish a notice in admin, confirm it appears on the public
  page ordered by `published_at DESC`; set one to `draft`, confirm it
  disappears from the public list but still shows in admin.

---

### Phase 6 — Page CMS (About / Contact / Policies / Forms)

- [ ] **6.1** Build `ContentService` (`getBlocks`, `updateBlocks` on
  `Page.content_blocks` JSON).
- [ ] **6.2** Seed 4 `Page` rows (`about`, `contact`, `policies`, `forms`)
  with `content_blocks` matching what's currently hardcoded in
  `public/ndc_about.html`, `ndc_contact.html`, `ndc_policies.html`,
  `ndc_forms.html`.
- [ ] **6.3** Wire the 4 dedicated admin editors built 2026-07
  (`admin/page-about.html`, `page-contact.html`, `page-policies.html`,
  `page-forms.html`) to real `PUT /api/admin/pages/{slug}` endpoints — these
  screens already exist and already match this exact field structure, so
  this step is "connect the form," not "design the form."
- [ ] **6.4** Convert the 4 public pages to Blade, rendering from
  `content_blocks` (history timeline, mission/vision, policy list, form
  category list) instead of static markup.
  **Verify:** edit the Vision quote in `admin/page-about.html`, confirm it
  changes on `public/ndc_about.html` (Blade version) after save.

---

### Phase 7 — Services Catalog, Team, Homepage Builder

- [ ] **7.1** `Service` model + seeder from `public/ndc_services.html`'s
  service cards; `AdminServiceController` wired to `admin/services.html`.
- [ ] **7.2** `TeamMember` model + seeder from the roster on
  `public/ndc_about.html` (leadership + technical staff, `display_order`
  preserved); `AdminTeamController` wired to `admin/team.html`.
- [ ] **7.3** Wire `admin/homepage.html`'s hero/ticker/stat-bar/featured-services
  fields to `ContentService` (homepage is `Page` slug `home`).
  **Verify:** toggle a service's `is_featured` off in `admin/services.html`,
  confirm it drops off the homepage's featured grid but still shows on the
  full `public/ndc_services.html` catalog.

---

### Phase 8 — Service Order Procedure Page (informational, no backend)

- [ ] **8.1** Convert `public/ndc_services.html`'s Service Order Procedure
  section to Blade. There's no submission endpoint to build — the Cloud and
  Request-Based tabs' CTA buttons link straight to `https://cmp.bcc.gov.bd`
  (`target="_blank"`), and the SSL/VPN tab links to `https://www.bcc-ca.gov.bd/`
  plus the existing downloadable `NDC-VPN-Access-Form.pdf`.
  **Verify:** all three outbound links resolve (CMP, BCC CA, the VPN form
  PDF); no `/api/service-orders`-style route exists anywhere in `routes/web.php`.

---

### Phase 9 — Contact Inquiries + Mail + Abuse Protection

- [ ] **9.1** Wire `public/ndc_contact.html`'s form (already lists the exact
  `inquiry_type` option set — reuse verbatim) and `public/login.html`'s
  message box (Account Access page) to `ContactInquiry::create()`, both
  through the same `ContactController` route.
- [ ] **9.2** Build the 3 mail classes from Part 7 (`AdminInviteMail`,
  `ContactInquiryAckMail`, `NoticePublishedMail`), point `MAIL_MAILER=log`
  in local `.env` so nothing real sends yet.
  **Verify:** submit the contact form, confirm a row in `contact_inquiries`
  and a rendered email body appears in `storage/logs/laravel.log`.
  **Fix:** email not logged → check `config/mail.php` matches `.env`, not a
  cached config (`php artisan config:clear`).
- [ ] **9.3** Apply `throttle:5,1` middleware to the `/api/contact-inquiries`
  route. Add real CAPTCHA verification (reCAPTCHA v2 or hCaptcha) required
  when the "already submitted once" cookie (set on first success) is present
  — see §6.7. Wire it to replace the static-HTML `localStorage` mock.
  **Verify:** submit the form twice in a row from the same browser; the 2nd
  submission is rejected server-side without a valid CAPTCHA token, even if
  the frontend checkbox is bypassed via devtools.

---

### Phase 10 — Media Library

- [ ] **10.1** Build `MediaService` (`store`, `delete`, `getPublicUrl`) +
  `AdminMediaController`, wired to `admin/media.html`'s upload form and the
  file list (which already needs its 3 mock entries — including the
  corrected `NDC-Service-Portfolio.pdf, 67MB` — replaced with real
  `MediaAsset` rows once uploads exist).
- [ ] **10.2** Point the `assets/docs/forms/*` and `assets/docs/portfolio/*`
  files copied in step 0.7 at real `MediaAsset` rows (one seeder row per
  existing file, `storage_path` pointing at the already-copied file) so
  `admin/page-forms.html` and `page-policies.html`'s "attach a file" actions
  have real records to pick from immediately.
  **Verify:** every download button on the live-rendered `ndc_forms.html`
  and pricing pages resolves through `MediaService::getPublicUrl()`, not a
  hardcoded `assets/docs/...` path.

---

### Phase 11 — Admin User Management

- [ ] **11.1** Build `UserManagementService` (`invite`, `suspend`,
  `delete`, `list`) + `AdminUserController`, wired to `admin/users.html`.
  **Verify:** invite a test admin user through the real UI, complete the
  invite link (sets password), confirm they can then log in at
  `/admin/login` with the assigned role's permissions and not more.

---

### Phase 12 — Audit Log + Dashboard

- [ ] **12.1** Build `AuditService`, call `record()` from the end of every
  other service method touched in Phases 4–11 (pricing update, notice
  publish, page save, admin-user invite/suspend, etc.).
- [ ] **12.2** Wire `admin/dashboard.html`'s stat cards (including "Admin
  Users") and "Recent Activity" feed to real counts/`AuditLog::recent()`.
  **Verify:** perform one action from each phase above, confirm each shows
  up in the dashboard activity feed in the correct order with a correct
  `action` string (`notice.publish`, `pricing.update`, `user.invite`, etc.).

---

### Phase 13 — Cross-Cutting QA Pass

- [ ] **13.1** Re-run the full static-site checks that were already
  automated against the HTML prototype and must still hold against the
  Blade output: zero broken internal links/anchors, zero undefined CSS
  classes referenced, HTML validates.
- [ ] **13.2** Re-test responsive behavior (2026-07 pass: header/nav on
  narrow viewports, all data tables horizontally scrollable with no
  functionality hidden behind `display:none`, all grids collapse) — this
  time in an actual mobile viewport against the live Laravel app, not just
  the static file.
- [ ] **13.3** Run through the full admin auth flow (invite → complete →
  login), one notice publish, one price change, one page-content edit, and
  one Contact/Account-Access form submission (including the 2nd-submission
  CAPTCHA gate) — the "golden path" — start to finish with no manual DB edits.
- [ ] **13.4** Confirm `SESSION_DRIVER`, `MAIL_MAILER`, `FILESYSTEM_DISK`
  are all switched from local/log/dev values to their production
  equivalents before deployment (see Part 6.6, Part 5.1).

---
