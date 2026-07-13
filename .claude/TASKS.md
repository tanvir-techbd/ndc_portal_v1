# Task Tracker — NDC Portal v1

Running log of work items. Newest entries at the top. Mark items
`[ ]` open, `[x]` done, `[~]` in progress.

## 2026-07-12 (evening) — Admin "Site Pages" list: Edit button was wrapping onto two lines
User: Site Pages admin listing looked "a little broken." Screenshot showed
why: its Edit link used `.row-action-btn` (a fixed 28×28px icon-only square,
used everywhere else in the admin panel — Notices, Team, Pricing, Services,
Messages all use it icon-only) but this was the one place that put actual
text ("✏️ Edit") inside it, so the word wrapped onto a second line below the
icon in the narrow cell. Confirmed via grep it was the only such usage
project-wide. Fixed by dropping the text (icon-only, matching every other
admin table) and wrapping it in the standard `.row-actions` div. Also added
a second icon — a "View live page" link (`route($page->slug)`, which works
directly since every page slug matches its public route name) — since the
row otherwise felt sparse with only one action and a quick preview link is
genuinely useful for a content editor. Verified all 5 view-links resolve
to the correct live URL and a full HTTP+error sweep stayed clean.

## 2026-07-12 (later afternoon) — "Ordering" nav dropdown's sub-tabs didn't switch on same-page clicks
User: "Request Based Service Order" under the Ordering nav dropdown doesn't
work. Reproduced with a scripted click (CDP): the URL hash correctly
updated to `#procedure-request`, but the visible tab/panel never changed.

Root cause: `services.blade.php`'s procedure-tab deep-link handler
(`#procedure-cloud` / `#procedure-request` / `#procedure-ssl`, added in an
earlier session for the Forms→Services cross-link) only ran once, at
initial page load. A hash-only link click on a page you're *already on*
(e.g. clicking "Ordering → Request Based Service Order" while already on
`/services`, or the in-page "Request-Based Order" CTA button lower down)
fires a `hashchange` event but never reloads the page — so the load-only
check silently missed every same-page click. This affected all three
Ordering sub-items and the on-page CTA link, not just the one the user
happened to hit.

Fix: extracted the hash-check into `handleHash()`, called once on load (as
before) and again on `window.addEventListener('hashchange', handleHash)`.
Verified via scripted clicks (CDP): fresh cross-page navigation to all 3
`#procedure-*` hashes ✓, same-page nav-dropdown click ✓, same-page CTA
button click ✓, direct tab-button clicks (regression check — these don't
touch the hash at all) still work ✓, zero console errors/exceptions during
any of it. Full site HTTP+error-log+CSS-class sweep afterward: clean.

## 2026-07-12 (afternoon) — CRITICAL: stale Vite build was silently discarding hours of CSS work; site-wide design/responsive audit
User: navbar active-state broken, admin panel design broken in places, needs a full
device-responsiveness pass, "professional and nice" design, and a check for
backend un-functionality. This turned into the most consequential fix of
the project so far.

**Root cause #1 (huge): `npm run build` had not been run since 2026-07-11
20:00.** Every `resources/css/{public,admin}.css` edit made in every session
since then — the entire pricing-table redesign, the structured page editor,
the services catalog rewrite, the font-family reset, the about-page photo,
literally everything — was sitting in source only. `@vite()` serves the
compiled `public/build/assets/*` bundle (hashed filenames from
`manifest.json`), not the source files, and nothing in this environment
watches/rebuilds automatically (no `npm run dev` process, confirmed via
`ps aux`). Diffing class names against source `.css` files (this project's
established audit method) never caught this, because the source *did*
contain the classes — the served bundle just didn't. Confirmed by grepping
for `.pt-order-btn` etc. in the actual built CSS file (0 matches) vs. source
(2 matches), and by a visual screenshot showing "Order →" rendering as
plain blue link text instead of the intended green button. Ran
`npm run build`; every subsequent screenshot confirmed the real, intended
styling. **This must not be allowed to happen again** — any future CSS/JS
change is inert until `npm run build` is re-run; there is no dev-server
watching in this environment.

**Root cause #2: navbar active-state was only ever wired for "Home."**
`request()->routeIs(...)` was applied to exactly one of nine desktop nav
items (and *zero* of nine mobile items, not even Home) — everywhere else,
`.nav-links > li.active > a` (a real, styled state) just never got its
class. Wired `routeIs()` per item on both desktop and mobile nav (About →
`about`, Services → `services`, Pricing → `pricing.*`, Forms/Policies/
Notices/Contact → their own route) — "Ordering" intentionally stays
unhighlighted even on `/services`, matching the original static
prototype's behavior exactly (confirmed against
`_static-prototype/public/*.html`, which hardcoded `class="active"`
per-page and never marked the "Ordering" dropdown).

