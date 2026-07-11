<x-public-layout :title="'Account Access – National Data Center'" :meta-description="'Account access for the National Data Center site — admin login, the NDC Cloud Management Portal (CMP), or send us a message.'">
<div class="auth-page">
  <div class="auth-split">
    <div class="auth-split-content">
      <p class="auth-split-eyebrow">&#9670; NDC Account Access</p>
      <h2>Where do you need to <span>go?</span></h2>
      <p>This site is informational — it doesn't host customer accounts. Real service ordering, billing, and support tickets are managed on the NDC Cloud Management Portal.</p>
      <ul class="auth-feature-list">
        <li><span class="check">&#10003;</span> Manage Cloud Services (IaaS / PaaS / SaaS) and VPS instances on CMP</li>
        <li><span class="check">&#10003;</span> Place and track orders directly on CMP</li>
        <li><span class="check">&#10003;</span> NDC staff manage this site's content via the Admin Login</li>
        <li><span class="check">&#10003;</span> Everyone else can leave us a message below</li>
      </ul>
      <div class="auth-split-badges">
        <span class="auth-org-badge">&#128737; ISO 27001 Certified</span>
        <span class="auth-org-badge">&#9889; Tier-III Facility</span>
      </div>
    </div>
  </div>

  <div class="auth-form-side">
    <div class="auth-card">
      <div class="auth-card-logo">
        <div class="auth-card-logo-text">National Data Center<small>Account Access</small></div>
      </div>

      <h1>Where do you need to go?</h1>
      <p class="auth-sub">Pick the option that matches what you're here to do.</p>

      @if (session('status'))
        <div class="admin-alert admin-alert-success">{{ session('status') }}</div>
      @endif

      <div class="access-option-card">
        <h4>&#128737; NDC Staff / Admin</h4>
        <p>Manage site content — notices, pricing, pages, and media — through the admin panel.</p>
        <a href="{{ route('admin.login') }}" class="auth-submit-btn">Go to Admin Login &nbsp;&#8594;</a>
      </div>

      <div class="access-option-card">
        <h4>&#9729; Manage Services or Place an Order</h4>
        <p>Log in, order, and manage your Cloud/Request-Based services on the NDC Cloud Management Portal.</p>
        <a href="{{ setting('cmp_portal_url', 'https://cmp.bcc.gov.bd') }}" target="_blank" rel="noopener" class="auth-submit-btn">Open CMP Portal &nbsp;&#8594;</a>
        <div class="qof-note">&#8505; CMP is currently in testing.</div>
      </div>

      <div class="auth-divider"><span>OR</span></div>

      <p class="auth-sub" style="margin-bottom:16px;">Just have something to tell us?</p>

      <form id="loginMessageForm" data-captcha-form method="POST" action="{{ route('contact-inquiries.store') }}" autocomplete="off">
        @csrf
        <input type="hidden" name="source" value="account_access_page"/>
        <div class="auth-row-split">
          <div class="auth-form-group">
            <label for="lmName">Full Name *</label>
            <input type="text" id="lmName" name="full_name" value="{{ old('full_name') }}" required placeholder="Your full name"/>
            @error('full_name')<div class="admin-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="auth-form-group">
            <label for="lmOrg">Organization</label>
            <input type="text" id="lmOrg" name="organization" value="{{ old('organization') }}" placeholder="Ministry / Agency name"/>
          </div>
        </div>
        <div class="auth-row-split">
          <div class="auth-form-group">
            <label for="lmEmail">Email *</label>
            <input type="email" id="lmEmail" name="email" value="{{ old('email') }}" required placeholder="you@ministry.gov.bd"/>
            @error('email')<div class="admin-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="auth-form-group">
            <label for="lmPhone">Phone Number</label>
            <input type="tel" id="lmPhone" name="phone" value="{{ old('phone') }}" placeholder="+880 1xxx-xxxxxx"/>
          </div>
        </div>
        <div class="auth-form-group">
          <label for="lmMessage">Message *</label>
          <textarea id="lmMessage" name="message" required placeholder="How can we help?">{{ old('message') }}</textarea>
          @error('message')<div class="admin-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="captcha-box" data-captcha-placeholder hidden>
          <label class="auth-checkbox"><input type="checkbox" name="captcha_verified" value="1" required/> I'm not a robot &mdash; verification required after your first submission</label>
          @error('captcha_verified')<div class="admin-field-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="auth-submit-btn">Send Message &nbsp;&#8594;</button>
      </form>
    </div>
  </div>
</div>
</x-public-layout>
