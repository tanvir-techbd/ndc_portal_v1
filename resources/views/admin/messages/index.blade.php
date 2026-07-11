<x-admin-layout :title="'Messages'">
  @php
    $statusPill = ['new' => 'status-inactive', 'in_progress' => 'status-pending', 'resolved' => 'status-active'];
    $statusLabel = ['new' => 'New', 'in_progress' => 'In Progress', 'resolved' => 'Resolved'];
  @endphp
  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>Messages</h3><div class="sub">{{ $inquiries->total() }} total @if ($newCount) &middot; {{ $newCount }} new @endif</div></div>
      <div class="admin-panel-actions">
        <a href="{{ route('admin.messages.index') }}" class="abtn abtn-sm {{ ! $status ? 'abtn-primary' : '' }}">All</a>
        <a href="{{ route('admin.messages.index', ['status' => 'new']) }}" class="abtn abtn-sm {{ $status === 'new' ? 'abtn-primary' : '' }}">New</a>
        <a href="{{ route('admin.messages.index', ['status' => 'in_progress']) }}" class="abtn abtn-sm {{ $status === 'in_progress' ? 'abtn-primary' : '' }}">In Progress</a>
        <a href="{{ route('admin.messages.index', ['status' => 'resolved']) }}" class="abtn abtn-sm {{ $status === 'resolved' ? 'abtn-primary' : '' }}">Resolved</a>
      </div>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th><a href="{{ $sortLinks['full_name']['url'] }}" style="color:inherit;">{!! $sortLinks['full_name']['label'] !!}</a></th>
            <th>Subject</th>
            <th><a href="{{ $sortLinks['status']['url'] }}" style="color:inherit;">{!! $sortLinks['status']['label'] !!}</a></th>
            <th><a href="{{ $sortLinks['created_at']['url'] }}" style="color:inherit;">{!! $sortLinks['created_at']['label'] !!}</a></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($inquiries as $inquiry)
            <tr>
              <td>
                <strong>{{ $inquiry->full_name }}</strong>
                @if ($inquiry->organization)<br><span style="color:var(--gray-500);font-size:.76rem;">{{ $inquiry->organization }}</span>@endif
              </td>
              <td>{{ $inquiry->inquiry_type ? ucwords(str_replace('_', ' ', $inquiry->inquiry_type)) : 'General' }}<br><span style="color:var(--gray-500);font-size:.76rem;">{{ Str::limit($inquiry->message, 60) }}</span></td>
              <td><span class="status-pill {{ $statusPill[$inquiry->status] }}">{{ $statusLabel[$inquiry->status] }}</span></td>
              <td>{{ $inquiry->created_at->format('M j, Y g:ia') }}</td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('admin.messages.show', $inquiry) }}" class="row-action-btn" title="View">&#128065;</a>
                  <form method="POST" action="{{ route('admin.messages.destroy', $inquiry) }}" onsubmit="return confirm('Delete this message?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="row-action-btn danger" title="Delete">&#128465;</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="text-align:center;color:var(--gray-500);padding:20px;">No messages yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div style="margin-top:16px;">{{ $inquiries->links('vendor.pagination.ndc') }}</div>
</x-admin-layout>
