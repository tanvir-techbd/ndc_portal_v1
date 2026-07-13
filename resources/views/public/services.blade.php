<x-public-layout :title="'Services – National Data Center'">

<div class="page-hero"><div class="wrap"><div class="page-hero-inner"><div class="page-hero-text"><div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / <span>Services</span></div><h2>NDC Services</h2><p>Comprehensive IT infrastructure and cloud services for government and enterprise organizations.</p></div></div></div></div>

<div class="subnav"><div class="wrap"><ul class="subnav-links">
  <li><a href="#cloud" class="active">Cloud (IaaS/PaaS/SaaS)</a></li>
  <li><a href="#hosting">Web &amp; App Hosting</a></li>
  <li><a href="#colocation">Colocation</a></li>
  <li><a href="#managed">Managed Services</a></li>
  <li><a href="#email">Email (Zimbra)</a></li>
  <li><a href="#vps">VPS &amp; Load Balancer</a></li>
  <li><a href="#backup">Backup &amp; DR</a></li>
  <li><a href="#procedure">Service Order Procedure</a></li>
  <li><a href="#cloud-order">Service Order</a></li>
</ul></div></div>

<section class="section">
  <div class="wrap">
    <p class="eyebrow">At a Glance</p>
    <h2 class="section-title">NDC Service Overview</h2>
    <div class="divider"></div>
    <p class="section-lead">NDC provides numerous services segregated into three main categories: Infrastructure as a Service (IaaS), Platform as a Service (PaaS), and Software as a Service (SaaS) — all delivered from a Tier-III certified government data center.</p>
    <div class="service-hero-grid">
      <div class="s-stat-card"><div class="s-stat-num">Tier-III</div><div class="s-stat-lbl">Certified Data Center</div></div>
      <div class="s-stat-card"><div class="s-stat-num">99.982%</div><div class="s-stat-lbl">Uptime SLA Standard</div></div>
      <div class="s-stat-card"><div class="s-stat-num">24×7</div><div class="s-stat-lbl">Operations &amp; Support</div></div>
    </div>

    @foreach ($groups as $group)
      <div id="{{ $group->slug }}" class="svc-anchor" style="padding-top:{{ $loop->first ? '24px' : '48px' }};">
        <p class="eyebrow">{{ $group->tag ?? $group->name }}</p>
        <h3 style="font-family:var(--font-d);font-size:1.6rem;font-weight:700;color:var(--green-dark);margin-bottom:20px;">{{ $group->name }}</h3>
        @forelse (($detailsByGroup[$group->slug] ?? []) as $detail)
          <div class="service-card">
            <div class="service-card-header">
              <div class="svc-icon">@include('public.partials.service-icon', ['icon' => $detail->icon])</div>
              <div><h3>{{ $detail->name }}</h3><div class="svc-tag">{{ $detail->tag }}</div></div>
            </div>
            <p>{{ $detail->description }}</p>
            @if (!empty($detail->tiers))
              <div class="svc-tiers">
                @foreach ($detail->tiers as $tier)
                  <span class="tier-pill {{ str_ends_with($tier, '*') ? 'gold' : '' }}">{{ rtrim($tier, '*') }}</span>
                @endforeach
              </div>
            @endif
            @if (!empty($detail->features))
              <div class="svc-features">
                @foreach ($detail->features as $feature)
                  <div class="svc-feature">{{ $feature }}</div>
                @endforeach
              </div>
            @endif
          </div>
        @empty
          <p style="color:var(--gray-500);font-size:.85rem;">No catalog details published for this service yet.</p>
        @endforelse
      </div>

    @endforeach
    <!-- ── SERVICE ORDER PROCEDURE ── -->
    <div id="procedure" class="svc-anchor" style="padding-top:48px;">
      <p class="eyebrow">Step-by-Step Guide</p>
      <h3 style="font-family:var(--font-d);font-size:1.6rem;font-weight:700;color:var(--green-dark);margin-bottom:12px;">Service Order Procedure</h3>
      <p class="section-lead" style="margin-bottom:24px;">Follow the appropriate procedure below depending on the type of service you need from NDC. Actual ordering, account access, and provisioning now happen on the NDC Cloud Management Portal (CMP) — this page explains the process and hands you off at the right step.</p>

      <div class="procedure-tabs" role="tablist" aria-label="Service order procedure type">
        <button type="button" class="procedure-tab-btn active" data-target="procedure-cloud" role="tab" aria-selected="true">&#9729; Cloud Service Order</button>
        <button type="button" class="procedure-tab-btn" data-target="procedure-request" role="tab" aria-selected="false">&#128203; Request Based Service Order</button>
        <button type="button" class="procedure-tab-btn" data-target="procedure-ssl" role="tab" aria-selected="false">&#128274; SSL Certificate Order for VPN</button>
      </div>

      <!-- Sub-item 1: Cloud Service Order -->
      <div id="procedure-cloud" class="procedure-block active" role="tabpanel">
        <div class="procedure-grid">
          <ol class="stepper">
            <li>
              <h4>Browse the Cloud Service Catalog</h4>
              <p>Review Elastic Cloud Server (ECS), Storage, PaaS, and SaaS offerings on this site to identify the service and tier that fits your workload.</p>
            </li>
            <li>
              <h4>Download &amp; Complete the Service Form</h4>
              <p>Download the relevant Cloud Service order form (see the <a href="{{ route('forms') }}">Forms &amp; Agreements</a> page), fill it in, and either sign it digitally and deliver it by email or eNothi, or print, sign, and physically deliver it to BCC with a forwarding letter.</p>
            </li>
            <li>
              <h4>Contract Signing &amp; Resource Preparation</h4>
              <p>BCC signs the service contract and prepares the resource quota and account for your organization on the Cloud Management Platform.</p>
            </li>
            <li>
              <h4>Portal Access Delivered by Email</h4>
              <p>NDC emails your Cloud Portal access and support information to the address provided on your form.</p>
              <span class="step-meta">Billing Starts Once You Order</span>
            </li>
            <li>
              <h4>Order &amp; Provision It Yourself on CMP</h4>
              <p>Log in to the NDC Cloud Management Portal (CMP) with the credentials you received, then select, configure, and provision your Cloud service directly.</p>
            </li>
          </ol>

          <div class="quick-order-form">
            <h4>Order via CMP</h4>
            <p class="qof-sub">Once your account is provisioned, place and manage your Cloud order directly on the NDC Cloud Management Portal.</p>
            <a href="{{ setting('cmp_portal_url', 'https://cmp.bcc.gov.bd') }}" target="_blank" rel="noopener" class="qof-cta-btn">Open CMP Portal &nbsp;&#8594;</a>
            <div class="qof-note">&#8505; CMP is currently in testing. For help getting started, see <a href="{{ route('contact') }}">Contact NDC</a>.</div>
          </div>
        </div>
      </div>

      <!-- Sub-item 2: Request Based Service Order -->
      <div id="procedure-request" class="procedure-block" role="tabpanel">
        <div class="procedure-grid">
          <ol class="stepper">
            <li>
              <h4>Browse the Service Catalog</h4>
              <p>Review the Request Based Service packages (VPS, Backup, Load Balancer, Database, Email, Colocation, Hosting) to identify the package you need.</p>
            </li>
            <li>
              <h4>Download &amp; Complete the Service Form</h4>
              <p>Download the order form for your required package from the <a href="{{ route('forms') }}">Forms &amp; Agreements</a> page, fill it in, and either sign it digitally and deliver it by email or eNothi, or print, sign, and physically deliver it to BCC with a forwarding letter.</p>
            </li>
            <li>
              <h4>Contract Signing &amp; Hosting Confirmation</h4>
              <p>BCC signs the service contract and confirms hosting/provisioning arrangements for the requested package.</p>
            </li>
            <li>
              <h4>Delivery &amp; Support Information</h4>
              <p>NDC emails delivery/confirmation details and support information to your registered contact once the service is provisioned.</p>
              <span class="step-meta">Billing Starts on Delivery</span>
            </li>
          </ol>

          <div class="quick-order-form">
            <h4>Start on CMP</h4>
            <p class="qof-sub">Start your Request-Based service request on the NDC Cloud Management Portal — since fulfillment for these packages is handled directly by NDC, expect a follow-up rather than instant provisioning.</p>
            <a href="{{ setting('cmp_portal_url', 'https://cmp.bcc.gov.bd') }}" target="_blank" rel="noopener" class="qof-cta-btn">Open CMP Portal &nbsp;&#8594;</a>
            <div class="qof-note">&#8505; CMP is currently in testing. Prefer to talk first? <a href="{{ route('contact') }}">Contact NDC</a>.</div>
          </div>
        </div>
      </div>

      <!-- Sub-item 3: SSL Certificate Order for VPN -->
      <div id="procedure-ssl" class="procedure-block" role="tabpanel">
        <div class="procedure-grid">
          <ol class="stepper">
            <li>
              <h4>Register &amp; Enroll at BCC CA</h4>
              <p>Visit the BCC Certifying Authority site, complete registration, log in, and enroll for an SSL Certificate Class-II.</p>
            </li>
            <li>
              <h4>Install BCC CA Client &amp; Generate Key</h4>
              <p>Download and install the BCC CA client on your working terminal, then generate your private key (PK) from the BCC CA site.</p>
            </li>
            <li>
              <h4>Pay the Certificate Fee</h4>
              <p>Complete payment by cash/cheque at bank, mobile banking (bKash/Nagad), or online via SSLCommerz — see fees below.</p>
            </li>
            <li>
              <h4>Approval &amp; Download</h4>
              <p>Email the scanned payment receipt to the BCC CA team, wait for your certificate to be approved by the BCC CA Admin, then download and install it on your working terminal.</p>
              <span class="step-meta">Step 1 of 2 — SSL Certificate Subscription</span>
            </li>
            <li>
              <h4>Install VPN Client &amp; Configure</h4>
              <p>Install the NDC VPN client (SecoClient) on your working terminal and configure the connection with NDC's VPN gateway information.</p>
            </li>
            <li>
              <h4>Submit the VPN Access Form</h4>
              <p>Fill out the NDC VPN Access Form with your certificate name and required details, then email the scanned copy to <a href="mailto:datacenter@bcc.gov.bd">datacenter@bcc.gov.bd</a>. NDC's VPN team will then assign and authenticate your VPN user to the Data Center network.</p>
              <span class="step-meta">Step 2 of 2 — VPN Client &amp; Access</span>
            </li>
          </ol>

          <div class="quick-order-form">
            <h4>SSL Certificate &amp; VPN Access</h4>
            <p class="qof-sub">This flow runs through BCC's Certifying Authority, not CMP.</p>
            <table class="fee-table">
              <thead><tr><th>SSL Certificate (Class-II)</th><th>Unit Price</th><th>VAT (15%)</th><th>Total / Year</th></tr></thead>
              <tbody>
                <tr><td>Government Employee</td><td>৳500.00</td><td>৳75.00</td><td>৳575.00</td></tr>
                <tr><td>Private Organization</td><td>৳3,000.00</td><td>৳450.00</td><td>৳3,450.00</td></tr>
              </tbody>
            </table>
            <a href="{{ setting('bcc_ca_portal_url', 'https://www.bcc-ca.gov.bd') }}/" target="_blank" rel="noopener" class="qof-cta-btn">Subscribe on BCC CA Portal &nbsp;&#8594;</a>
            <a href="{{ asset('storage/docs/forms/NDC-VPN-Access-Form.pdf') }}" download class="qof-cta-btn outline">Download NDC VPN Access Form</a>
            <div class="qof-note">&#8505; Certificate fees and more detail: <a href="{{ setting('bcc_ca_portal_url', 'https://www.bcc-ca.gov.bd') }}/service-fees" target="_blank" rel="noopener">bcc-ca.gov.bd/service-fees</a>. Need the vendor authorization letter, VPN client guide, or other VPN documents? See all <a href="{{ route('forms') }}#vpn">NDC VPN Service Forms &#8594;</a></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── SERVICE ORDER ── -->
    <div id="cloud-order" class="svc-anchor" style="padding-top:48px;">
      <div class="order-cta">
        <h3>Ready to Order NDC Services?</h3>
        <p>This site explains NDC's services and ordering procedures. Actual ordering and account access happen on the NDC Cloud Management Portal (CMP) — currently in testing.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
          <a href="{{ setting('cmp_portal_url', 'https://cmp.bcc.gov.bd') }}" target="_blank" rel="noopener" class="btn btn-primary">Open CMP Portal</a>
          <a href="#procedure-request" class="btn btn-outline-white">Request-Based Order</a>
        </div>
        <p style="font-size:.78rem;color:rgba(255,255,255,.6);margin-top:16px;">For assistance: datacenter@bcc.gov.bd &nbsp;|&nbsp; +88-02-55006840 &nbsp;|&nbsp; 24×7</p>
      </div>
    </div>
  </div>
