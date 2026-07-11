<x-admin-layout :title="'Site Pages'">
  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>Site Pages</h3><div class="sub">Edit the content blocks that drive each public page</div></div>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>Page</th><th>Slug</th><th>Actions</th></tr></thead>
        <tbody>
          @foreach ($pages as $page)
            <tr>
              <td>{{ $page->title }}</td>
              <td><code>{{ $page->slug }}</code></td>
              <td><a href="{{ route('admin.pages.edit', $page->slug) }}" class="row-action-btn">&#9998; Edit</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</x-admin-layout>
