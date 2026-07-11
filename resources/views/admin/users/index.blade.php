<x-admin-layout :title="'Admin Users'">
  <div class="admin-panel" style="margin-bottom:20px;">
    <div class="admin-panel-header"><div><h3>Invite Admin User</h3><div class="sub">Creates a pending account and emails a password-setup link</div></div></div>
    <div class="admin-panel-body">
      @error('email')<div class="admin-field-error" style="margin-bottom:10px;">{{ $message }}</div>@enderror
      <form method="POST" action="{{ route('admin.users.invite') }}">
        @csrf
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" required/>
          </div>
          <div class="admin-form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required/>
          </div>
          <div class="admin-form-group">
            <label for="role">Role *</label>
            <select id="role" name="role" required>
              <option value="content_editor">Content Editor</option>
              <option value="super_admin">Super Admin</option>
            </select>
          </div>
        </div>
        <button type="submit" class="abtn abtn-primary">+ Send Invite</button>
      </form>
    </div>
  </div>

  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>Admin Users</h3><div class="sub">Staff accounts with panel access (<code>is_admin = 1</code>)</div></div>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>User</th><th>Role</th><th>Last Login</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @foreach ($users as $user)
            <tr>
              <td><div class="cell-flex"><div class="admin-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div><div><div class="cell-title">{{ $user->name }}</div><div class="cell-sub">{{ $user->email }}</div></div></div></td>
              <td>{{ $user->role === 'super_admin' ? 'Super Admin' : 'Content Editor' }}</td>
              <td>{{ $user->last_login_at?->format('M j, Y') ?? '—' }}</td>
              <td>
                <span class="status-pill {{ match($user->status) { 'active' => 'status-active', 'pending' => 'status-pending', default => 'status-inactive' } }}">{{ ucfirst($user->status) }}</span>
              </td>
              <td>
                <div class="row-actions">
                  @if ($user->status === 'active' && $user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                      @csrf @method('PUT')
                      <button type="submit" class="row-action-btn danger" title="Suspend">&#128683;</button>
                    </form>
                  @elseif ($user->status === 'suspended')
                    <form method="POST" action="{{ route('admin.users.reactivate', $user) }}">
                      @csrf @method('PUT')
                      <button type="submit" class="row-action-btn" style="color:var(--green-dark);" title="Reactivate">&#9654;</button>
                    </form>
                  @endif
                  @if ($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this admin account?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="row-action-btn danger" title="Delete">&#128465;</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div style="margin-top:16px;">{{ $users->links('vendor.pagination.ndc') }}</div>
</x-admin-layout>
