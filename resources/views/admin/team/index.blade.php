<x-admin-layout :title="'Team Members'">
  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>Team Members</h3><div class="sub">{{ $members->count() }} total</div></div>
      <div class="admin-panel-actions"><a href="{{ route('admin.team.create') }}" class="abtn abtn-primary">+ Add Member</a></div>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Designation</th><th>Group</th><th>Actions</th></tr></thead>
        <tbody>
          @foreach ($members as $member)
            <tr>
              <td>{{ $member->name }}</td>
              <td>{{ $member->designation }}</td>
              <td>{{ $member->group === 'leadership' ? 'Leadership' : 'Technical Staff' }}</td>
              <td>
                <div class="row-actions">
                  <a href="{{ route('admin.team.edit', $member) }}" class="row-action-btn">&#9998;</a>
                  <form method="POST" action="{{ route('admin.team.destroy', $member) }}" onsubmit="return confirm('Remove this team member?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="row-action-btn danger">&#128465;</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</x-admin-layout>
