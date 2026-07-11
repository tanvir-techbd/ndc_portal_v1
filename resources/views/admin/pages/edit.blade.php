<x-admin-layout :title="'Edit: ' . $page->title">
  <div class="admin-panel">
    <div class="admin-panel-header">
      <div><h3>Edit: {{ $page->title }}</h3><div class="sub">Changes here update the live <code>{{ $page->slug }}</code> page immediately.</div></div>
    </div>
    <div class="admin-panel-body">
      <form method="POST" action="{{ route('admin.pages.update', $page->slug) }}">
        @csrf
        @method('PUT')
        <div class="admin-form-group">
          <label for="pageTitle">Page Title</label>
          <input type="text" id="pageTitle" name="title" value="{{ old('title', $page->title) }}" required/>
        </div>
        <div class="admin-form-grid">
          @foreach ($page->content_blocks as $key => $value)
            <div class="pb-section {{ is_array($value) || (is_string($value) && strlen($value) > 90) ? 'full' : '' }}">
              @include('admin.pages.partials.field', ['name' => "blocks[{$key}]", 'label' => ucwords(str_replace('_', ' ', $key)), 'value' => $value])
            </div>
          @endforeach
        </div>
        @error('blocks')<div class="admin-field-error">{{ $message }}</div>@enderror
        <div class="admin-panel-actions" style="padding-top:18px;">
          <a href="{{ route('admin.pages.index') }}" class="abtn">Cancel</a>
          <a href="{{ url('/') }}" target="_blank" class="abtn">Preview Live Site</a>
          <button type="submit" class="abtn abtn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</x-admin-layout>
