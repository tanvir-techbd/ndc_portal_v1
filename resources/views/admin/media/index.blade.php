<x-admin-layout :title="'Media Library'">
  <div class="admin-alert admin-alert-success">
    Upload files here first, then attach them from where they belong:
    a <strong>Team Photo</strong> is picked on a member's edit screen under
    <a href="{{ route('admin.team.index') }}">Team Members</a>; a
    <strong>Notice Attachment</strong> (e.g. a tender PDF) is picked on a
    notice's edit screen under <a href="{{ route('admin.notices.index') }}">News &amp; Notices</a>.
    <strong>General</strong> files aren't attached anywhere automatically —
    use them when you just need a shareable link. <strong>Logo</strong> is
    reserved for a future site-branding setting.
  </div>
  <div class="admin-panel" style="margin-bottom:20px;">
    <div class="admin-panel-header"><div><h3>Upload New File</h3></div></div>
    <div class="admin-panel-body">
      <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-grid">
          <div class="admin-form-group">
            <label for="file">File (image, PDF, or Word doc — max 20MB)</label>
            <input type="file" id="file" name="file" required/>
            @error('file')<div class="admin-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="admin-form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
              <option value="general">General</option>
              <option value="notice">Notice Attachment</option>
              <option value="team_photo">Team Photo</option>
              <option value="logo">Logo</option>
            </select>
          </div>
        </div>
        <button type="submit" class="abtn abtn-primary">Upload</button>
      </form>
    </div>
  </div>

  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>Media Library</h3><div class="sub">{{ $assets->total() }} files</div></div>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>File</th><th>Category</th><th>Size</th><th>Uploaded</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse ($assets as $asset)
            <tr>
              <td><a href="{{ $asset->resolvedUrl() }}" target="_blank">{{ $asset->original_filename }}</a></td>
              <td>{{ ucfirst(str_replace('_', ' ', $asset->category)) }}</td>
              <td>{{ number_format($asset->size_bytes / 1024, 1) }} KB</td>
              <td>{{ $asset->created_at->format('M j, Y') }}</td>
              <td>
                <form method="POST" action="{{ route('admin.media.destroy', $asset) }}" onsubmit="return confirm('Delete this file?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="row-action-btn danger">&#128465;</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="text-align:center;color:var(--gray-500);padding:20px;">No files uploaded yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div style="margin-top:16px;">{{ $assets->links('vendor.pagination.ndc') }}</div>
</x-admin-layout>
