@php
    $navItems = [
        ['route' => 'home', 'label' => 'Home'],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="{{ $metaDescription ?? setting('homepage_meta_description') }}"/>
  <title>{{ $title ?? setting('homepage_meta_title') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Barlow+Condensed:wght@500;600;700&display=swap" rel="stylesheet"/>
  @vite(['resources/css/public.css', 'resources/js/nav.js'])
  {{ $head ?? '' }}
</head>
<body>

<div class="top-strip">
  <div class="wrap">
    <span>Government of the People's Republic of Bangladesh &nbsp;|&nbsp; ICT Tower, Agargaon, Dhaka-1207</span>
    <div class="ts-right">
      <a href="{{ Route::has('policies') ? route('policies') . '#accessibility' : '#' }}">Accessibility</a>
      <a href="#site-footer">Sitemap</a>
      <a href="{{ Route::has('contact') ? route('contact') : '#' }}">Contact</a>
    </div>
  </div>
</div>
<header>
  <div class="container">
    <div class="header-inner">
      <div class="logo-group">
        <a href="{{ route('home') }}" class="logo-item">
          <img src="{{ asset('images/logo-a725453b2e.png') }}" alt="ICT Division"/>
        </a>
        <div class="logo-div"></div>
        <a href="{{ route('home') }}" class="logo-item">
          <img src="{{ asset('images/logo-0d424c2945.png') }}" alt="BCC"/>
          <span class="logo-label">Bangladesh<br>Computer Council</span>
        </a>
        <div class="logo-div"></div>
        <a href="{{ route('home') }}" class="logo-item">
          <img src="{{ asset('images/logo-4fba5160ce.png') }}" alt="National Data Center (NDC)"/>
        </a>
      </div>
      <div class="site-title">
        <h1>{{ setting('site_name', 'National Data Center') }}</h1>
        <p>{{ setting('site_tagline') }}</p>
      </div>
      <div class="hdr-contact">
        <strong>Help Desk</strong>
        {{ setting('helpdesk_phone') }}<br>
        {{ setting('helpdesk_email') }}<br>
        Service Hours: {{ setting('service_hours') }}
      </div>
    </div>
  </div>
</header>
<nav><div class="container"><div class="nav-inner">
  <ul class="nav-links">
    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
    <li class="has-drop {{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ Route::has('about') ? route('about') : '#' }}">About <span class="drop-arrow">&#9660;</span></a>
      <ul>
        <li><a href="{{ Route::has('about') ? route('about') : '#' }}#history">Our History</a></li>
        <li><a href="{{ Route::has('about') ? route('about') : '#' }}#mission-vision">Mission &amp; Vision</a></li>
        <li><a href="{{ Route::has('about') ? route('about') : '#' }}#team">Our Team</a></li>
        <li><a href="{{ Route::has('about') ? route('about') : '#' }}#certifications">Certifications</a></li>
      </ul>
    </li>
    <li class="has-drop {{ request()->routeIs('services') ? 'active' : '' }}"><a href="{{ Route::has('services') ? route('services') : '#' }}">Services <span class="drop-arrow">&#9660;</span></a>
      <ul>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#cloud">Cloud Services (IaaS/PaaS/SaaS)</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#hosting">Web &amp; Application Hosting</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#colocation">Colocation Services</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#managed">Managed Services</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#email">Email Service (Zimbra)</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#vps">VPS Service</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#backup">Backup &amp; Disaster Recovery</a></li>
      </ul>
    </li>
    <li class="has-drop"><a href="{{ Route::has('services') ? route('services') : '#' }}#procedure">Ordering <span class="drop-arrow">&#9660;</span></a>
      <ul>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#procedure-cloud">Cloud Service Order</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#procedure-request">Request Based Service Order</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#procedure-ssl">SSL Certificate Order for VPN</a></li>
      </ul>
    </li>
    <li class="has-drop {{ request()->routeIs('pricing.*') ? 'active' : '' }}"><a href="{{ Route::has('pricing.cloud') ? route('pricing.cloud') : '#' }}">Pricing <span class="drop-arrow">&#9660;</span></a>
      <ul>
        <li><a href="{{ Route::has('pricing.cloud') ? route('pricing.cloud') : '#' }}">Cloud Based Service</a></li>
        <li><a href="{{ Route::has('pricing.request') ? route('pricing.request') : '#' }}">Request Based Service</a></li>
      </ul>
    </li>
    <li class="{{ request()->routeIs('forms') ? 'active' : '' }}"><a href="{{ Route::has('forms') ? route('forms') : '#' }}">Forms</a></li>
    <li class="{{ request()->routeIs('policies') ? 'active' : '' }}"><a href="{{ Route::has('policies') ? route('policies') : '#' }}">Policies</a></li>
    <li class="{{ request()->routeIs('notices') ? 'active' : '' }}"><a href="{{ Route::has('notices') ? route('notices') : '#' }}">Notices</a></li>
    <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ Route::has('contact') ? route('contact') : '#' }}">Support &amp; Contact</a></li>
    <li class="nav-login-item"><a href="{{ route('login') }}">&#128274; Login</a></li>
  </ul>
  <button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>
</div></div></nav>

<div class="mobile-nav-overlay" id="mobileOverlay"></div>
<div class="mobile-nav" id="mobileNav" role="navigation">
  <div class="mobile-nav-header">
    <button class="mobile-nav-close" id="mobileClose" aria-label="Close menu">&#10005;</button>
  </div>
  <ul class="mobile-nav-links">
    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
    <li class="has-mobile-drop {{ request()->routeIs('about') ? 'active' : '' }}">
      <a href="{{ Route::has('about') ? route('about') : '#' }}">About NDC <span class="mobile-drop-toggle">&#9660;</span></a>
      <ul class="mobile-sub">
        <li><a href="{{ Route::has('about') ? route('about') : '#' }}#history">Our History</a></li>
        <li><a href="{{ Route::has('about') ? route('about') : '#' }}#mission-vision">Mission &amp; Vision</a></li>
        <li><a href="{{ Route::has('about') ? route('about') : '#' }}#team">Our Team</a></li>
        <li><a href="{{ Route::has('about') ? route('about') : '#' }}#certifications">Certifications</a></li>
      </ul>
    </li>
    <li class="has-mobile-drop {{ request()->routeIs('services') ? 'active' : '' }}">
      <a href="{{ Route::has('services') ? route('services') : '#' }}">NDC Services <span class="mobile-drop-toggle">&#9660;</span></a>
      <ul class="mobile-sub">
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#cloud">Cloud Services (IaaS/PaaS/SaaS)</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#hosting">Web &amp; Application Hosting</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#colocation">Colocation Services</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#managed">Managed Services</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#email">Email Service (Zimbra)</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#vps">VPS Service</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#backup">Backup &amp; Disaster Recovery</a></li>
      </ul>
    </li>
    <li class="has-mobile-drop">
      <a href="{{ Route::has('services') ? route('services') : '#' }}#procedure">Service Order Procedure <span class="mobile-drop-toggle">&#9660;</span></a>
      <ul class="mobile-sub">
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#procedure-cloud">Cloud Service Order</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#procedure-request">Request Based Service Order</a></li>
        <li><a href="{{ Route::has('services') ? route('services') : '#' }}#procedure-ssl">SSL Certificate Order for VPN</a></li>
      </ul>
    </li>
    <li class="has-mobile-drop {{ request()->routeIs('pricing.*') ? 'active' : '' }}">
      <a href="{{ Route::has('pricing.cloud') ? route('pricing.cloud') : '#' }}">Package &amp; Pricing <span class="mobile-drop-toggle">&#9660;</span></a>
      <ul class="mobile-sub">
        <li><a href="{{ Route::has('pricing.cloud') ? route('pricing.cloud') : '#' }}">Cloud Based Service</a></li>
        <li><a href="{{ Route::has('pricing.request') ? route('pricing.request') : '#' }}">Request Based Service</a></li>
      </ul>
    </li>
    <li class="{{ request()->routeIs('forms') ? 'active' : '' }}"><a href="{{ Route::has('forms') ? route('forms') : '#' }}">Forms &amp; Agreements</a></li>
    <li class="{{ request()->routeIs('policies') ? 'active' : '' }}"><a href="{{ Route::has('policies') ? route('policies') : '#' }}">Policies &amp; Guidelines</a></li>
    <li class="{{ request()->routeIs('notices') ? 'active' : '' }}"><a href="{{ Route::has('notices') ? route('notices') : '#' }}">Notices</a></li>
    <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ Route::has('contact') ? route('contact') : '#' }}">Support &amp; Contact</a></li>
    <li style="border-top:2px solid var(--green-mid);margin-top:8px;padding-top:4px;">
      <a href="{{ route('login') }}" style="display:flex;align-items:center;gap:10px;padding:13px 20px;color:#fff;font-size:.88rem;font-weight:700;background:var(--green-accent);">
        <span>&#128274;</span> Login / Portal Access
      </a>
    </li>
  </ul>
  <div class="mobile-nav-search"><input type="text" placeholder="Search NDC..." aria-label="Search"/></div>