**Root cause #3 (found via visual screenshot, not code review): mobile nav
hid its own first two items behind the page header.** `header` is
`position:sticky; z-index:200`; `.mobile-nav` (the slide-in drawer) was
`z-index:145` and `.mobile-nav-overlay` was `z-index:140` — both *below*
the header, so the header painted on top of the open drawer's top ~130px,
completely hiding "Home" and mostly hiding "About NDC" every time a mobile
user opened the menu. Bumped both above the header (205 / 210). This has
presumably been broken since the mobile nav was first built — a real,
severe, previously-invisible bug (screenshots were the only way to catch
it; a DOM/class audit alone reported both `<li>` elements as present and
"correct").

**Root cause #4: `.abtn` (admin button component) had no default
color/background.** Every bare `class="abtn"` (no `abtn-primary`/
`-secondary`/`-danger` modifier) — every "Cancel" link across every admin
form, "Reply by Email," "Back to Messages," and the new Messages status
filter tabs — rendered as unstyled browser-default blue link text instead
of a button. Gave the base `.abtn` a neutral/secondary look by default
(white bg, gray border, gray text) so an unmodified `.abtn` always looks
like a button.

**Also fixed while auditing:**
- Removed a dead, unused "Comparison table" CSS block in `public.css`
  (`.tier-col`/`.price-cell`/`.feature-name`, extracted from the static
  prototype long ago, never used by any current Blade view) that
  coincidentally reused the exact `.pricing-table`/`.pricing-table-wrap`
  class names as this session's new tabular pricing page, silently
  overriding a couple of properties (e.g. `min-width`).
- Added a subtle right-edge fade-gradient hint to `.admin-table-wrap` and
  `.pricing-table-wrap` on narrow viewports — tables scroll horizontally
  correctly (verified by scripted scroll+screenshot) but gave no visual
  indication there was more to see.

**New capability this session: real headless-Chrome screenshots.**
`google-chrome` is installed in this environment. Used
`--headless=new --screenshot` for logged-out pages, and a small custom CDP
script (`cdp_shot.mjs`, reusing an already-established Laravel session
cookie via `Network.setCookie` — never re-deriving or writing the admin
password to disk) for authenticated admin-panel screenshots at arbitrary
viewport sizes. This is a major upgrade over the "diff class names against
source CSS" method used everywhere earlier in this project — it would have
caught issues #1, #3, and #4 above immediately, whereas the class-audit
method structurally cannot (it checks presence of a class name, not
whether the styling is actually being served or visually correct). Prefer
this method for design/responsiveness verification going forward.

Verified via screenshots at desktop (1400px)/tablet (820px)/mobile (390px)
across ~10 public pages and ~6 admin pages, both logged-out and
authenticated, plus a full HTTP+error-log sweep (zero errors). No backend
functional bugs found beyond what's listed above.

## 2026-07-12 (later morning) — Homepage "Latest News" / "Official Notices" were the same list twice
User asked where these two homepage panels get updated from — investigating
found a real bug: `HomeController` called `NoticeService::latestPublic()`
twice with only the limit changed (4 vs 7), so both panels showed the exact
same most-recent notices, just one panel truncated shorter. The original
static prototype had these as two genuinely different content sets (News =
day-to-day announcements, Notices = formal circulars), lost in the Laravel
conversion. Fixed using the `category` field every notice already has (and
every admin notice form already lets you pick): added
`NoticeService::latestNews()` (categories: services, general) and
`latestOfficialNotices()` (categories: maintenance, tender, policy,
security). Both panels are still updated from the exact same place —
Admin → News & Notices (`admin/notices`) — the category chosen there now
determines which homepage panel a notice shows up in, instead of both
panels racing to show the same top-N rows. Verified the two panels now
render disjoint content and the rest of the site sweep stays clean.

## 2026-07-12 (morning) — Real server photo on the homepage About section
Replaced the hand-drawn SVG placeholder (stylized rack rectangles + a fake
"ICT Tower, Agargaon, Dhaka" caption) in the homepage's About NDC section
with an actual data center photo. Sourced from Wikimedia Commons — "Cable
racks at grid computing center, Fermilab with blue lights.jpg", a U.S.
Department of Energy work, public domain, no attribution required.
Downloaded, center-cropped to the section's ~480:340 aspect ratio, resized
to 960×680 and re-compressed (~120KB) with Pillow (no ImageMagick/GD
available in this environment), saved to
`public/images/about-datacenter-servers.jpg`. Dropped the old caption text
since it named NDC's actual facility — this photo isn't really NDC's site,
so keeping a location-specific caption would have been misleading; the
Tier-III badge overlay stays since that's a genuine NDC credential, not
tied to this specific image.

## 2026-07-12 (early morning) — News CRUD discoverability, Media Library wiring, Messages inbox, Services Catalog CRUD, Support/Contact merge, sitewide font fix
Large multi-part request. Investigated each claim before building anything —
two turned out to be existing-but-hidden features, three were genuine gaps:

- **"No option to create/edit/delete news"**: full CRUD already existed
  (`admin/notices/*`) — the sidebar just labeled it "Notices & Circulars,"
  which didn't read as "News" to the user since the public site calls the
  same content "News & Notices." Renamed the admin label to match
  (sidebar, page title, panel header) rather than building anything new.
