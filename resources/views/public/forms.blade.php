@php
  $sections = [
    [
      'id' => 'general', 'eyebrow' => 'Service Orders', 'title' => 'General & Frame Agreements',
      'desc' => 'Cross-service agreements and documents used across multiple order types.',
      'cards' => [
        ['name' => 'Additional RBS Requirement Form', 'desc' => 'Used to request supplementary Request Based Service resources beyond your existing package.', 'files' => ['Download (.docx)' => 'Additional-RBS-Requirement-Form.docx']],
        ['name' => 'Cloud Service Frame Agreement', 'desc' => 'Governing agreement for organizations onboarding to eGovCloud services.', 'files' => ['English' => 'Cloud-Service-Frame-Agreement-English.pdf', 'Bangla' => 'Cloud-Service-Frame-Agreement-Bangla.docx']],
        ['name' => 'Cloud Quota Upgrade Request Form', 'desc' => "Request an increase to your organization's cloud resource quota.", 'files' => ['English' => 'Cloud-Quota-Increase-Letter-Sample-English.docx', 'Bangla' => 'Cloud-Quota-Increase-Letter-Sample-Bangla.docx']],
        ['name' => 'NDC Service Portfolio', 'tier' => 'Reference', 'desc' => 'Full catalog of NDC services, specifications and infrastructure overview (large file, ~67 MB).', 'files' => ['Download (.pdf)' => 'NDC-Service-Portfolio.pdf'], 'portfolio' => true, 'featured' => true],
      ],
    ],
    [
      'id' => 'vpn', 'eyebrow' => 'Network · Secure Access', 'title' => 'NDC VPN Service Forms',
      'desc' => 'Forms and guides for requesting VPN access, authorizing vendor/partner access, and managing SSL client certificates for secure connectivity to NDC infrastructure.',
      'related_link' => ['label' => 'New to VPN access? Read the SSL Certificate & VPN Access procedure first', 'url' => route('services') . '#procedure-ssl'],
      'cards' => [
        ['name' => 'NDC VPN Access Form', 'desc' => 'Primary request form to activate VPN access for secure connectivity to NDC cloud resources.', 'files' => ['Download (.pdf)' => 'NDC-VPN-Access-Form.pdf'], 'featured' => true],
        ['name' => 'VPN Authorization Letter for Vendor/Partner', 'desc' => "Authorizes a third-party vendor or partner to access NDC infrastructure via VPN on your organization's behalf.", 'files' => ['English' => 'VPN-Authorization-Letter-Vendor-English.docx', 'Bangla' => 'VPN-Authorization-Letter-Vendor-Bangla.docx']],
        ['name' => 'SSL Certificate Renewal (Postpaid) Letter', 'desc' => 'Sample subscription letter for postpaid SSL/VPN certificate renewal.', 'files' => ['Download (.docx)' => 'SSL-Certificate-Renewal-Postpaid-Letter.docx']],
        ['name' => 'BCC UniVPN Installation Guide', 'tier' => 'Guide', 'desc' => 'Step-by-step installation procedure for new VPN client setup.', 'files' => ['Download (.docx)' => 'BCC-UniVPN-Installation-Guide.docx']],
        ['name' => 'NDC VPN CA Certificate Renewal Process', 'tier' => 'Guide', 'desc' => 'Procedure for renewing the Certificate Authority (CA) certificate used for VPN authentication.', 'files' => ['Download (.pdf)' => 'NDC-VPN-CA-Certificate-Renewal-Process.pdf']],
        ['name' => 'Digital Signature (SSL Client) Manual', 'tier' => 'Guide', 'desc' => 'Registration and enrollment manual for SSL Client digital signatures on Windows Keystore.', 'files' => ['Download (.docx)' => 'Digital-Signature-SSLClient-Manual.docx']],
        ['name' => 'VPN Service Activation Process (Bangla)', 'desc' => 'SSL Certificate (Type-II) subscription and VPN activation process, in Bangla.', 'files' => ['Download (.docx)' => 'SSL-VPN-Activation-Process-Bangla.docx']],
      ],
    ],
    [
      'id' => 'vps', 'eyebrow' => 'Request Based Service', 'title' => 'VPS Service Forms',
      'desc' => 'Forms required to order or modify a Virtual Private Server package.',
      'cards' => [
        ['name' => 'VPS Service Order Form', 'desc' => 'New VPS provisioning request with package, OS, and organization details.', 'request' => true],
        ['name' => 'VPS Upgrade/Downgrade Request', 'desc' => 'Change an existing VPS package tier.', 'request' => true],
      ],
    ],
    [
      'id' => 'backup', 'eyebrow' => 'Request Based Service', 'title' => 'Backup Service Forms',
      'desc' => 'Forms for enabling file/directory-level backup alongside another RBS order.',
      'cards' => [
        ['name' => 'Backup Service Order Form', 'desc' => 'Request backup enablement and retention configuration for an existing service.', 'request' => true],
      ],
    ],
    [
      'id' => 'loadbalancer', 'eyebrow' => 'Request Based Service', 'title' => 'Load Balancer Forms',
      'desc' => 'Forms for provisioning a Load Balancer in front of your hosted application or VPS.',
      'cards' => [
        ['name' => 'Load Balancer Order Form', 'desc' => 'Request Network Load Balancer provisioning and backend configuration.', 'request' => true],
      ],
    ],
    [
      'id' => 'database', 'eyebrow' => 'Request Based Service', 'title' => 'Managed Database Service Forms',
      'desc' => 'Forms for Managed Database Service and MySQL Database Service orders.',
      'cards' => [
        ['name' => 'Managed Database Order Form', 'desc' => 'Request a new managed database instance with tier and storage requirements.', 'request' => true],
        ['name' => 'MySQL Database Order Form', 'desc' => 'Request a dedicated MySQL Database Service instance.', 'request' => true],
      ],
    ],
    [
      'id' => 'email', 'eyebrow' => 'Request Based Service', 'title' => 'Email Service Forms',
      'desc' => 'Forms for onboarding official government email on the Zimbra platform.',
      'cards' => [
        ['name' => 'Email Service Order Form', 'desc' => 'New domain onboarding and mailbox provisioning request.', 'request' => true],
        ['name' => 'Delegated Admin Request Form', 'desc' => "Request delegated administration rights for your organization's domain.", 'request' => true],
      ],
    ],
    [
      'id' => 'collocation', 'eyebrow' => 'Request Based Service', 'title' => 'Collocation (RUA/RSA) Forms',
      'desc' => 'Forms for Rack Unit Allocation and Rack Space Allocation orders.',
      'cards' => [
        ['name' => 'Rack Unit Allocation Order Form', 'desc' => 'Request partial rack space (RUA) for your equipment.', 'request' => true],
        ['name' => 'Rack Space Allocation Order Form', 'desc' => 'Request a full rack (RSA) with power and connectivity specification.', 'request' => true],
      ],
    ],
    [
      'id' => 'private-access', 'eyebrow' => 'Request Based Service', 'title' => 'Private Access Service Forms',
      'desc' => 'Forms for provisioning a dedicated private network link into NDC.',
      'cards' => [
        ['name' => 'Private Access Order Form', 'desc' => 'Request Basic or Standard private access provisioning with port requirements.', 'request' => true],
      ],
    ],
    [
      'id' => 'app-hosting', 'eyebrow' => 'Request Based Service', 'title' => 'Application Hosting Forms',
      'desc' => "Forms for onboarding an application to NDC's shared hosting infrastructure.",
      'cards' => [
        ['name' => 'Application Hosting Order Form', 'desc' => 'Request Linux or Windows application hosting with platform requirements.', 'request' => true],
      ],
    ],
    [
      'id' => 'web-hosting', 'eyebrow' => 'Request Based Service', 'title' => 'Web Hosting Forms',
      'desc' => "Forms for onboarding an organizational website to NDC's shared web hosting.",
      'cards' => [
        ['name' => 'Web Hosting Order Form', 'desc' => 'Request Linux or Windows web hosting with domain and storage requirements.', 'request' => true],
      ],
    ],
  ];
