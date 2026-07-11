<x-public-layout :title="'About NDC – National Data Center'">

<div class="page-hero"><div class="wrap"><div class="page-hero-inner"><div class="page-hero-text"><div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / <span>About</span></div><h2>About National Data Center</h2></div></div></div></div>

<div class="about-subnav">
  <div class="wrap">
    <ul class="subnav-links">
      <li><a href="#history" class="active">Our History</a></li>
      <li><a href="#mission-vision">Mission &amp; Vision</a></li>
      <li><a href="#team">Our Team</a></li>
      <li><a href="#certifications">Certifications</a></li>
    </ul>
  </div>
</div>

<section class="section" id="history">
  <div class="wrap">
    <p class="eyebrow">Our Background</p>
    <h2 class="section-title">Our History</h2>
    <div class="divider"></div>
    <p class="section-lead">{{ $blocks['history_lead'] ?? '' }}</p>

    <div class="timeline">
      @foreach (($blocks['timeline'] ?? []) as $i => $item)
        <div class="tl-item">
          @if ($i % 2 === 0)
            <div class="tl-content"><div class="tl-content-box"><h3>{{ $item['title'] }}</h3><p>{{ $item['body'] }}</p></div></div>
            <div class="tl-dot"><div class="tl-dot-circle">{{ $item['year'] }}</div></div>
            <div class="tl-empty"></div>
          @else
            <div class="tl-empty"></div>
            <div class="tl-dot"><div class="tl-dot-circle">{{ $item['year'] }}</div></div>
            <div class="tl-content"><div class="tl-content-box"><h3>{{ $item['title'] }}</h3><p>{{ $item['body'] }}</p></div></div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section section-alt section-anchor" id="mission-vision">
  <div class="wrap">
    <p class="eyebrow">Purpose &amp; Direction</p>
    <h2 class="section-title">Mission &amp; Vision</h2>
    <div class="divider"></div>
    <div class="mv-grid">
      <div class="mv-card mission">
        <div class="mv-tag">&#9670; Mission Statement</div>
        <h3>Our Mission</h3>
        <p>{{ $blocks['mission'] ?? '' }}</p>
      </div>
      <div class="mv-card vision">
        <div class="mv-tag">&#9670; Vision Statement</div>
        <h3>Our Vision</h3>
        <p>{{ $blocks['vision'] ?? '' }}</p>
      </div>
    </div>

    <h3 style="font-family:var(--font-d);font-size:1.5rem;font-weight:700;color:var(--green-dark);margin:32px 0 20px;">Our Core Values</h3>
    <div class="values-grid">
      @foreach (($blocks['core_values'] ?? []) as $val)
        <div class="val-card">
          <div class="val-card-icon">{{ $val['icon'] }}</div>
          <h4>{{ $val['title'] }}</h4>
          <p>{{ $val['body'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section section-anchor" id="team">
  <div class="wrap">
    <p class="eyebrow">Our People</p>
    <h2 class="section-title">Our Team</h2>
    <div class="divider"></div>
    <div class="team-section-label">&#9670; Leadership &amp; Management</div>
    <div class="leadership-grid">
      @foreach ($leadership as $member)
        <div class="team-card">
          <div class="team-avatar">
            @if ($member->photo)
              <img src="{{ $member->photo->resolvedUrl() }}" alt="{{ $member->name }}"/>
            @else
              {{ collect(explode(' ', $member->name))->map(fn($p) => substr($p, 0, 1))->take(2)->implode('') }}
            @endif
          </div>
          <div class="team-info">
            <h4>{{ $member->name }}</h4>
            <div class="role">{{ $member->designation }}</div>
            <div class="org">Bangladesh Computer Council</div>
          </div>
        </div>
      @endforeach
    </div>
    @if ($technicalStaff->isNotEmpty())
      <div class="team-section-label" style="margin-top:24px;">&#9670; Technical Staff</div>
      <div class="leadership-grid">
        @foreach ($technicalStaff as $member)
          <div class="team-card">
            <div class="team-avatar">
            @if ($member->photo)
              <img src="{{ $member->photo->resolvedUrl() }}" alt="{{ $member->name }}"/>
            @else
              {{ collect(explode(' ', $member->name))->map(fn($p) => substr($p, 0, 1))->take(2)->implode('') }}
            @endif
          </div>
            <div class="team-info">
              <h4>{{ $member->name }}</h4>
              <div class="role">{{ $member->designation }}</div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

<section class="section section-alt section-anchor" id="certifications">
  <div class="wrap">
    <p class="eyebrow">Recognized Standards</p>
    <h2 class="section-title">Certifications &amp; Compliance</h2>
    <div class="divider"></div>
    <div class="values-grid">
      @foreach (($blocks['certifications'] ?? []) as $cert)
        <div class="val-card">
          <h4>{{ $cert['name'] }}</h4>
          <p>{{ $cert['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

</x-public-layout>
