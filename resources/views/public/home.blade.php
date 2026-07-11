<x-public-layout :title="setting('homepage_meta_title')" :meta-description="setting('homepage_meta_description')">

@if (setting('feature_ticker_enabled') === '1')
<div class="ticker-bar">
  <div class="wrap">
    <div class="ticker-inner">
      <span class="ticker-label">&#128276; Notice</span>
      <span class="ticker-text">{{ setting('ticker_message') }}</span>
    </div>
  </div>
</div>
@endif

<!-- HERO BANNER -->
<section class="hero" style="padding:70px 0 60px;">
  <div class="container">
    <div class="hero-grid">
      <div class="hero-text">
        <p class="hero-eyebrow">{{ $blocks['hero_eyebrow'] ?? '' }}</p>
        <h2>{{ $blocks['hero_title_main'] ?? '' }} <span>{{ $blocks['hero_title_accent'] ?? '' }}</span><br>{{ $blocks['hero_title_end'] ?? '' }}</h2>
        <p>{{ $blocks['hero_description'] ?? '' }}</p>
        <div class="hero-badges">
          @foreach (($blocks['hero_badges'] ?? []) as $i => $badge)
            <span class="badge {{ $i < 3 ? 'gold' : '' }}">{{ $badge }}</span>
          @endforeach
        </div>
        <div class="hero-btns">
          <a href="{{ Route::has('services') ? route('services') : '#' }}" class="btn btn-primary">Explore Services</a>
          <a href="{{ Route::has('services') ? route('services') . '#cloud-order' : '#' }}" class="btn btn-outline">Service Order</a>
        </div>
      </div>
      <div class="hero-visual">
        <svg viewBox="0 0 440 320" xmlns="http://www.w3.org/2000/svg">
          <rect x="0" y="290" width="440" height="30" fill="#0a1f12"/>
          <rect x="10" y="278" width="420" height="16" rx="2" fill="#0f2a18" stroke="#1a4028" stroke-width="0.5"/>
          <rect x="30" y="50" width="80" height="228" rx="3" fill="#0d2218" stroke="#2a5c3a" stroke-width="1.5"/>
          <rect x="130" y="50" width="80" height="228" rx="3" fill="#0d2218" stroke="#2a5c3a" stroke-width="1.5"/>
          <rect x="230" y="50" width="80" height="228" rx="3" fill="#0d2218" stroke="#2a5c3a" stroke-width="1.5"/>
          <rect x="330" y="80" width="80" height="198" rx="3" fill="#0d2218" stroke="#2a5c3a" stroke-width="1.5" opacity="0.6"/>
          <rect x="24" y="40" width="392" height="12" rx="2" fill="#1a3a26" stroke="#2a5c3a" stroke-width="1"/>
          <rect x="24" y="10" width="120" height="22" rx="3" fill="#0f2518" stroke="#2a5c3a" stroke-width="0.8"/>
          <circle cx="36" cy="21" r="4" fill="#4cbb6e" opacity="0.9"/>
          <text x="44" y="25" font-family="monospace" font-size="8" fill="rgba(76,187,110,0.9)">SYSTEM ONLINE</text>
          <rect x="296" y="10" width="120" height="22" rx="3" fill="#0f2518" stroke="#2a5c3a" stroke-width="0.8"/>
          <circle cx="308" cy="21" r="4" fill="#d4a017" opacity="0.9"/>
          <text x="316" y="25" font-family="monospace" font-size="8" fill="rgba(212,160,23,0.9)">UPTIME: 99.9%</text>
        </svg>
      </div>
    </div>
  </div>
</section>