- **"Media Library purpose unclear"**: real gap — uploads worked but nothing
  on the site ever consumed them; `TeamMember.photo_media_id` and
  `Notice.attachment_media_id` FK columns existed and were even wired at
  the model level (`belongsTo`) but no admin form had a field for them, and
  no public view rendered them. Added a media picker (dropdown of already-
  uploaded assets, filtered by category) to both the Team Member and Notice
  admin forms; the About page's team section now shows the uploaded photo
  instead of always falling back to initials; the Notices page's existing
  `Route::has('notices.download')` attachment-download link had never had
  a matching route/controller method — added both. Also added a short
  explainer block to the Media Library page itself pointing at where each
  category gets attached.
- **"Service catalog not editable"**: real gap, and the deepest one —
  `public/services.blade.php`'s entire detailed catalog (11 service cards
  across 7 groups: Cloud ×4, Hosting, Colocation, Managed, Email, VPS ×2,
  Backup) was hardcoded HTML, completely disconnected from the `Service`
  model/admin panel despite `admin/services` existing (it only had
  toggle-featured/toggle-visible, no create/edit/delete, and the public
  page never even queried the DB). Extended `Service` with `kind`
  (group/detail), `group_slug`, `tag`, `tiers`, `features` — the 6 existing
  homepage-teaser rows became `kind=group` (added a 7th, Backup & DR, which
  had no homepage summary before), and all 11 catalog cards were extracted
  verbatim into new `kind=detail` rows. Built full admin CRUD (create/edit/
  delete, one panel per group). Rewrote the public catalog section to
  render from the DB, with a small fixed SVG icon library
  (`public.partials.service-icon`, keyed by an admin-picked icon name —
  deliberately not raw admin-editable SVG, to avoid an HTML/XSS surface).
- **Admin "Messages" inbox** (`admin/messages`): didn't exist at all —
  `ContactInquiry` rows were being created by the Contact form but nothing
  admin-side could read them. Built index (paginated, sortable by name/
  status/date via column-header links, filterable by status) + a detail
  view that marks a message "in progress" on open and lets an admin set
  new/in_progress/resolved. Added a sidebar badge showing the unread count.
- **Support/Contact nav merge**: the nav had two separate items — "Support"
  linked to `contact#support`, an anchor that didn't exist anywhere on the
  page (dead link), and "Contact" linked to the same page plain. Merged
  into one "Support & Contact" item (desktop + mobile nav), retitled the
  Contact page to match, and added a "Technical Support Portal" card
  linking to `support.bcc.gov.bd` (new `support_portal_url` setting,
  defaults via the existing `setting()` fallback pattern — no admin
  Settings UI exists yet to edit it through, that's a separate pre-existing
  gap, not introduced here). The message form and contact details the user
  asked for were already on this page from an earlier session.
- **"Official font style and size should be maintained"**: found a real,
  sitewide bug while auditing — neither stylesheet had an
  `input, select, textarea, button, table { font-family: inherit }` reset,
  which browsers require explicitly (form controls don't inherit body font
  by default). Every form control across the *entire* site — every admin
  form, the contact form, pricing sort headers — was silently rendering in
  the OS default UI font instead of Inter. One-line fix in both
  `public.css` and `admin.css`.
- Verified via full HTTP + error-log sweep (10 public + 10 admin pages,
  zero errors) and the CSS class-existence audit method used throughout
  this project, plus live create → verify → delete round trips through the
  new Messages and Services CRUD.

**Lesson for next time**: "X isn't editable" is worth checking against the
actual admin routes/controllers before assuming a build is needed — two of
five complaints here were fully-built features hidden behind a label
mismatch, and building duplicates would have made the discoverability
problem worse, not better.

