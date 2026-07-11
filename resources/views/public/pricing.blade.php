<x-public-layout :title="$pageTitle . ' – National Data Center'">

<div class="pricing-hero">
  <div class="container">
    <div class="pricing-hero-inner">
      <div>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / <span>{{ $pageTitle }}</span></div>
        <h2>{{ $pageTitle }}</h2>
        <p>All prices exclude VAT unless stated otherwise. Actual ordering and provisioning happen on the NDC Cloud Management Portal.</p>
        <div class="pricing-hero-badges">
          <span class="phbadge">Government Rates</span>
          <span class="phbadge">Updated Pricing</span>
        </div>
      </div>
    </div>
  </div>
</div>

@php
  $serviceTypeLabels = [
    'cloud_ecs_general' => 'General Purpose ECS',
    'cloud_ecs_memory' => 'Memory Intensive ECS',
    'cloud_ecs_accelerated' => 'Accelerated ECS (GPU)',
    'cloud_storage' => 'Elastic Block Storage (EBS) & Object Storage',
    'cloud_autoscaling' => 'Auto Scaling (AS)',
    'cloud_network' => 'Elastic IP & Load Balancer',
    'cloud_backup' => 'ECS Snapshot Backup Service',
    'cloud_container' => 'Container Service (eGovCloud Kubernetes)',
    'cloud_paas_app' => 'Hardened Application Platform',
    'cloud_paas_db' => 'Database Platform',
    'cloud_paas_messaging' => 'Messaging Platform',
    'cloud_paas_monitoring' => 'Monitoring Platform',
    'cloud_openshift' => 'OpenShift on BCC eGovCloud Platform',
    'cloud_dr' => 'Disaster Recovery Service (CDRS)',
    'rbs_vps' => 'VPS (Virtual Private Server)',
    'rbs_email' => 'Email Service',
    'rbs_database_managed' => 'Managed Database Service',
    'rbs_database_mysql' => 'MySQL Database Service',
    'rbs_private_access' => 'NDC Private Access Service',
    'rbs_public_ip' => 'Public IP Address',
    'rbs_block_storage' => 'Block Storage',
    'rbs_waf' => 'Web Application Firewall (WAF)',
    'rbs_backup' => 'Backup Service',
    'rbs_cloud_drive' => 'Cloud Drive Service',
    'rbs_colocation_rack_unit' => 'Rack Unit Allocation (Colocation)',
    'rbs_colocation_rack_space' => 'Rack Space Allocation (Colocation)',
    'rbs_physical_server' => 'Physical Server Service',
    'rbs_dns' => 'DNS Service',
    'rbs_consultation' => 'Design & Deployment Consultation',
  ];
@endphp

@if ($tiersByType->isNotEmpty())
<div class="pricing-subnav">
  <div class="container">
    <div class="pricing-subnav-inner">
      <ul class="pricing-tabs">
        @foreach ($tiersByType as $serviceType => $tiers)
          <li><a href="#{{ Str::after($serviceType, '_') }}" class="{{ $loop->first ? 'active' : '' }}">{{ $serviceTypeLabels[$serviceType] ?? str_replace('_', ' ', $serviceType) }}</a></li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endif