@endphp
<x-public-layout :title="'Forms & Agreements – National Data Center'">

<div class="page-hero"><div class="wrap"><div class="page-hero-inner"><div class="page-hero-text"><div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / <span>Forms</span></div><h2>Forms &amp; Agreements</h2><p>{{ $blocks['intro'] ?? 'Downloadable forms, frame agreements, and reference documents for NDC services.' }}</p></div></div></div></div>

<div class="pricing-subnav">
  <div class="container">
    <div class="pricing-subnav-inner">
      <ul class="pricing-tabs">
        @foreach ($sections as $i => $section)
          <li><a href="#{{ $section['id'] }}" class="{{ $i === 0 ? 'active' : '' }}">{{ Str::before($section['title'], ' Forms') }}</a></li>
        @endforeach
      </ul>
    </div>
  </div>
</div>

<div class="wrap" style="padding:36px 24px;">
  @foreach ($sections as $section)
    <div class="pricing-section" id="{{ $section['id'] }}" data-pricing-section>
      <div class="pricing-section-header">
        <p class="pricing-section-eyebrow">{{ $section['eyebrow'] }}</p>
        <h2 class="pricing-section-title">{{ $section['title'] }}</h2>
        <p class="pricing-section-desc">{{ $section['desc'] }}</p>
        @isset ($section['related_link'])
          <p class="pricing-section-desc"><a href="{{ $section['related_link']['url'] }}" style="color:var(--green-dark);font-weight:600;">{{ $section['related_link']['label'] }} &#8594;</a></p>
        @endisset
      </div>
      <div class="pricing-cards">
        @foreach ($section['cards'] as $card)
          <div class="pricing-card {{ !empty($card['featured']) ? 'featured' : '' }}">
            <span class="pc-tier basic">{{ $card['tier'] ?? 'Form' }}</span>
            <div class="pc-name">{{ $card['name'] }}</div>
            <ul class="pc-specs" style="margin-bottom:16px;"><li style="border-bottom:none;padding:7px 0;color:var(--gray-500);font-size:.8rem;line-height:1.6;">{{ $card['desc'] }}</li></ul>
            @if (!empty($card['request']))
              <a href="{{ route('contact') }}" class="pc-order-btn">Request This Form &#8594;</a>
            @elseif (count($card['files']) > 1)
              <div style="display:flex;gap:8px;">
                @foreach ($card['files'] as $label => $file)
                  <a href="{{ asset('storage/docs/' . (!empty($card['portfolio']) ? 'portfolio' : 'forms') . '/' . $file) }}" class="pc-order-btn" style="flex:1;padding:10px 6px;" download>&#128196; {{ $label }}</a>
                @endforeach
              </div>
            @else
              @foreach ($card['files'] as $label => $file)
                <a href="{{ asset('storage/docs/' . (!empty($card['portfolio']) ? 'portfolio' : 'forms') . '/' . $file) }}" class="pc-order-btn" download>&#128196; {{ $label }}</a>
              @endforeach
            @endif
          </div>
        @endforeach
      </div>
    </div>
  @endforeach
</div>

</x-public-layout>