<!-- STAT BAR -->
<div class="stat-bar">
  <div class="container">
    <div class="stat-grid">
      @foreach (($blocks['stat_bar'] ?? []) as $stat)
        <div class="stat-item">
          <div class="stat-num">{{ $stat['num'] }}</div>
          <div class="stat-label">{{ $stat['label'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<!-- QUICK LINKS -->
<section style="padding:36px 0; background:var(--off-white);">
  <div class="container">
    <div class="quick-links-grid">
      <a href="{{ Route::has('services') ? route('services') . '#cloud-order' : '#' }}" class="quick-link-card">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-9 14H7v-2h3v2zm0-4H7v-2h3v2zm0-4H7V7h3v2zm7 8h-5v-2h5v2zm0-4h-5v-2h5v2zm0-4h-5V7h5v2z"/></svg>
        <span>Service Order</span>
      </a>
      <a href="{{ Route::has('services') ? route('services') . '#colocation' : '#' }}" class="quick-link-card">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-5 14H4v-4h11v4zm0-5H4V9h11v4zm5 5h-4V9h4v9z"/></svg>
        <span>Colocation</span>
      </a>
      <a href="{{ Route::has('services') ? route('services') . '#cloud' : '#' }}" class="quick-link-card">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"/></svg>
        <span>Cloud Services</span>
      </a>
      <a href="{{ Route::has('services') ? route('services') . '#email' : '#' }}" class="quick-link-card">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z"/></svg>
        <span>Email (Zimbra)</span>
      </a>
      <a href="{{ Route::has('notices') ? route('notices') : '#' }}" class="quick-link-card">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l6 2.67V11c0 3.7-2.56 7.16-6 8.34-3.44-1.18-6-4.64-6-8.34V7.67L12 5z"/></svg>
        <span>Security Info</span>
      </a>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section>
  <div class="container">
    <div class="section-header">
      <p class="eyebrow">What We Offer</p>
      <h2>NDC Services</h2>
      <p>Comprehensive IT infrastructure and cloud services designed for government and enterprise organizations across Bangladesh.</p>
    </div>
    <div class="services-grid">
      @foreach ($featuredServices as $service)
        <div class="service-card">
          <div class="service-icon">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.15"/><path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" fill="none"/></svg>
          </div>
          <h3>{{ $service->name }}</h3>
          <p>{{ $service->description }}</p>
          <a href="{{ Route::has('services') ? route('services') . '#' . $service->slug : '#' }}" class="service-link">Learn more &#8594;</a>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ABOUT NDC -->
<section class="section-alt">
  <div class="container">
    <div class="about-grid">
      <div class="about-text">
        <p class="eyebrow">About NDC</p>
        <h2>{{ $blocks['about_preview_title'] ?? '' }}</h2>
        @foreach (($blocks['about_preview_paragraphs'] ?? []) as $paragraph)
          <p>{{ $paragraph }}</p>
        @endforeach
        <div class="about-certs">
          <span class="cert-badge">ISO 27001</span>
          <span class="cert-badge">ISO 20000</span>
          <span class="cert-badge">ITILv2</span>
          <span class="cert-badge">Tier-III</span>
        </div>
        <div class="about-btns">
          <a href="{{ Route::has('about') ? route('about') : '#' }}" class="btn-green">Learn More About NDC</a>
          <a href="{{ Route::has('about') ? route('about') . '#certifications' : '#' }}" class="btn-green-outline">Our Certifications</a>
        </div>
      </div>
      <div class="about-image">
        <img src="{{ asset('images/about-datacenter-servers.jpg') }}" alt="Server racks in a Tier-III data center" loading="lazy"/>
        <div class="tier-badge-overlay">
          <span class="big">TIER III</span>
          <small>Certified</small>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NEWS & NOTICES -->
<section>
  <div class="container">
    <div class="section-header">
      <p class="eyebrow">Updates</p>
      <h2>News &amp; Notices</h2>
    </div>
    <div class="news-grid">
      <div class="notice-panel">
        <div class="panel-header">
          Latest News
          <a href="{{ Route::has('notices') ? route('notices') : '#' }}">View All &#8594;</a>
        </div>
        <div style="padding:16px 18px;">
          @foreach ($latestNotices as $notice)
            <div class="news-card">
              <div class="news-thumb">
                <svg viewBox="0 0 68 52" xmlns="http://www.w3.org/2000/svg"><rect width="68" height="52" fill="#1a3a26"/><circle cx="34" cy="26" r="10" fill="#2a5c3a"/></svg>
              </div>
              <div>
                <div class="news-meta">{{ $notice->published_at->format('F j, Y') }} &nbsp;·&nbsp; {{ ucfirst($notice->category) }}</div>
                <div class="news-title"><a href="{{ Route::has('notices') ? route('notices') : '#' }}">{{ $notice->title }}</a></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="notice-panel">
        <div class="panel-header">
          Official Notices
          <a href="{{ Route::has('notices') ? route('notices') : '#' }}">View All &#8594;</a>
        </div>
        <ul class="notice-list">
          @foreach ($officialNotices as $notice)
            <li>
              <span class="notice-date">{{ $notice->published_at->format('M') }}<br>{{ $notice->published_at->format('d') }}</span>
              <a href="{{ Route::has('notices') ? route('notices') : '#' }}">{{ $notice->title }}</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- CERTIFICATIONS / PARTNERS -->
<section class="section-alt" style="padding:40px 0;">
  <div class="container">
    <div class="section-header" style="margin-bottom:24px;">
      <p class="eyebrow">Recognized By</p>
      <h2>Certifications &amp; Standards</h2>
    </div>
    <div class="partner-row">
      @foreach (['ISO 27001', 'ISO 20000', 'ITILv2', 'Tier-III', 'BCC', 'ICT Division'] as $cert)
        <a href="{{ Route::has('about') ? route('about') . '#certifications' : '#' }}" class="partner-logo">
          <svg viewBox="0 0 100 60" xmlns="http://www.w3.org/2000/svg">
            <rect width="100" height="60" rx="4" fill="#fff" stroke="#c4cfc7" stroke-width="1"/>
            <text x="50" y="34" font-family="Arial" font-size="11" font-weight="bold" fill="#1a5c2e" text-anchor="middle">{{ $cert }}</text>
          </svg>
          <span>{{ $cert }}</span>
        </a>
      @endforeach
    </div>
  </div>
</section>

</x-public-layout>