</div>

{{ $slot }}

<footer id="site-footer">
  <div class="footer-top">
    <div class="wrap">
      <div class="footer-grid">
        <div class="footer-brand">
          <h3>National Data Center</h3>
          <p>NDC is operated by Bangladesh Computer Council (BCC), the apex statutory body under the ICT Division of the Government of Bangladesh — delivering Tier-III certified, 24×7 IT infrastructure for public and private organizations.</p>
          <div class="footer-logos">
            <img src="{{ asset('images/logo-4fba5160ce.png') }}" alt="NDC"/>
            <img src="{{ asset('images/logo-0d424c2945.png') }}" alt="BCC"/>
            <img src="{{ asset('images/logo-a725453b2e.png') }}" alt="ICT Division"/>
          </div>
        </div>
        <div class="footer-col">
          <h4>About NDC</h4>
          <ul>
            <li><a href="{{ Route::has('about') ? route('about') : '#' }}#history">Our History</a></li>
            <li><a href="{{ Route::has('about') ? route('about') : '#' }}#mission-vision">Mission &amp; Vision</a></li>
            <li><a href="{{ Route::has('about') ? route('about') : '#' }}#team">Our Team</a></li>
            <li><a href="{{ Route::has('about') ? route('about') : '#' }}#certifications">Certifications</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Services</h4>
          <ul>
            <li><a href="{{ Route::has('services') ? route('services') : '#' }}#cloud">Cloud (IaaS/PaaS/SaaS)</a></li>
            <li><a href="{{ Route::has('services') ? route('services') : '#' }}#hosting">Web &amp; App Hosting</a></li>
            <li><a href="{{ Route::has('services') ? route('services') : '#' }}#colocation">Colocation</a></li>
            <li><a href="{{ Route::has('services') ? route('services') : '#' }}#email">Zimbra Email</a></li>
            <li><a href="{{ Route::has('services') ? route('services') : '#' }}#managed">Managed Services</a></li>
            <li><a href="{{ Route::has('services') ? route('services') : '#' }}#vps">VPS &amp; Load Balancer</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Contact Us</h4>
          <div class="fci"><span class="fci-icon">&#128205;</span><span>{{ setting('office_address') }}</span></div>
          <div class="fci"><span class="fci-icon">&#128222;</span><span>{{ setting('helpdesk_phone') }}</span></div>
          <div class="fci"><span class="fci-icon">&#9993;</span><span>{{ setting('helpdesk_email') }}</span></div>
          <div class="fci"><span class="fci-icon">&#128339;</span><span>{{ setting('service_hours') }}</span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="wrap">
      <div class="footer-bottom-inner">
        <span>{{ setting('copyright_text') }}</span>
        <div class="fbl"><a href="{{ Route::has('policies') ? route('policies') : '#' }}#privacy">Privacy Policy</a><a href="{{ Route::has('policies') ? route('policies') : '#' }}#terms">Terms of Use</a><a href="{{ Route::has('policies') ? route('policies') : '#' }}#rti">RTI</a></div>
        <span>{{ setting('planning_implementation_credit') }}</span>
      </div>
    </div>
  </div>
</footer>

{{ $scripts ?? '' }}

<script>
// Shared sub-nav scroll-spy: highlights the sub-nav item matching whichever
// section is currently in view, for any page that has a .pricing-tabs or
// .subnav-links bar (Pricing/Policies/Forms/About/Services). Anchor clicks
// already jump to the right section (native browser behavior); this just
// keeps the nav's "active" state in sync as the user scrolls, since jumping
// there is not the same as marking where you are once you're there.
(function () {
  var navLinks = document.querySelectorAll('.pricing-tabs a[href^="#"], .subnav-links a[href^="#"]');
  if (!navLinks.length) return;

  var targets = [];
  navLinks.forEach(function (link) {
    var el = document.getElementById(link.getAttribute('href').slice(1));
    if (el) targets.push({ el: el, link: link });
  });
  if (!targets.length) return;

  function setActive(link) {
    navLinks.forEach(function (l) { l.classList.remove('active'); });
    link.classList.add('active');
  }

  targets.forEach(function (t) {
    new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) setActive(t.link);
      });
    }, { rootMargin: '-25% 0px -65% 0px' }).observe(t.el);
  });
})();
</script>

</body>
</html>