## 2026-07-11 (very late) — Replaced raw-JSON page editor with a structured form
User: editing site pages (`admin/pages/{slug}`) meant hand-editing a raw
`content_blocks` JSON blob in a textarea — wanted a real editor instead.
This was actually a known backlog item ("replace the generic JSON
content_blocks admin editor with tailored per-field forms").

Rather than hand-writing 5 bespoke forms (one per page slug — home, about,
contact, policies, forms — each with a different `content_blocks` shape),
built one **generic recursive form renderer** that introspects the shape of
whatever's in `content_blocks` and picks the right control automatically:

- Plain string → text input (or textarea if long/multiline).
- Boolean → checkbox (e.g. policies `sections[].requestable`).
- Sequential array of scalars (e.g. `hero_badges`) → one-item-per-line
  textarea.
- Sequential array of associative arrays (e.g. `timeline`, `stat_bar`,
  `core_values`, `certifications`, `departments`, `faqs`, policies
  `sections`) → a repeater of cards, one per existing row plus 2 blank
  "(new)" spares — leave a card fully blank to remove it on save, fill in a
  blank one to add an entry. No JS needed for add/remove.
- Associative (non-list) sub-array → recurses into labeled sub-fields.

New: `resources/views/admin/pages/partials/field.blade.php` (the recursive
renderer, included by itself for nesting) and
`PageController::reconstructValue()`/`isBlankRow()` (the inverse — walks
the *existing* `content_blocks` value as a shape template alongside the
submitted form data, so an untyped HTML POST round-trips back into the
exact original nested structure). The existing value is used only as a
template for shape/type, never as a fallback value, so edits and
additions/removals all take effect correctly. Row-level heterogeneity
(policies `sections` — only 6 of 10 have a `requestable` key) is handled by
using each existing row's *own* keys for that row, and the union of keys
across all rows only for the blank spare/new-entry cards.

Verified via a scripted browser-less round-trip (parse the rendered form →
resubmit unchanged → diff DB before/after): all 5 pages come back byte-for-byte
identical on a no-op save. Also tested a real edit (changed `home`'s
hero_eyebrow, confirmed it appeared on the live homepage immediately,
reverted) and a real add+remove (added a 6th FAQ, removed the 1st contact
department, confirmed counts/content changed correctly, reverted via
`PagesSeeder`). Zero errors across a full public+admin page sweep afterward.

## 2026-07-11 (later night) — Sub-nav active-state highlighting; shortened pricing table CTA
User: sub-nav bars jump to the right section but never mark which button
you're on, and the per-row "Order via CMP Portal" button text in the new
pricing tables was too verbose.

- **No active-state highlighting anywhere except Services**: the previous
  session added scroll-spy (`IntersectionObserver`) only to
  `services.blade.php`. Pricing, Policies, and Forms pages' `.pricing-tabs`
  bars, and the About page's subnav, never updated `.active` after the
  initial page load — clicking jumped correctly (that part already worked)
  but nothing then reflected where you'd scrolled to. Moved scroll-spy into
  a single shared script in `components/public-layout.blade.php` (runs on
  every public page, auto-detects any `.pricing-tabs`/`.subnav-links` bar
  present) instead of duplicating it per page, and removed the now-redundant
  copy from `services.blade.php` to avoid two observers fighting over the
  same links.
- **About page subnav was missing entirely** — same root cause as the
  Services page subnav found last session (present in
  `_static-prototype/public/ndc_about.html:783`, dropped during the Laravel
  conversion, never rebuilt). Restored it (History / Mission & Vision /
  Team / Certifications), which is also what made the "not marking the
  active button" bug visible there — there was no subnav to highlight yet.
- **Pricing table CTA button**: shortened from "Order via CMP Portal" /
  "Start Request on CMP" (fine for the one big banner at the bottom of the
  page) to a plain "Order →" for the per-row table button, repeated 66/37
  times — the longer label was disproportionate at that size and repetition.
  The bottom CTA banner keeps its fuller, page-specific label since it's a
  one-off with more room for context.
- Verified: HTTP + error-log sweep (zero errors) and CSS class-existence
  audit (zero new gaps) across all 10 public pages after both changes.

## 2026-07-11 (night) — Admin price field simplified; public pricing pages rebuilt as sortable tables
Follow-up: admin editing two redundant price fields (numeric + hand-typed
display string) was pointless busywork, and the card-grid pricing layout
was called out as unprofessional with "redundant texts" — customer wants to
scan/compare prices fast, which cards don't support well.

- **Admin price field**: added a `price_unit` column
  (migration `2026_07_11_213000_add_price_unit_to_pricing_tiers_table`).
  Admin form now has *Numeric Price* + a *Per* unit dropdown (/mo, /yr, /GB,
  /GB/mo, /core/mo, /vCPU/mo, /domain/mo, /account/mo, one-time, none) —
  `price_display` is auto-generated server-side via
  `PricingTier::formatDisplay()` (`PricingController::resolveDisplay()`) as
  "৳X,XXX.XX/unit", never hand-typed for the ~75/103 tiers that are a plain
  number. A *Custom Price Text* field remains, but only for the real
  non-numeric cases (28/103 tiers: "Contact for Quote," negotiable ranges,
  and 2 compound one-time-plus-yearly fees) — required when Numeric Price is
  blank, optional override otherwise. Backfilled `price_unit` on the 103
  seeded rows by parsing each existing `price_display` suffix.
- **Public pricing pages → tabular layout**: replaced the `.pricing-cards`
  card grid on `pricing.blade.php` with a `.pricing-table` per service-type
  section — one row per tier, dynamic spec columns (the union of `specs`
  keys actually used in that section, so e.g. Cloud ECS shows a "Resources"
  column while Colocation shows different columns), a Price column, and an
  Order button column. Removed the repeated "◆ Resources: ..." bullet-list
  styling (the "redundant texts" complaint) in favor of one value per cell.
  Tier and Price column headers are click-to-sort (vanilla JS, ascending/
  descending toggle) so a customer can sort a whole section by price in one
  click ("fast sortedly"); non-numeric prices sort to the bottom via a
  sentinel value. `forms.blade.php` and `policies.blade.php` still use the
  original `.pricing-card` grid — appropriate there since those are
  documents/sections, not comparable numeric line items.
- Verified: full HTTP + error-log sweep after both changes (zero errors),
  zero new missing-CSS-class findings, and an admin round-trip confirming
  (a) a plain numeric price auto-formats correctly on save/reload and
  (b) the one compound-fee tier's Custom Price Text override is preserved
  and correctly pre-filled on edit.

## 2026-07-11 (evening) — Pricing card polish, admin pricing CRUD, nav deep-linking, structural HTML fix
Follow-up user report after the content-completeness pass below: pricing cards
"not looking professional," admin pricing had no create option and showed
resource details badly, font sizes too big, sub-nav links required manual
scrolling instead of jumping to the right section/tab, and SSL Certificate &
VPN Access content wasn't linked to the VPN forms. Also hit a real 500/crash
while verifying. Root causes and fixes:

- **Pricing card badge overflow**: `pc-tier` (the small colored pill on each
  card) was reading `specs['type']`, a key that existed on only 1 of 103
  seeded tiers; for the other 102 it fell back to the *category* name
  (e.g. "Container Service (eGovCloud Kubernetes)", 40+ chars) crammed into
  a `.65rem` uppercase pill sized for words like "Basic"/"Standard". Fixed
  by reading `specs['tier']` (the actual short variant label, e.g.
  "x.Small", already present on 29 rows) and backfilling it for the other
  74 via script from each tier's name suffix, with ~15 explicit overrides
  for the handful of awkward multi-clause names. Badge color now maps to
  basic/standard/advance/premium/xlarge based on the label text instead of
  being hardcoded to "basic" for every card.
- **Price text overflow**: `.pc-price` was a fixed `2rem` bold regardless of
  content, but ~26 of 103 real prices are descriptive strings up to 47
  chars (e.g. "Similar to ECS, EBS & other cloud resource cost"), not short
  numbers — wrapped into unreadable 3-4 line blocks. Added length-tiered
  `.pc-price-md`/`.pc-price-sm` classes (1.3rem / 1.05rem) applied by
  `strlen($tier->price_display)` in the view.
- **Resource details ("specs") not showing nicely**: `.pc-specs li` was a
  rigid single-line flex row with a fixed-width label column — real spec
  values run up to 54 characters (e.g. "Dedicated — HA Server per Core with
  DR (max 1TB, BYOL)") and had no wrap handling, overflowing the card.
  Changed to a wrapping flex layout (`flex-wrap:wrap`, `word-break` on the
  value) so long values drop to their own line instead of overflowing.
- **Admin pricing had no create option and no specs visibility** — the
  admin table only showed Name/Price/Visible/Actions with an inline
  price-only edit form; specs were invisible and there was no "add new
  tier" path anywhere, even though `PricingService::addTier()`/`deleteTier()`
  already existed unused. Added full CRUD: `PricingController@create/store/
  edit/update` + routes, a shared `admin/pricing/form.blade.php` (matches
  the existing team-member create/edit pattern), a `+ Add Tier` button per
  service-type panel (pre-fills that group's `service_type`), and a
  Resource Details column in the index table. Specs are edited as plain
  `label: value` lines in a textarea (parsed server-side) rather than a
  fixed field set, since spec keys vary per category — documented that the
  `tier` line drives the badge.
- **SSL Certificate & VPN Access ↔ NDC VPN Service Forms had no link
  between them**: Services page explains the BCC CA + VPN procedure and
  only linked out to CMP/BCC CA and one PDF; Forms page has the full set of
  VPN documents (authorization letters, renewal process, activation guide)
  but never referenced the procedure explanation. Added reciprocal links:
  Services page's SSL/VPN block → `forms#vpn`, Forms page's VPN section →
  `services#procedure-ssl`.
- **Sub-nav links required manual scrolling to actually see the target
  content**: the site header + page subnav are both `position:sticky`
  (~176px combined height) but no target section anywhere had
  `scroll-margin-top` set, so every anchor jump landed the section directly
  behind the sticky bars — technically "jumped" but visually looked like
  nothing happened, forcing a manual scroll to see the heading. Added
  `scroll-margin-top` (176px desktop / 100px mobile, matching the sticky
  stack's actual height at each breakpoint) to `.section`, `.svc-anchor`,
  `.pricing-section`, and `.procedure-block`. Also found the Services page
  was missing its sub-nav entirely (present in the static prototype at
  `_static-prototype/public/ndc_services.html:744`, dropped during the
  Laravel conversion) — restored it along with the scroll-spy
  (`IntersectionObserver`) that highlights the active item, and added
  hash-based deep-linking (`#procedure-ssl` etc.) so the new cross-page
  links actually switch to the right tab and scroll to it, not just land on
  a hidden `display:none` tab panel.
- **Crash while verifying** (`HTTP 500` → dev server returning `HTTP 000`):
  `PricingService::getForPage()` caches plain attribute arrays (already
  fixed for the `__PHP_Incomplete_Class` issue in an earlier session), but
  `PricingTiersSeeder` truncate+reseed never flushed that cache — a stale
  cache entry from before a reseed could still be served, or in this case
  had gone stale enough to throw. Added `PricingService::flushCache()`
  (made public) called at the end of `PricingTiersSeeder::run()`, so a
  reseed can never leave a stale/mismatched cache entry behind again.
- **Structural HTML corruption in `services.blade.php`**: found while
  tracing the subnav work — a stray, malformed duplicate of the "Ready to
  Order" CTA block plus a second, never-closed `<footer id="site-footer">`
  fragment (partial copy of the real footer, which already comes from
  `public-layout.blade.php` and has no business being duplicated inline)
  was sitting between the real `</section>` and the page's closing
  `<script>` block, with a duplicate `id="cloud-order"` and a dangling
  extra `</section>` with no matching open tag. Likely leftover from a
  prior edit that appended instead of replacing. Deleted the entire
  27-line garbage fragment; verified `<footer>`/`<section>`/`id="cloud-order"`
  each now appear exactly where expected (once).
- Full verification after all of the above: HTTP 200 sweep across all 10
  public + 9 admin pages, zero `ERROR` log entries, zero new missing-CSS-class
  findings from the same audit method used in the earlier design-fix pass,
  and a live create → verify-on-public-page → update → delete round trip
  through the new admin pricing CRUD (cache correctly reflected the new
  tier immediately, cleaned up after).

**Lesson for next time**: when a spec/attribute map has an inconsistent key
across rows (only 1 of 103 rows actually had `specs['type']`), a `??`
fallback chain that lands on "use the whole name/category" instead of
failing loud will silently produce garbage for the 99% case — worth a quick
`grep`/count check on real seeded data before trusting a fallback path, not
just visually checking the first few rows.

## 2026-07-11 (later still) — Content completeness pass: logos, real pricing, missing docs
User reported logos missing, pricing incomplete vs. the live reference pages
(ndc.bcc.gov.bd `page_id=787` request-based, `page_id=902` cloud-based), many
PDFs/docs missing, and policy/agreement files missing — "downgraded vs. the
static prototype." Root causes and fixes:

- **Logos**: the public layout's `<a class="logo-item">` tags were built
  empty during the Blade conversion — the original's 3 logos (NDC, BCC, ICT
  Division) only ever existed as inline base64 in the static HTML, never
  extracted to real files. Extracted all 3 via a Python script into
  `public/images/logo-*.png`, wired into the header (all 3) and footer (all
  3, inverted per the existing but previously-unused `.footer-logos img`
  CSS rule).
- **Pricing was badly incomplete** (24 of ~100+ real tiers, from an earlier
  regex-based scrape of the static prototype). Fetched the actual live
  pricing tables from `ndc.bcc.gov.bd/?page_id=902` (Cloud Based Service)
  and `?page_id=787` (Request Based Service) — real WordPress HTML tables,
  not images — and rebuilt `database/seeders/data/pricing_tiers.json` from
  scratch: **103 tiers** (66 cloud incl. all 3 ECS families / storage /
  networking / PaaS app+db+messaging+monitoring / OpenShift / DR, 37
  request-based incl. VPS / email / managed+MySQL DB / private access /
  WAF / colocation / physical server / DNS / consultation). Added
  `serviceTypeLabels` entries for all 28 resulting service_type groups
  (both public and admin views), a category subnav (`.pricing-subnav`,
  matching a pattern the original site had that this codebase's pricing
  pages never got), and linked the real fee-schedule reference PDFs
  (`NDC-Cloud-Service-Fee-2023.pdf`, `NDC-Datacenter-Service-Prices-2023.pdf`)
  that the live pages also reference. `PricingTiersSeeder` now truncates
  before reseeding since the row shape changed completely.
- **Forms page was a hand-picked partial list** (10 of the original's ~35
  items across 11 category sections) — only the first "General & Frame
  Agreements" section had made it into the Blade conversion; VPS, Backup,
  Load Balancer, Managed DB, Email, Collocation, Private Access, App
  Hosting, and Web Hosting sections were dropped entirely. Rebuilt
  `forms.blade.php` with all 11 sections faithfully reproduced from the
  static prototype, plus the subnav it originally had. All file links
  verified resolving (14 real docs already existed in
  `storage/app/public/docs/`, they just weren't linked from most of the
  page).
- **Policies page was missing 6 of its 10 real sections** — only
  Privacy/Terms/RTI/Accessibility (the 4 with no associated document) had
  been ported; User Policy, Public Email Policy 2018, NDC Cloud Computing
  Policy 2023 (Draft), SOP, Data Center Guideline, and Public Email
  Guideline 2019 (the 6 "request the full document via Contact" governance
  docs — this is what the user meant by "policy and agreement files are
  missing") were dropped. Restored all 10 in `PagesSeeder`'s `policies`
  page content_blocks and rebuilt `policies.blade.php` to match, including
  the subnav and per-section "Request Full Document" CTA the original had.
  Switched `PagesSeeder` from `firstOrCreate` to `updateOrCreate` so
  re-running it actually refreshes content instead of being a no-op once
  a page row exists.
- Re-ran the full CSS class-existence audit (same method as the earlier
  design-fix pass) across every public and admin page after all of the
  above — zero new gaps introduced.

**Lesson for next time**: when told to "convert this page," always grep the
*entire* source file for repeating structural patterns (`pricing-card`,
`pricing-section`, list items in a subnav) and count them, rather than
transcribing by hand from the first screen of content — partial-but-plausible-looking
output (10 forms instead of 35, 4 policy sections instead of 10) is easy to
mistake for "done" without an explicit count check against the source.

## 2026-07-11 (later same day) — Design/CSS audit and fix
User reported "designs are broken" after the Laravel conversion. Root cause:
`resources/css/public.css` was built by extracting each page's own
page-specific `<style>` block from the static prototype (ABOUT.CSS,
CONTACT.CSS, SERVICES.CSS, etc.) — but **`index.html`'s own block
(HOME.CSS: hero, stat bar, ticker, quick links, services grid, news grid,
partner row) was never extracted**, so the entire homepage rendered with
core layout classes completely unstyled. Found by diffing every class used
in each rendered page against every class actually defined in the
stylesheet (not visual inspection — no screenshot tool available this
session, so this was a systematic class-existence audit + cross-check
against the original static HTML to rule out false positives like
JS-only hook classes that never had CSS to begin with).

Fixed:
- [x] Extracted `HOME.CSS` from `_static-prototype/public/index.html` into
      `public.css` (was missing entirely).
- [x] Fixed `home.blade.php`: `section-eyebrow` → `eyebrow` (wrong class
      name used, real class is `.eyebrow` from the shared base styles).
- [x] Fixed `home.blade.php`'s ticker markup: was using an invented
      `.ticker-wrap` structure with inline styles; rebuilt to match the
      real `.ticker-bar > .wrap > .ticker-inner > .ticker-label/.ticker-text`
      structure that the CSS actually targets.
- [x] Laravel's default pagination view outputs Tailwind utility classes,
      which our non-Tailwind public/admin pages don't load — pagination
      widgets rendered as raw unstyled buttons. Built a custom
      `resources/views/vendor/pagination/ndc.blade.php` using the site's
      real `.pagination`/`.pg-btn` classes (already defined for
      `ndc_notices.html`), added matching styles to `admin.css` too, and
      switched all 4 `->links()` calls
      (`public/notices`, `admin/notices`, `admin/users`, `admin/media`) to
      `->links('vendor.pagination.ndc')`.
- [x] `admin/notices/index.blade.php` used `notice-cat` (a public-page-only
      class); the real admin class is `.category-pill` + `.cat-*` — added
      those to `admin.css` (they only existed in `public.css` before) and
      fixed the Blade view.
- [x] Verified via a full class-existence sweep across all 10 public pages
      and 12 admin pages post-fix — zero real missing-class findings left
      (remaining flagged classes confirmed as pre-existing, legitimately
      unstyled JS-hook/wrapper classes in the original static design too).

**Lesson for next time**: when extracting per-page CSS from a multi-page
static prototype into a consolidated stylesheet, extract *every* page's
own block, including the "main" one that page-specific styles usually
piggyback off (`index.html`'s HOME.CSS block was easy to miss since the
shared `main.css` portion was already pulled from that same file).

## 2026-07-11
- [x] Set up `.claude/` folder for task tracking and context summaries.
- [x] Full-project anomaly audit (public/, admin/, assets/, plan doc).
- [x] Identified rename: `BACKEND-DESIGN-PLAN.md` → `LARAVEL-DYNAMIZATION-PLAN.md`.
      Fixed 16 stale inline comments across 12 HTML files + 1 stale
      `admin-notices.html` self-reference in `admin/notices.html`.
- [x] Updated `LARAVEL-DYNAMIZATION-PLAN.md`'s stale "Known gap" note —
      `admin/page-policies.html` and `admin/page-forms.html` already exist
      as dedicated editors, so the plan now reflects that instead of
      flagging it as missing.
- [x] **Advertising-only scope change** — removed all on-site
      ordering/account features, replaced with hand-offs to CMP/BCC CA:
      - Rewrote `ndc_services.html`'s Service Order Procedure (3 tabs) with
        real workflow content sourced from ndc.bcc.gov.bd, replacing the
        quick-order-forms with CMP/BCC-CA CTA buttons + a VPN form download.
      - Updated `ndc_pricing_cloud.html` / `ndc_pricing_request.html` CTAs
        to link straight to CMP.
      - Deleted `public/register.html`; redesigned `public/login.html` as
        an "Account Access" page (Admin Login / CMP / plain message form
        with a CAPTCHA-on-2nd-submission mock); removed the Register nav
        link across all public pages.
      - Added the same CAPTCHA-on-2nd-submission mock to `ndc_contact.html`.
      - Repurposed `admin/users.html` from "Portal Users" (customer
        approval workflow) to "Admin Users" (staff accounts, `is_admin`
        flag, invite-based creation); updated `admin/dashboard.html` and
        every admin sidebar's nav label to match.
      - Rewrote `LARAVEL-DYNAMIZATION-PLAN.md`: removed `ServiceOrder` and
        `Organization` models, use cases, services, and roadmap Phase 8;
        rewrote Part 6 (Auth Design) as admin-only with an invite flow and
        a new §6.7 public-form abuse-protection design; updated all
        summary counts.

- [x] **Laravel conversion, Phase 0 — environment + skeleton stood up
      in place** (see `.claude/CONTEXT.md` "Local dev environment" for
      command/credential details):
      - Moved the static prototype into `_static-prototype/` (nothing
        deleted) and scaffolded `laravel/laravel` directly into the project
        root.
      - Installed Composer (`~/.local/bin`) and Node/npm (via `nvm`) —
        neither is a system-wide install, both are user-local.
      - LAMPP's MariaDB needed a manual repair by the user (crashed
        `mysql.db`/`mysql.user` system tables from a prior bad shutdown);
        it's now running and auto-repaired itself on that restart.
      - Created MySQL DB `ndc_bcc` + user `ndc_app`.
      - Installed Jetstream (`livewire` stack, `--teams`), left Fortify's
        registration/2FA/password-reset features at their defaults **by
        explicit user choice** — see the CONTEXT.md note on why this
        looks like it contradicts the advertising-only/no-public-accounts
        design and isn't reconciled yet.
      - Ran migrations (users, teams, team_invitations, personal_access_tokens,
        two-factor columns, etc.) against `ndc_bcc` — all clean.
      - Verified boot: `/`, `/login`, `/register` all return HTTP 200 with
        Jetstream's Livewire/Alpine assets loading correctly.

- [x] **Laravel conversion, Phases 1–13 — all built and verified end-to-end**
      (not just "compiles" — every flow below was actually exercised via
      curl/tinker through real HTTP requests during this session):
      - **Phase 1**: all 10 Eloquent models + migrations (`User` augmented
        with `is_admin`/`role`/`status`/invite columns; `Page`, `Notice`,
        `Service`, `TeamMember`, `PricingTier`, `ContactInquiry`,
        `MediaAsset`, `Setting`, `AuditLog`).
      - **Phase 2**: admin auth (`AuthService`, `CheckAdminRole` middleware,
        `Admin\AuthController`) — verified login, logout, unauthorized
        redirect, invite → accept-invite → login as the new user.
      - **Phase 3**: `SettingService` + shared `<x-public-layout>` /
        `<x-admin-layout>` components (see CONTEXT.md for why they're in
        `components/` not `layouts/`).
      - **Phase 4**: Pricing — `PricingService`, public + admin controllers,
        24 real seeded tiers. Verified: public display, admin price edit,
        cache invalidation, audit log. Also where a real caching bug got
        found and fixed — see CONTEXT.md "Known gotchas."
      - **Phase 5**: Notices — public list+category-filter, admin CRUD +
        bulk publish/draft/delete. Verified end-to-end including the
        create-notice-appears-on-public-page round trip.
      - **Phase 6**: Page CMS — About/Contact/Policies/Forms public pages,
        generic JSON `content_blocks` admin editor (see CONTEXT.md known
        gap re: not a tailored per-field form). Verified a live content
        edit reflecting on the public page with cache invalidation.
      - **Phase 7**: Homepage (hero/stats/featured-services/notices, all
        DB-driven), Services catalog page, admin Team + Services screens,
        admin Homepage Builder (turned out to just be the Phase 6 generic
        page editor pointed at the `home` slug — confirmed working, no
        separate screen needed).
      - **Phase 8**: Service Order Procedure section (on `/services`) —
        real workflow content sourced from ndc.bcc.gov.bd, CMP/BCC-CA
        links driven by `Setting` rows.
      - **Phase 9**: Contact inquiries + abuse protection — verified the
        CAPTCHA gate is enforced *server-side* (2nd submission without the
        checkbox field is actually rejected), not just hidden client-side.
      - **Phase 10**: Media library — `MediaService` (magic-byte MIME
        validation, 20MB limit), upload/list/delete, reference-protection
        (won't delete a file still linked from a Notice/TeamMember).
        Verified full upload → serve → delete cycle.
      - **Phase 11**: Admin Users screen — invite/suspend/reactivate/delete,
        verified full cycle including audit logging.
      - **Phase 12**: Dashboard — real stat cards (page/notice/admin-user
        counts) and a real `AuditLog::recent()` activity feed, verified
        populated after triggering a real action.
      - **Phase 13**: Full route sweep (every public + admin page, HTTP 200,
        no exceptions in the log) — caught and fixed the pricing-cache bug
        during this pass.

## Backlog / follow-ups (not blocking — see CONTEXT.md "Known gaps" for detail)
- [ ] `sudo apt install php8.5-mbstring` still hasn't been confirmed done —
      re-check `php -m | grep mbstring` next session; if still missing,
      grep new Blade/PHP code for `Str::title|Str::upper|Str::lower|Str::limit|mb_`
      before shipping.
- [ ] Reconcile Jetstream's Teams/public-registration (left at defaults, by
      explicit user choice) with the plan doc's invite-only admin-account
      design — still unreconciled, ask before assuming either direction.
- [ ] `PricingTiersSeeder` has 24 of the original ~60+ pricing rows — a full
      audit against `_static-prototype/public/ndc_pricing_*.html` would be
      needed for pixel-exact parity.
- [ ] Wire at least one real `MediaAsset` reference (a team photo, a notice
      PDF, a logo setting) to exercise those FK columns end-to-end.
- [ ] Replace the generic JSON `content_blocks` admin editor (Phase 6) with
      tailored per-field forms if content editors find raw JSON awkward.
- [ ] Split remaining page-specific CSS more granularly if `public.css`/
      `admin.css` grow unwieldy (currently ~600-750 lines each, consolidated
      from the static prototype's 26 separate inline `<style>` blocks).
