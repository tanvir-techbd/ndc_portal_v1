<x-public-layout :title="'Support & Contact – National Data Center'">

<div class="page-hero"><div class="wrap"><div class="page-hero-inner"><div class="page-hero-text"><div class="breadcrumb"><a href="{{ route('home') }}">Home</a> / <span>Support &amp; Contact</span></div><h2>Support &amp; Contact</h2><p>Reach our team for service inquiries, technical support, tender information, or general assistance.</p></div></div></div></div>

<section class="section">
  <div class="wrap" style="display:grid;grid-template-columns:1.3fr 1fr;gap:32px;">
    <div>
      <div class="contact-form-panel">
        <h3>Send Us a Message</h3>
        <p>Fill out this form for service inquiries, feedback, or general questions. Our team will respond within one business day.</p>

        @if (session('status'))
          <div class="admin-alert admin-alert-success" style="background:var(--green-pale);border:1px solid var(--green-light);color:var(--green-dark);padding:12px 16px;border-radius:5px;margin-bottom:16px;">{{ session('status') }}</div>
        @endif

        <form id="contactForm" data-captcha-form method="POST" action="{{ route('contact-inquiries.store') }}" autocomplete="off">
          @csrf
          <input type="hidden" name="source" value="contact_page"/>
          <div class="form-row">
            <div class="form-group"><label for="cfName">Full Name *</label><input type="text" id="cfName" name="full_name" value="{{ old('full_name') }}" required placeholder="Your full name"/></div>
            <div class="form-group"><label for="cfOrg">Organization *</label><input type="text" id="cfOrg" name="organization" value="{{ old('organization') }}" required placeholder="Ministry / Agency name"/></div>
          </div>
          <div class="form-row">
            <div class="form-group"><label for="cfEmail">Email Address *</label><input type="email" id="cfEmail" name="email" value="{{ old('email') }}" required placeholder="official@ministry.gov.bd"/></div>
            <div class="form-group"><label for="cfPhone">Phone Number</label><input type="tel" id="cfPhone" name="phone" value="{{ old('phone') }}" placeholder="+880 1xxx-xxxxxx"/></div>
          </div>
          <div class="form-group">
            <label for="cfSubject">Subject / Inquiry Type *</label>
            <select id="cfSubject" name="inquiry_type" required>
              <option value="">-- Select subject --</option>
              <option value="cloud">Cloud Service Inquiry (IaaS/PaaS/SaaS)</option>
              <option value="hosting">Web &amp; Application Hosting</option>
              <option value="colocation">Colocation Service</option>
              <option value="email">Email Service (Zimbra)</option>
              <option value="service_order">Service Order Assistance</option>
              <option value="technical_support">Technical Support</option>
              <option value="tender">Tender / Procurement</option>
              <option value="general">General Inquiry</option>
              <option value="feedback">Feedback / Complaint</option>
            </select>
          </div>
          <div class="form-group"><label for="cfMessage">Message *</label><textarea id="cfMessage" name="message" required placeholder="Please describe your inquiry in detail...">{{ old('message') }}</textarea></div>
          <div class="captcha-box" data-captcha-placeholder hidden>
            <label><input type="checkbox" name="captcha_verified" value="1" required/> I'm not a robot &mdash; verification required after your first submission</label>
          </div>
          <button type="submit" class="btn-submit">&#9993; Submit Inquiry</button>
          <p style="font-size:.72rem;color:var(--gray-500);margin-top:10px;text-align:center;">For urgent technical issues, please call the helpdesk directly: {{ setting('helpdesk_phone') }}</p>
        </form>
      </div>

      <h3 style="font-family:var(--font-d);font-size:1.4rem;font-weight:700;color:var(--green-dark);margin:28px 0 16px;">Frequently Asked Questions</h3>
      <div class="faq-list">
        @foreach (($blocks['faqs'] ?? []) as $faq)
          <details class="faq-item">
            <summary>{{ $faq['q'] }}</summary>
            <div class="faq-body">{{ $faq['a'] }}</div>
          </details>
        @endforeach
      </div>
    </div>

    <div class="info-panel">
      <div class="info-card">
        <h4>&#128295; Technical Support Portal</h4>
        <p>For account access, service tickets, and technical support requests, use the BCC Support Portal.</p>
        <a href="{{ setting('support_portal_url', 'https://support.bcc.gov.bd') }}" target="_blank" rel="noopener" class="pt-order-btn" style="display:inline-block;margin-top:10px;">Open Support Portal &#8594;</a>
      </div>
      <div class="info-card">
        <h4>&#127968; Visit Us</h4>
        <p>{{ setting('office_address') }}</p>
      </div>
      <div class="info-card">
        <h4>&#128222; Call / Email</h4>
        <p>{{ setting('helpdesk_phone') }}<br>{{ setting('helpdesk_email') }}<br>{{ setting('service_hours') }}</p>
      </div>
      <div class="info-card">
        <h4>&#128101; Department Contacts</h4>
        <div class="dept-grid" style="display:grid;gap:8px;margin-top:8px;">
          @foreach (($blocks['departments'] ?? []) as $dept)
            <div class="dept-card"><h5>{{ $dept['name'] }}</h5><div class="dept-email">{{ $dept['email'] }}</div><div class="dept-desc">{{ $dept['desc'] }}</div></div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

</x-public-layout>
