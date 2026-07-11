<x-admin-layout :title="'Services Catalog'">
  <div class="admin-panel" style="margin-bottom:20px;">
    <div class="admin-panel-header">
      <div><h3>Service Groups</h3><div class="sub">Section summaries — shown on the homepage featured grid and as headers on the full Services page</div></div>
      <div class="admin-panel-actions"><a href="{{ route('admin.services.create') }}" class="abtn abtn-primary abtn-sm">+ Add Service</a></div>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Featured</th><th>Visible</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse ($groups as $service)
            <tr>
              <td>{{ $service->name }}</td>
              <td><span class="status-pill {{ $service->is_featured ? 'status-active' : 'status-inactive' }}">{{ $service->is_featured ? 'Featured' : 'Not Featured' }}</span></td>
              <td><span class="status-pill {{ $service->is_visible ? 'status-active' : 'status-inactive' }}">{{ $service->is_visible ? 'Visible' : 'Hidden' }}</span></td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('admin.services.edit', $service) }}" class="row-action-btn" title="Edit">&#9998;</a>
                  <form method="POST" action="{{ route('admin.services.toggle-featured', $service) }}">
                    @csrf @method('PUT')
                    <button type="submit" class="row-action-btn" title="Toggle featured">&#9733;</button>
                  </form>
                  <form method="POST" action="{{ route('admin.services.toggle-visible', $service) }}">
                    @csrf @method('PUT')
                    <button type="submit" class="row-action-btn" title="Toggle visibility">&#128065;</button>
                  </form>
                  <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service group? Catalog cards under it must be removed or reassigned first.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="row-action-btn danger" title="Delete">&#128465;</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="4" style="text-align:center;color:var(--gray-500);padding:20px;">No service groups yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @foreach ($groups as $group)
    <div class="admin-panel" style="margin-bottom:20px;">
      <div class="admin-panel-header">
        <div><h3>{{ $group->name }} — Catalog Cards</h3><div class="sub">Shown on the full Services page under this group</div></div>
        <div class="admin-panel-actions"><a href="{{ route('admin.services.create') }}?group={{ $group->slug }}" class="abtn abtn-primary abtn-sm">+ Add Card</a></div>
      </div>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead><tr><th>Name</th><th>Tag</th><th>Visible</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse (($details[$group->slug] ?? []) as $service)
              <tr>
                <td>{{ $service->name }}</td>
                <td style="color:var(--gray-500);">{{ $service->tag }}</td>
                <td><span class="status-pill {{ $service->is_visible ? 'status-active' : 'status-inactive' }}">{{ $service->is_visible ? 'Visible' : 'Hidden' }}</span></td>
                <td>
                  <div class="row-actions">
                    <a href="{{ route('admin.services.edit', $service) }}" class="row-action-btn" title="Edit">&#9998;</a>
                    <form method="POST" action="{{ route('admin.services.toggle-visible', $service) }}">
                      @csrf @method('PUT')
                      <button type="submit" class="row-action-btn" title="Toggle visibility">&#128065;</button>
                    </form>
                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this catalog card?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="row-action-btn danger" title="Delete">&#128465;</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" style="text-align:center;color:var(--gray-500);padding:20px;">No cards yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  @endforeach
</x-admin-layout>
