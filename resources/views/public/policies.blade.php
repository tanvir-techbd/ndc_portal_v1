<x-public-layout :title="'Policies & Guidelines – National Data Center'">

<div class="pricing-hero">
  <div class="wrap">
    <div class="pricing-hero-inner">
      <div>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / <span>Policies &amp; Guidelines</span></div>
        <h2>Policies &amp; <span style="color:var(--green-accent);">Guidelines</span></h2>
        <p>{{ $blocks['hero_lead'] ?? '' }}</p>
      </div>
    </div>
  </div>
</div>

@if (!empty($blocks['sections']))
<div class="pricing-subnav">
  <div class="container">
    <div class="pricing-subnav-inner">
      <ul class="pricing-tabs">
        @foreach ($blocks['sections'] as $section)
          <li><a href="#{{ $section['id'] }}" class="{{ $loop->first ? 'active' : '' }}">{{ $section['tab'] ?? $section['title'] }}</a></li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endif

<div class="wrap" style="padding:0 24px;">
  @foreach (($blocks['sections'] ?? []) as $section)
    <div class="pricing-section" id="{{ $section['id'] }}" data-pricing-section>
      <div class="pricing-section-header">
        <p class="pricing-section-eyebrow">{{ $section['eyebrow'] ?? '' }}</p>
        <h2 class="pricing-section-title">{{ $section['title'] }}</h2>
      </div>
      @foreach (($section['body'] ?? []) as $paragraph)
        <p class="pricing-section-desc" style="max-width:820px;margin-bottom:12px;">{{ $paragraph }}</p>
      @endforeach
      @if (!empty($section['requestable']))
        <div style="margin-top:16px;"><a href="{{ route('contact') }}" class="pc-order-btn" style="display:inline-block;padding:10px 22px;">Request Full Document &#8594;</a></div>
      @endif
    </div>
  @endforeach
</div>

</x-public-layout>