</section>


<script>
(function () {
  const tabBtns = document.querySelectorAll('.procedure-tab-btn');
  const blocks = document.querySelectorAll('.procedure-block');

  function activateTab(target) {
    const btn = document.querySelector('.procedure-tab-btn[data-target="' + target + '"]');
    if (!btn) return;
    tabBtns.forEach(function (b) {
      b.classList.remove('active'); b.setAttribute('aria-selected', 'false');
    });
    btn.classList.add('active'); btn.setAttribute('aria-selected', 'true');
    blocks.forEach(function (block) {
      block.classList.toggle('active', block.id === target);
    });
  }

  tabBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      activateTab(btn.getAttribute('data-target'));
    });
  });

  // Deep-link support: arriving with #procedure-cloud / #procedure-request /
  // #procedure-ssl (e.g. from the Forms page's VPN section, or the "Ordering"
  // nav dropdown) should switch to that tab and scroll to it automatically,
  // not land on a hidden panel. This has to run both on initial page load
  // AND on 'hashchange' — a same-page link click (already on /services,
  // picking a different Ordering sub-item) only fires hashchange, it does
  // not reload the page, so a load-only check misses it entirely.
  function handleHash() {
    const hashTarget = window.location.hash.replace('#', '');
    if (hashTarget && document.getElementById(hashTarget) && document.querySelector('.procedure-tab-btn[data-target="' + hashTarget + '"]')) {
      activateTab(hashTarget);
      document.getElementById(hashTarget).scrollIntoView();
    }
  }
  handleHash();
  window.addEventListener('hashchange', handleHash);
})();
</script>

</x-public-layout>
