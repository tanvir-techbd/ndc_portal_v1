<x-admin-layout :title="'News & Notices'">
  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>News &amp; Notices</h3><div class="sub">{{ $notices->total() }} total</div></div>
      <div class="admin-panel-actions">
        <select onchange="window.location = this.value" style="padding:7px 12px;border:1px solid var(--gray-300);border-radius:4px;font-size:.8rem;background:#fff;">
          <option value="{{ route('admin.notices.index') }}" {{ ! $category ? 'selected' : '' }}>All Categories</option>
          @foreach ($categories as $cat)
            <option value="{{ route('admin.notices.index', ['category' => $cat]) }}" {{ $category === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
          @endforeach
        </select>
        <a href="{{ route('admin.notices.create') }}" class="abtn abtn-primary">+ New Notice</a>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.notices.bulk-action') }}">
      @csrf
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th class="td-checkbox"><input type="checkbox" onclick="document.querySelectorAll('input[name=\'notice_ids[]\']').forEach(c => c.checked = this.checked)"/></th>
              <th>Title</th><th>Category</th><th>Status</th><th>Published</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($notices as $notice)
              <tr>
                <td class="td-checkbox"><input type="checkbox" name="notice_ids[]" value="{{ $notice->id }}"/></td>
                <td>{{ $notice->title }}</td>
                <td><span class="category-pill cat-{{ $notice->category }}">{{ ucfirst($notice->category) }}</span></td>
                <td><span class="status-pill {{ $notice->status === 'published' ? 'status-active' : 'status-pending' }}">{{ ucfirst($notice->status) }}</span></td>
                <td>{{ $notice->published_at?->format('M j, Y') ?? '—' }}</td>
                <td>
                  <div class="row-actions">
                    <a href="{{ route('admin.notices.edit', $notice) }}" class="row-action-btn">&#9998;</a>
                    @if ($notice->status !== 'published')
                      <button type="submit" formaction="{{ route('admin.notices.publish', $notice) }}" formmethod="POST" class="row-action-btn" style="color:var(--green-dark);">&#10003;</button>
                    @endif
                    <button type="submit" formaction="{{ route('admin.notices.destroy', $notice) }}" formmethod="POST" onclick="return confirm('Delete this notice?')" class="row-action-btn danger">&#128465;</button>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" style="text-align:center;color:var(--gray-500);padding:20px;">No notices yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="admin-panel-actions" style="padding:14px 22px;">
        <select name="action" style="padding:7px 12px;border:1px solid var(--gray-300);border-radius:4px;font-size:.8rem;">
          <option value="publish">Publish selected</option>
          <option value="draft">Move to draft</option>
          <option value="delete">Delete selected</option>
        </select>
        <button type="submit" class="abtn abtn-primary">Apply</button>
      </div>
    </form>
  </div>
  <div style="margin-top:16px;">{{ $notices->links('vendor.pagination.ndc') }}</div>
</x-admin-layout>
