<x-admin-layout :title="'Message from ' . $inquiry->full_name">
  @php
    $statusPill = ['new' => 'status-inactive', 'in_progress' => 'status-pending', 'resolved' => 'status-active'];
    $statusLabel = ['new' => 'New', 'in_progress' => 'In Progress', 'resolved' => 'Resolved'];
  @endphp
  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>{{ $inquiry->full_name }}</h3><div class="sub">{{ $inquiry->created_at->format('F j, Y \a\t g:ia') }} &middot; via {{ $inquiry->source === 'account_access_page' ? 'Support & Contact page' : 'Contact page' }}</div></div>
      <div class="admin-panel-actions">
        <span class="status-pill {{ $statusPill[$inquiry->status] }}">{{ $statusLabel[$inquiry->status] }}</span>
      </div>
    </div>
    <div class="admin-panel-body">
      <div class="admin-form-grid">
        <div class="admin-form-group">
          <label>Organization</label>
          <p>{{ $inquiry->organization ?: '—' }}</p>
        </div>
        <div class="admin-form-group">
          <label>Subject</label>
          <p>{{ $inquiry->inquiry_type ? ucwords(str_replace('_', ' ', $inquiry->inquiry_type)) : 'General' }}</p>
        </div>
        <div class="admin-form-group">
          <label>Email</label>
          <p><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></p>
        </div>
        <div class="admin-form-group">
          <label>Phone</label>
          <p>{{ $inquiry->phone ?: '—' }}</p>
        </div>
        <div class="admin-form-group full">
          <label>Message</label>
          <p style="white-space:pre-wrap;background:var(--off-white);border:1px solid var(--gray-100);border-radius:6px;padding:14px 16px;">{{ $inquiry->message }}</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.messages.update-status', $inquiry) }}" style="display:flex;gap:10px;align-items:center;margin-top:8px;">
        @csrf
        @method('PUT')
        <select name="status">
          <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
          <option value="in_progress" {{ $inquiry->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
          <option value="resolved" {{ $inquiry->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
        </select>
        <button type="submit" class="abtn abtn-primary">Update Status</button>
        <a href="mailto:{{ $inquiry->email }}" class="abtn">Reply by Email</a>
        <a href="{{ route('admin.messages.index') }}" class="abtn">Back to Messages</a>
      </form>
    </div>
  </div>
</x-admin-layout>