<div class="wrap" style="padding:36px 24px;">
  @forelse ($tiersByType as $serviceType => $tiers)
    <div class="pricing-section" id="{{ Str::after($serviceType, '_') }}" data-pricing-section>
      <div class="pricing-section-header">
        <h2 class="pricing-section-title">{{ $serviceTypeLabels[$serviceType] ?? str_replace('_', ' ', $serviceType) }}</h2>
      </div>
      @php
        $specKeys = $tiers->flatMap(fn ($t) => collect($t->specs ?? [])->except(['tier', 'type'])->keys())->unique()->values();
      @endphp
      <div class="pricing-table-wrap">
        <table class="pricing-table">
          <thead>
            <tr>
              <th data-sort="text">Tier</th>
              @foreach ($specKeys as $key)
                <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
              @endforeach
              <th data-sort="number">Price</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tiers as $tier)
              @php
                $badgeText = $tier->specs['tier'] ?? $tier->specs['type'] ?? 'Standard';
                $badgeKey = strtolower($badgeText);
                $badgeClass = match (true) {
                    str_contains($badgeKey, 'premium') => 'premium',
                    str_contains($badgeKey, 'advance') => 'advance',
                    str_contains($badgeKey, 'x.large') || str_contains($badgeKey, '2x') || str_contains($badgeKey, '3x') || str_contains($badgeKey, '4x') || str_contains($badgeKey, '5x') => 'xlarge',
                    str_contains($badgeKey, 'standard') => 'standard',
                    default => 'basic',
                };
              @endphp
              <tr>
                <td>
                  <div class="pt-name-cell">
                    <span class="pc-tier {{ $badgeClass }}">{{ $badgeText }}</span>
                    <span class="pt-name">{{ $tier->name }}</span>
                  </div>
                </td>
                @foreach ($specKeys as $key)
                  <td>{{ $tier->specs[$key] ?? '—' }}</td>
                @endforeach
                <td data-value="{{ $tier->price_value ?? 9999999 }}">
                  <span class="pt-price">{{ $tier->price_display }}</span>
                  <span class="pt-price-period">excl. VAT &amp; TAX</span>
                </td>
                <td><a href="{{ $ctaUrl }}" target="_blank" rel="noopener" class="pt-order-btn">Order &#8594;</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @empty
    <p style="text-align:center;color:var(--gray-500);padding:40px 0;">No pricing tiers published yet.</p>
  @endforelse

  @if (!empty($referenceDoc))
    <p style="text-align:center;font-size:.82rem;color:var(--gray-500);margin:8px 0 28px;">
      &#128196; Reference Document: <a href="{{ asset('storage/docs/portfolio/' . $referenceDoc['file']) }}" download style="color:var(--green-dark);font-weight:600;">{{ $referenceDoc['label'] }}</a>
    </p>
  @endif

  <div class="pricing-cta-banner">
    <h3>Ready to Order?</h3>
    <p>Actual ordering and account management happen on the NDC Cloud Management Portal.</p>
    <div class="pricing-cta-btns">
      <a href="{{ $ctaUrl }}" target="_blank" rel="noopener" class="cta-btn-primary">{{ $ctaLabel }} &#8594;</a>
      <a href="{{ Route::has('contact') ? route('contact') : route('login') }}" class="cta-btn-outline">Contact NDC Team</a>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.pricing-table').forEach(function (table) {
  var tbody = table.querySelector('tbody');
  table.querySelectorAll('th[data-sort]').forEach(function (th) {
    th.addEventListener('click', function () {
      var idx = Array.prototype.indexOf.call(th.parentNode.children, th);
      var type = th.getAttribute('data-sort');
      var asc = th.getAttribute('data-dir') !== 'asc';
      table.querySelectorAll('th[data-sort]').forEach(function (h) {
        h.removeAttribute('data-dir'); h.classList.remove('sorted-asc', 'sorted-desc');
      });
      th.setAttribute('data-dir', asc ? 'asc' : 'desc');
      th.classList.add(asc ? 'sorted-asc' : 'sorted-desc');

      var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
      rows.sort(function (a, b) {
        var aCell = a.children[idx], bCell = b.children[idx];
        var av = aCell.getAttribute('data-value') || aCell.textContent.trim();
        var bv = bCell.getAttribute('data-value') || bCell.textContent.trim();
        if (type === 'number') {
          av = parseFloat(av) || 0; bv = parseFloat(bv) || 0;
          return asc ? av - bv : bv - av;
        }
        return asc ? av.localeCompare(bv) : bv.localeCompare(av);
      });
      rows.forEach(function (r) { tbody.appendChild(r); });
    });
  });
});
</script>

</x-public-layout>
