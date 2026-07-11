/* ════════════════════════════════════════════════════════════
   NAV.JS — Shared mobile navigation behavior (all public pages)
   Handles: hamburger toggle, drawer open/close, overlay click,
   Escape key, accordion sub-menus, auto-close on desktop resize
════════════════════════════════════════════════════════════ */
(function () {
  const hamburger = document.getElementById('hamburger');
  const mobileNav = document.getElementById('mobileNav');
  const mobileClose = document.getElementById('mobileClose');
  const overlay = document.getElementById('mobileOverlay');

  if (!hamburger || !mobileNav) return;

  function openNav() {
    mobileNav.classList.add('open');
    overlay.classList.add('open');
    hamburger.classList.add('open');
    hamburger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeNav() {
    mobileNav.classList.remove('open');
    overlay.classList.remove('open');
    hamburger.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  hamburger.addEventListener('click', function () {
    mobileNav.classList.contains('open') ? closeNav() : openNav();
  });
  mobileClose?.addEventListener('click', closeNav);
  overlay?.addEventListener('click', closeNav);
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeNav(); });

  document.querySelectorAll('.has-mobile-drop > a').forEach(function (link) {
    link.addEventListener('click', function (e) {
      const parent = this.parentElement;
      const sub = parent.querySelector('.mobile-sub');
      if (sub) {
        e.preventDefault();
        parent.classList.toggle('open');
      }
    });
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth > 900) closeNav();
  });
})();

/* ----------------------------------------------------------------
   CAPTCHA-on-repeat-submission (static demo → real backend enforces
   it too, see Phase 9). Applies to any form with [data-captcha-form].
------------------------------------------------------------------- */
document.querySelectorAll('[data-captcha-form]').forEach(function (form) {
  const key = 'ndc_submitted_' + form.id;
  const box = form.querySelector('[data-captcha-placeholder]');
  const checkbox = box ? box.querySelector('input[type="checkbox"]') : null;
  if (localStorage.getItem(key) && box) {
    box.hidden = false;
    if (checkbox) checkbox.required = true;
  }
  form.addEventListener('submit', function () {
    localStorage.setItem(key, '1');
  });
});
