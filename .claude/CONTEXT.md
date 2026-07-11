# Project Context — NDC Portal v1

## What this is
The NDC Bangladesh website: an **advertising-only** public-facing portal plus
an admin CMS panel. It does not sell, take, or track orders — real ordering
and account management happen on the external NDC Cloud Management Portal
(`cmp.bcc.gov.bd`, in testing) and, for SSL/VPN certificates, the BCC
Certifying Authority (`bcc-ca.gov.bd`).

**2026-07-11: converted from static HTML prototype to a real Laravel app**,
in place, per `LARAVEL-DYNAMIZATION-PLAN.md`'s roadmap — **all 13 phases
built and verified end-to-end** (not just scaffolding: every public page and
every admin CRUD screen was actually exercised via curl through real HTTP
requests — login, create/edit/delete, file upload, cache invalidation, audit
logging — not just "the route exists"). The directory is now a standard
Laravel project root (`app/`, `routes/`, `resources/views/`, `database/`,
`public/` = Laravel's web root with `index.php`, etc.) plus Jetstream
(Livewire stack, Teams + registration enabled — see below). The original
static HTML/CSS site was moved wholesale into `_static-prototype/` before
scaffolding, untouched, as the visual/functional reference each page was
converted from — **nothing was deleted**.

## Layout
```
_static-prototype/  — the original static site (public/, admin/, assets/),
                       kept as reference. Every page in it has now been
                       converted into a Blade view — safe to consult for
                       design intent, but no longer the source of truth for
                       content (the database is, now).
app/Http/Controllers/Public/   — public site controllers (Home, About,
                       Contact, Services, Pricing, Notices, Policy, Forms,
                       AccountAccess, ContactInquiry)
app/Http/Controllers/Admin/    — admin panel controllers (Auth, Dashboard,
                       Notice, Page, Pricing, Team, Service, Media, User)
app/Services/          — business logic layer (AuthService, AuditService,
                       UserManagementService, SettingService, ContentService,
                       NoticeService, PricingService, MediaService) — see
                       LARAVEL-DYNAMIZATION-PLAN.md Part 3 for what each does
app/Models/             — all 10 Eloquent models (User, Page, Notice, Service,
                       TeamMember, PricingTier, ContactInquiry, MediaAsset,
                       Setting, AuditLog)
resources/views/components/admin-layout.blade.php, public-layout.blade.php
                     — shared chrome (sidebar/topbar, header/nav/footer),
                       used via <x-admin-layout> / <x-public-layout>. NOTE:
                       these live in components/ not layouts/ — Laravel's
                       anonymous-component auto-discovery only scans
                       resources/views/components/. Jetstream's own
                       <x-app-layout>/<x-guest-layout> work differently (real
                       PHP classes in app/View/Components/), which is *why*
                       naively mirroring that pattern into layouts/ broke on
                       first render — don't repeat that mistake.
resources/css/public.css, admin.css — hand-extracted from every static
                       page's inline <style> block, consolidated into two
                       Vite-compiled stylesheets (not 26 separate inline
                       blocks like the static prototype had).
database/seeders/      — PagesSeeder, ServicesSeeder, NoticesSeeder,
                       PricingTiersSeeder (reads database/seeders/data/pricing_tiers.json,
                       extracted from the static pricing pages via a Python
                       HTML parser — 24 real tiers, not the full 60+ the
                       original had; a full audit against the static pages
                       would be a good follow-up if pixel-exact pricing
                       parity matters), TeamMembersSeeder, SettingsSeeder,
                       AdminUserSeeder (needs ADMIN_SEED_PASSWORD in .env).
```

## Known gotchas hit while building this — read before touching caching or PHP setup
- **PHP's `mbstring` extension is not installed** (`php -m | grep mbstring`
  returns nothing). This breaks `Str::limit()`, `Str::title()`, `Str::upper()`,
  `mb_*` functions, and Fortify's own login-throttle key generation
  (`app/Providers/FortifyServiceProvider.php` calls `Str::lower()` — a latent
  bug in the untouched Jetstream scaffold, not something we introduced).
  Wherever this codebase needed similar behavior, plain non-mb functions were
  used instead (`substr`, `strtoupper`, `ucwords`, `Str::before`/`Str::after`
  — those don't route through mb_* internally). If `sudo apt install
  php8.5-mbstring` still hasn't been run, budget for this to bite again in
  new code — grep for `Str::title\|Str::upper\|Str::lower\|Str::limit\|mb_`
  before shipping a new Blade view.
- **Never cache raw Eloquent model/Collection instances** with the
  `database` (or `file`) cache driver and expect it to be reliable across
  process boundaries — `PricingService::getForPage()` originally did
  `Cache::remember($key, ..., fn () => $query->get()->groupBy(...))` and it
  intermittently 500'd with `TypeError: ... __PHP_Incomplete_Class returned`
  after a fresh `php artisan serve` restart, non-deterministically. Fixed by
  caching plain attribute arrays (`->map->getAttributes()->all()`) and
  rehydrating via `PricingTier::hydrate($rows)` after the cache read. If you
  add caching to another service (ContentService, SettingService, etc.),
  follow the same array-cache-then-hydrate pattern, not "cache the model."
- `CACHE_STORE=database` in `.env` — the `cache` table, not files/Redis.

## Local dev environment
- **LAMPP** at `/opt/lampp` provides Apache + MariaDB 10.4. Starting/stopping
  it requires `sudo /opt/lampp/lampp start|stop` — Claude cannot run this
  itself (no non-interactive sudo in this environment), so **you** need to
  run it when the DB isn't reachable. `lampp status` is unreliable — it often
  reports "MySQL is not running" due to a stale PID file it can't clean up
  without root, even when MySQL is actually up. Verify with:
  `/opt/lampp/bin/mysql -u root -e "SELECT 1;"` instead of trusting `status`.
- **Composer** installed to `~/.local/bin/composer` (no system-wide/root
  install — added to PATH via `~/.bashrc`/`~/.profile`).
- **Node/npm** installed via `nvm` (`~/.nvm`) — not a system package. Run
  `nvm use default` (or just open a fresh shell) before `npm` commands if a
  shell doesn't have it on PATH yet.
- **Database**: `ndc_bcc`, user `ndc_app`@`localhost`. Password stored in
  `.claude/.db_password_do_not_commit` (also already in `.env`) — never print
  this file's contents or `.env`'s `DB_PASSWORD` line to a terminal/transcript.
- **Run the app**: `php artisan serve --port=8010` (8000 was already taken by
  something else on this machine when first tested — check before assuming
  8000 is free).

## Jetstream (2026-07-11) — Teams + public registration are ON, by explicit choice
Jetstream was installed with `php artisan jetstream:install livewire --teams`
and Fortify's default features (registration, password reset, profile
updates, 2FA) all left enabled — `config/jetstream.php` and
`config/fortify.php` are untouched from Jetstream's defaults.

**This looks like it contradicts the "no public accounts / invite-only admin"
design below — it's not an oversight.** When asked explicitly whether to
disable Teams/registration to match that design, the user chose to keep
Jetstream's defaults instead. So as of now: Jetstream's own `/register`,
`/login`, team-management routes are live and functional, separate from
whatever the plan doc's admin-only invite flow eventually becomes. If a
future session needs to reconcile these two designs (e.g. gate `/register`
behind something, or repurpose Jetstream Teams as the admin/staff account
system instead of building a separate one), that reconciliation hasn't
happened yet — ask before assuming either direction.

## Ordering / accounts — now live in Laravel, not just static HTML
- No public customer login/register on our own site. `GET /login` (route
  name `login`, `Public\AccountAccessController@show`) is the "Account
  Access" page: admin login link, external CMP link, and a plain message
  form that posts to `POST /contact-inquiries` (shared with the Contact
  page's form — same `ContactInquiry` model, `source` column tells them
  apart).
- Both of those forms require no account, and both show a CAPTCHA checkbox
  from the **2nd submission onward** — enforced twice: client-side via
  `resources/js/nav.js` (`localStorage`-based, cosmetic) and server-side in
  `Public\ContactInquiryController::store()` (a `ndc_has_submitted` signed
  cookie gates a `captcha_verified` field with real `Validator` rules —
  verified by curl-testing that a 2nd submission without the field is
  actually rejected, not just hidden in the UI).
- `admin/users` (`Admin\UserController`, `UserManagementService`) manages
  NDC staff/admin accounts (`is_admin` + `role` columns on `User`), created
  only via Super Admin invite (`AdminInviteMail` + a signed token, see
  `AuthService::completeInvite()`) — never self-registration.
- `GET /services` (`Public\ServicesController`) — the Service Order
  Procedure section is informational only: Cloud/Request-Based tabs link out
  to `setting('cmp_portal_url')`; the SSL/VPN tab links to
  `setting('bcc_ca_portal_url')` plus a downloadable VPN Access Form served
  from `storage/app/public/docs/forms/` (symlinked at `public/storage`).

## Known gaps / decisions (post-Laravel-conversion)
- **Policies/Forms/About/Contact admin editing is a generic JSON textarea**,
  not a tailored form per field (`Admin\PageController` + `admin/pages/edit.blade.php`
  — edits `Page.content_blocks` as raw pretty-printed JSON). Functional and
  safe (validates as JSON before saving), but not as friendly as the
  static prototype's mocked-up field-by-field editors. A real per-page
  editor UI would be a good follow-up if content editors find raw JSON
  awkward.
- **PricingTiersSeeder has 24 of the original ~60+ pricing rows** — real
  prices/specs pulled from the static HTML via a Python parser, covering
  the biggest categories (Cloud ECS/Storage/Backup/Network, Request-Based
  VPS), not every single row from every section. The pricing *system*
  (model, service, public display, admin edit, cache invalidation, audit
  log) is fully real and correct; the seed *data* just isn't 100% exhaustive.
  See `database/seeders/data/pricing_tiers.json`.
- **Team member photos, notice PDF attachments, logo settings** — the
  `MediaAsset` model + upload/delete flow is fully built and working
  (Phase 10), but nothing currently references a `MediaAsset` from a
  `TeamMember.photo_media_id`, `Notice.attachment_media_id`, or the
  `logo_*_media_id` settings — those FK columns exist and work, they're
  just unpopulated. Uploading via `/admin/media` and wiring one in via
  tinker or a future admin form would exercise the full path.
- Decorative content (hero/about SVG illustrations, certification badge
  SVGs, quick-link icons) was kept as **static Blade markup**, not modeled
  in the database — only the plan-mandated dynamic pieces (hero text,
  stat bar, featured services, notices, pricing, page content_blocks) are
  DB-driven. Re-check `resources/views/public/*.blade.php` before assuming
  something is admin-editable.

## Renames log
- 2026-07-11: `BACKEND-DESIGN-PLAN.md` → `LARAVEL-DYNAMIZATION-PLAN.md`
  (root planning doc). Confirmed via full-project audit: 16 stale inline
  `<!-- BACKEND: ... see BACKEND-DESIGN-PLAN.md -->` comments were found
  across 12 HTML files in `public/` and `admin/notices.html`, all now fixed
  to point at the new filename. A separate stale `admin-notices.html`
  self-reference in `admin/notices.html:1011` (pre-dating the `admin-`
  prefix drop) was also fixed.

## Not a git repo
This directory has no `.git`. There's no commit history to lean on —
file-rename history has to be tracked manually here until/unless the repo
is initialized.
